<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\DataContainer\DataProvider;

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Entity\RecipientSubscription;
use Avisota\Contao\Event\RecipientMigrateCollectPersonalsEvent;
use Contao\Doctrine\ORM\EntityHelper;
use DcGeneral\Data\CollectionInterface;
use DcGeneral\Data\ConfigInterface;
use DcGeneral\Data\DriverInterface;
use DcGeneral\Data\ModelInterface;
use DcGeneral\Data\NoOpDriver;
use Doctrine\DBAL\Driver\PDOStatement;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RecipientMigrateDataProvider extends NoOpDriver
{
	/**
	 * {@inheritdoc}
	 */
	public function save(ModelInterface $objItem)
	{
		global $container;

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $container['event-dispatcher'];

		/** @var \Doctrine\DBAL\Connection $connection */
		$connection = $container['doctrine.connection.default'];

		$migrationSettings = $objItem->getPropertiesAsArray();

		$channels                  = array();
		$channelMailingListMapping = array();
		foreach ($migrationSettings['channels'] as $channel) {
			$mailingList                         = $channel['mailingList'];
			$channel                             = $channel['channel'];
			$channels[]                          = $connection->quote($channel);
			$channelMailingListMapping[$channel] = $mailingList;
		}

		$queryBuilder = $connection->createQueryBuilder();
		/** @var PDOStatement $stmt */
		$stmt = $queryBuilder
			->select('*')
			->from('tl_newsletter_recipients', 'r')
			->where(
				$queryBuilder
					->expr()
					->in('pid', $channels)
			)
			->execute();

		$entityManager       = EntityHelper::getEntityManager();
		$recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');

		$user = \BackendUser::getInstance();

		$skipped  = 0;
		$migrated = 0;

		$contaoRecipients = $stmt->fetchAll();
		foreach ($contaoRecipients as $contaoRecipientData) {
			$recipient = $recipientRepository->findOneBy(array('email' => $contaoRecipientData['email']));

			if (!$recipient) {
				$recipient = new Recipient();
				$recipient->setEmail($contaoRecipientData['email']);
				$recipient->setAddedOn($recipient['addedOn'] ? $recipient['addedOn'] : new \DateTime());
				$recipient->setAddedBy($user->id);
			}
			else if (!$migrationSettings['overwrite']) {
				$skipped++;
				continue;
			}

			$event = new RecipientMigrateCollectPersonalsEvent($migrationSettings, $contaoRecipientData, $recipient);
			$eventDispatcher->dispatch(RecipientMigrateCollectPersonalsEvent::NAME, $event);

			$mailingList  = $channelMailingListMapping[$contaoRecipientData['pid']];
			$subscription = new RecipientSubscription();
			$subscription->setList('mailing_list:' . $mailingList);
			$subscription->setRecipient($recipient);
			$subscription->setConfirmed((bool) $contaoRecipientData['active']);

			$entityManager->persist($recipient);
			$entityManager->persist($subscription);
			$migrated++;
		}
		$entityManager->flush();

		if (!is_array($_SESSION['TL_CONFIRM'])) {
			$_SESSION['TL_CONFIRM'] = (array) $_SESSION['TL_CONFIRM'];
		}
		$_SESSION['TL_CONFIRM'][] = sprintf(
			$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['migrated'],
			$migrated,
			$skipped
		);
	}
}
