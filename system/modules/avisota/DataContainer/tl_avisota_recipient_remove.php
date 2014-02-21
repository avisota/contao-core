<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


class orm_avisota_recipient_remove extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Do the import.
	 *
	 * @param DataContainer $dc
	 */
	public function onsubmit_callback(DataContainer $dc)
	{
		$source = $dc->getData('source');
		$upload = $dc->getData('upload');
		$emails = $dc->getData('emails');

		$totalCount = 0;

		if (is_array($source)) {
			foreach ($source as $csvFile) {
				$file = new File($csvFile);
				$this->removeRecipients($file->getContent(), $totalCount);
			}
		}

		if ($upload) {
			$this->removeRecipients(file_get_contents($upload['tmp_name']), $totalCount);
		}

		if ($emails) {
			$this->removeRecipients($emails, $totalCount);
		}

		$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['orm_avisota_recipient_remove']['confirm'], $totalCount);

		setcookie('BE_PAGE_OFFSET', 0, 0, '/');
		$this->reload();
	}


	/**
	 * Remove recipients.
	 *
	 * @param string $emailList
	 * @param int    $totalCount
	 */
	protected function removeRecipients($emailList, &$totalCount)
	{
		$emails = preg_split('#[\s,;]#u', $emailList, -1, PREG_SPLIT_NO_EMPTY);
		$emails = array_filter(array_unique($emails));

		$params       = array($this->Input->get('id'));
		$placeHolders = array();
		foreach ($emails as $email) {
			if (preg_match('#^".*"$#', $email) || preg_match("#^'.*'$#", $email)) {
				$email = substr($email, 1, -1);
			}

			// Skip invalid entries
			if (!$this->isValidEmailAddress($email)) {
				continue;
			}

			$params[]       = $email;
			$placeHolders[] = '?';
		}

		if (count($params)) {
			// Check whether the e-mail address exists
			$resultSet = \Database::getInstance()
				->prepare(
				"DELETE FROM orm_avisota_recipient WHERE pid=? AND email IN (" . implode(',', $placeHolders) . ")"
			)
				->execute($params);

			$totalCount += $resultSet->affectedRows;
		}
	}
}
