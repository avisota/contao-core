<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


class orm_avisota_recipient_migrate extends Backend
{
	/**
	 * migrate the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Do the migration.
	 *
	 * @param DataContainer $dc
	 */
	public function onsubmit_callback(DataContainer $dc)
	{
		$sourceIds    = array_filter(array_map('intval', $dc->getData('source')));
		$usePersonals = $dc->getData('personals') ? true : false;
		$force     = $dc->getData('force') ? true : false;

		if (count($sourceIds)) {
			$source = implode(',', $sourceIds);

			$insertPersonals = '';
			$selectPersonals = '';
			if ($usePersonals) {
				$this->loadDataContainer('orm_avisota_recipient');
				$this->loadDataContainer('tl_member');
				foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $fieldName => $fieldConfig) {
					if ( // do not add default fields
						!in_array(
							$fieldName,
							array('pid', 'tstamp', 'email', 'confirmed', 'addedOn', 'addedBy', 'token')
						)
						// only add importable fields
						&& isset($fieldConfig['eval']['importable'])
						&& $fieldConfig['eval']['importable']
						// only add fields, that are exists in tl_member table
						&& isset($GLOBALS['TL_DCA']['tl_member']['fields'][$fieldName])
					) {
						$insertPersonals .= ',' . $fieldName;
						$selectPersonals .= ',IFNULL(m.' . $fieldName . ', "")';
					}
				}
			}

			$stmt = \Database::getInstance()
				->prepare(
				"INSERT INTO
						orm_avisota_recipient (pid,tstamp,email" . $insertPersonals . ",confirmed,addedOn,addedBy,token)
					SELECT
						?,r.tstamp,r.email" . $selectPersonals . ",r.active,?,?,r.token
					FROM
						tl_newsletter_recipients r
					" . ($usePersonals ? "
					LEFT JOIN
						tl_member m
					ON
						r.email = m.email
					" : "") . "
					WHERE
						r.pid IN ($source)
					AND
						r.email NOT IN (SELECT email FROM orm_avisota_recipient WHERE pid=?)
					" . ($force
					? ""
					: "
					AND
						MD5(r.email) NOT IN (SELECT email FROM orm_avisota_recipient_blacklist WHERE pid=?)
					")
			)
				->execute(
				$this->Input->get('id'),
				time(),
				$this->User->id,
				$this->Input->get('id'),
				$this->Input->get('id')
			);

			$_SESSION['TL_CONFIRM'][] = sprintf(
				$GLOBALS['TL_LANG']['orm_avisota_recipient_migrate']['migrated'],
				$stmt->affectedRows
			);

			if ($force) {
				\Database::getInstance()
					->prepare(
					"DELETE FROM
							orm_avisota_recipient_blacklist
						WHERE
							pid=?
						AND
							email IN (SELECT MD5(email) FROM orm_avisota_recipient WHERE pid=?)"
				)
					->execute($this->Input->get('id'), $this->Input->get('id'));
			}
		}

		setcookie('BE_PAGE_OFFSET', 0, 0, '/');
		$this->reload();
	}
}
