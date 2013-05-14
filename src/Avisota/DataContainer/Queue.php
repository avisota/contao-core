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
 * @license    LGPL
 * @filesource
 */

namespace Avisota\DataContainer;

class Queue extends \Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param \DataContainer $dc
	 */
	public function onload_callback($dc)
	{
	}

	/**
	 * @param \DataContainer $dc
	 */
	public function onsubmit_callback($dc)
	{
		$database = \Database::getInstance();

		$openTableName = 'avisota_newsletter_outbox_' . $dc->activeRecord->alias . '_open';
		$sendTableName = 'avisota_newsletter_outbox_' . $dc->activeRecord->alias . '_send';

		if (isset($_SESSION['AVISOTA_QUEUE_ALIAS'][$dc->id])) {
			if ($_SESSION['AVISOTA_QUEUE_ALIAS'][$dc->id] !== $dc->activeRecord->alias) {
				$previousOpenTableName = 'avisota_newsletter_outbox_' . $_SESSION['AVISOTA_QUEUE_ALIAS'][$dc->id] . '_open';
				$previousSendTableName = 'avisota_newsletter_outbox_' . $_SESSION['AVISOTA_QUEUE_ALIAS'][$dc->id] . '_send';

				if ($database->tableExists($previousOpenTableName) && !$database->tableExists($openTableName)) {
					$database->query(
						sprintf(
							'RENAME TABLE `%s` TO `%s`',
							$previousOpenTableName,
							$openTableName
						)
					);
				}
				if ($database->tableExists($previousSendTableName) && !$database->tableExists($sendTableName)) {
					$database->query(
						sprintf(
							'RENAME TABLE `%s` TO `%s`',
							$previousSendTableName,
							$sendTableName
						)
					);
				}
			}
		}

		if (!$database->tableExists($openTableName, null, true)) {
			$sql = file_get_contents(AVISOTA_ROOT . '/config/outbox_open.sql');
			$sql = str_replace('{{alias}}', $dc->activeRecord->alias, $sql);
			$database->query($sql);
		}

		if (!$database->tableExists($sendTableName, null, true)) {
			$sql = file_get_contents(AVISOTA_ROOT . '/config/outbox_send.sql');
			$sql = str_replace('{{alias}}', $dc->activeRecord->alias, $sql);
			$database->query($sql);
		}
	}

	public function rememberAlias($alias, $dc)
	{
		$_SESSION['AVISOTA_QUEUE_ALIAS'][$dc->id] = $alias;
		return $alias;
	}


	/**
	 * Check permissions to edit table tl_avisota_transport
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// TODO
	}

	/**
	 * Autogenerate a news alias if it has not been set yet
	 *
	 * @param mixed $value
	 * @param \DataContainer $dc
	 *
	 * @return string
	 */
	public function generateAlias($value, $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($value)) {
			$autoAlias = true;
			$value     = standardize($dc->activeRecord->title);
		}

		$aliasResultSet = $this->Database
			->prepare("SELECT id FROM tl_avisota_queue WHERE alias=?")
			->execute($value);

		// Check whether the news alias exists
		if ($aliasResultSet->numRows > 1 && !$autoAlias) {
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $value));
		}

		// Add ID to alias
		if ($aliasResultSet->numRows && $autoAlias) {
			$value .= '-' . $dc->id;
		}

		return $value;
	}
}
