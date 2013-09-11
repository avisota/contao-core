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

namespace Avisota\Contao\Recipient;

use Avisota\Contao\Event\RecipientMigrateCollectPersonalsEvent;
use Doctrine\DBAL\Driver\PDOStatement;

class Migrate extends \Controller
{
	static public function collectPersonalsFromMembers(RecipientMigrateCollectPersonalsEvent $event)
	{
		global $container;

		$migrate = new Migrate();
		$migrate->loadLanguageFile('orm_avisota_recipient');
		$migrate->loadDataContainer('orm_avisota_recipient');

		$migrationSettings = $event->getMigrationSettings();

		if ($migrationSettings['importFromMembers']) {
			/** @var \Doctrine\DBAL\Connection $connection */
			$connection = $container['doctrine.connection.default'];

			$recipient = $event->getRecipient();

			$queryBuilder = $connection->createQueryBuilder();
			/** @var PDOStatement $stmt */
			$stmt   = $queryBuilder
				->select('*')
				->from('tl_member', 'm')
				->where(
					$queryBuilder
						->expr()
						->eq('email', $recipient->getEmail())
				)
				->execute();
			$member = $stmt->fetch();

			$fields = $GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'];
			foreach ($fields as $fieldName => $fieldConfig) {
				if (isset($fieldConfig['eval']['migrateFrom'])) {
					$recipient->$fieldName = $member[$fieldConfig['eval']['migrateFrom']];
				}
			}
		}
	}
}
