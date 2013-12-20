<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  MEN AT WORK 2013
 * @package    avisota
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Backend;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Event\RemoveRecipientEvent;
use Avisota\Recipient\MutableRecipient;
use Avisota\RecipientSource\RecipientSourceInterface;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Query\QueryBuilder;

class Cron extends \Controller
{

	/**
	 *
	 * @var Cron 
	 */
	protected static $instance = null;
	
	/**
	 * @static
	 * @return Cron
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new Cron();
		}
		return self::$instance;
	}

	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	/**
	 * Send a notify for the option. 
	 * Only send XXX mails per run.
	 *  
	 * @return void
	 */
	public function cronNotifyRecipients()
	{
		// Check if notifications should be send.
		if (!$GLOBALS['TL_CONFIG']['avisota_send_notification'])
		{
			return false;
		}

		$this->loadLanguageFile('avisota_subscription');

		$entityManager           = EntityHelper::getEntityManager();
		$subscriptionRepository  = $entityManager->getRepository('Avisota\Contao:RecipientSubscription');
		$intCountSend            = 0;

		$resendDate  = $GLOBALS['TL_CONFIG']['avisota_notification_time'] * 24 * 60 * 60;
		$now         = time();

		// Get all recipients.
		$queryBuilder = EntityHelper::getEntityManager()->createQueryBuilder();
		$queryBuilder
				->select('r')
				->from('Avisota\Contao:Recipient', 'r')
				->innerJoin('Avisota\Contao:RecipientSubscription', 's', 'WITH', 's.recipient=r.id')
				->where('s.confirmed=0')
				->andWhere('s.reminderCount < ?1')
				->setParameter(1, $GLOBALS['TL_CONFIG']['avisota_notification_count']);
		$queryBuilder->orderBy('r.email');
		
		// Execute Query.
		$query                = $queryBuilder->getQuery();
		$integratedRecipients = $query->getResult();
		
		// Check each recipient with open subscription.
		foreach ($integratedRecipients as $integratedRecipient)
		{
			$subscriptions = $subscriptionRepository->findBy(array('recipient' => $integratedRecipient->id, 'confirmed' => 0), array('updatedAt' => 'asc'));
			$tokens        = array();
			$blnNotify     = false;

			foreach ($subscriptions as $subscription)
			{
				// Check if we are over the $resendDate date.
				if (($subscription->updatedAt->getTimestamp() + $resendDate) > $now)
				{
					continue;
				}

				// Set some data.
				$blnNotify = true;
				$tokens[]  = $subscription->getToken();

				// Update the subscription.
				$subscription->updatedAt     = new \Datetime();
				$subscription->reminderSent  = new \Datetime();
				$subscription->reminderCount = $subscription->reminderCount + 1;

				// Save.
				$entityManager->persist($subscription);
			}

			// Check if we have to send a notify and if we have a subscription module.
			if ($blnNotify && $subscription->getSubscriptionModule())
			{
				$subscription = $subscriptions[0];

				$parameters = array(
					'email' => $integratedRecipient->email,
					'token' => implode(',', $tokens),
				);

				$arrPage = $this->Database
						->prepare('SELECT * FROM tl_page WHERE id = (SELECT avisota_form_target FROM tl_module WHERE id = ?)')
						->limit(1)
						->execute($subscription->getSubscriptionModule())
						->fetchAssoc();

				$objNextPage = $this->getPageDetails($arrPage['id']);
				$strUrl      = $this->generateFrontendUrl($objNextPage->row(), null, $objNextPage->rootLanguage);

				$url = $this->generateFrontendUrl($arrPage);
				$url .= (strpos($url, '?') === false ? '?' : '&');
				$url .= http_build_query($parameters);

				$newsletterData         = array();
				$newsletterData['link'] = (object) array(
							'url' => \Environment::getInstance()->base . $url,
							'text' => $GLOBALS['TL_LANG']['avisota_subscription']['confirmSubscription'],
				);

				// Try to send the email.
				try
				{
					$this->sendMessage($integratedRecipient, $GLOBALS['TL_CONFIG']['avisota_notification_mail'], $GLOBALS['TL_CONFIG']['avisota_default_transport'], $newsletterData);
				}
				catch (\Exception $exc)
				{
					$this->log(sprintf('Unable to send reminder to "%s" with error message - %s', $integratedRecipient->email, $exc->getMessage()), __CLASS__ . ' | ' . __FUNCTION__, TL_ERROR);
				}

				// Update recipient;
				$integratedRecipient->updatedAt = new \DateTime();

				// Set counter.
				$intCountSend++;
			}

			// Send only 5 mails per run.
			if ($intCountSend >= 5)
			{
				break;
			}
		}

		$entityManager->flush();
	}
	
	public function cronCleanupRecipientList()
	{
		
		$entityManager          = EntityHelper::getEntityManager();
		$subscriptionRepository = $entityManager->getRepository('Avisota\Contao:RecipientSubscription');
		$eventDispatcher        = $GLOBALS['container']['event-dispatcher'];

		$cleanupDate = new \DateTime();
		$cleanupDate->setTimezone(new \DateTimeZone(date_default_timezone_get()));
		$cleanupDate->modify('-'. $GLOBALS['TL_CONFIG']['avisota_cleanup_time'] . ' day');
		
		$queryBuilder = EntityHelper::getEntityManager()->createQueryBuilder();
		$queryBuilder
			->select('s')
			->from('Avisota\Contao:RecipientSubscription', 's')
			->where('s.confirmed=0')
			->andWhere('s.updatedAt < :cleanupDate')
			->setParameter('cleanupDate', $cleanupDate );
		$query = $queryBuilder->getQuery();
		$outdatedSubscriptions = $query->getResult();
		
		foreach ($outdatedSubscriptions as $subScription) {
			$recipient = $subScription->getRecipient();
			$entityManager->remove($subScription);
			$entityManager->flush();

			$remainingSubscriptions = $subscriptionRepository
				->findBy(array('recipient' => $recipient->getId()));
			if (!$remainingSubscriptions || !count($remainingSubscriptions)) {
				$eventDispatcher->dispatch(RemoveRecipientEvent::NAME, new RemoveRecipientEvent($recipient));
				$entityManager->remove($recipient);
				$entityManager->flush();
			}
		}
	}
	
	/**
	 * Send mail.
	 * 
	 * @param type $recipient
	 * @param type $mailBoilerplateId
	 * @param type $transportId
	 * @param type $newsletterData
	 * 
	 * @throws \RuntimeException
	 */
	protected function sendMessage($recipient, $mailBoilerplateId, $transportId, $newsletterData)
	{
		$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
		$messageEntity     = $messageRepository->find($mailBoilerplateId);

		if (!$messageEntity)
		{
			throw new \RuntimeException('Could not find message id ' . $mailBoilerplateId);
		}

		/** @var MessagePreRendererInterface $renderer */
		$renderer           = $GLOBALS['container']['avisota.renderer'];
		$preRenderedMessage = $renderer->renderMessage($messageEntity);
		$message            = $preRenderedMessage->render($recipient, $newsletterData);

		/** @var TransportInterface $transport */
		$transport = $GLOBALS['container']['avisota.transport.' . $transportId];

		$transport->send($message);
	}
}
