<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_avisota_recipient_import
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_import'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onload_callback'   => array
		(
			array('tl_avisota_recipient_import', 'onload_callback'),
		),
		'onsubmit_callback' => array
		(
			array('tl_avisota_recipient_import', 'onsubmit_callback'),
		)
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'import' => array('source', 'upload'),
			'format' => array(':hide', 'delimiter', 'enclosure', 'columns', 'overwrite', 'force')
		)
	),
	// Fields
	'fields'       => array
	(
		'source'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['source'],
			'inputType' => 'fileTree',
			'eval'      => array('fieldType' => 'checkbox', 'files' => true, 'filesOnly' => true, 'extensions' => 'csv')
		),
		'upload'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['upload'],
			'inputType' => 'upload'
		),
		'delimiter' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['delimiter'],
			'inputType' => 'select',
			'options'   => array('comma', 'semicolon', 'tabulator', 'linebreak'),
			'reference' => &$GLOBALS['TL_LANG']['MSC'],
			'eval'      => array('mandatory' => true, 'tl_class' => 'w50')
		),
		'enclosure' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['enclosure'],
			'inputType' => 'select',
			'options'   => array('double', 'single'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import'],
			'eval'      => array('tl_class' => 'w50')
		),
		'columns'   => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['columns'],
			'inputType' => 'multiSelectWizard',
			'eval'      => array(
				'columnsCallback' => array('tl_avisota_recipient_import', 'createFieldSelectorArray'),
				'storeCallback'   => array('tl_avisota_recipient_import', 'storeFieldSelectorArray'),
				'tl_class'        => 'clr'
			)
		),
		'overwrite' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['overwrite'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'w50 m12')
		),
		'force'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['force'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'w50 m12')
		)
	)
);

class tl_avisota_recipient_import extends Backend
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
	 * Create the columns definition array.
	 *
	 * @return array
	 */
	public function createFieldSelectorArray()
	{
		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');

		$arr = array
		(
			array
			(
				'key'    => 'colnum',
				'label'  => $GLOBALS['TL_LANG']['tl_avisota_recipient_import']['colnum'],
				'source' => array(
					0  => 1,
					1  => 2,
					2  => 3,
					3  => 4,
					4  => 5,
					5  => 6,
					6  => 7,
					7  => 8,
					8  => 9,
					9  => 10,
					10 => 11,
					11 => 12,
					12 => 13,
					13 => 14,
					14 => 15,
					15 => 16,
					16 => 17,
					17 => 18,
					18 => 19,
					19 => 20
				),
				'style'  => 'width:100px'
			),
			array
			(
				'key'    => 'field',
				'label'  => $GLOBALS['TL_LANG']['tl_avisota_recipient_import']['field'],
				'source' => array()
			)
		);

		foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $k => $v) {
			if (isset($v['eval']) && isset($v['eval']['importable']) && $v['eval']['importable']) {
				$arr[1]['source'][$k] = $v['label'][0];
			}
		}

		return $arr;
	}


	/**
	 * Validate columns definition
	 */
	public function validateFieldSelectorArray($value)
	{
		$columnNumbers     = array();
		$fields      = array();
		$emailExists = false;
		$doubles     = false;

		foreach ($value as $row) {
			$columnNumber = $row['values']['colnum'];
			$field  = $row['values']['field'];

			if (in_array($columnNumber, $columnNumbers)) {
				$doubles = true;
			}
			else {
				$columnNumbers[] = $columnNumber;
			}

			if (in_array($field, $fields)) {
				$doubles = true;
			}
			else {
				$fields[] = $field;
			}

			if ($field == 'email') {
				$emailExists = true;
			}
		}

		if ($doubles) {
			$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['tl_avisota_recipient_import']['doubles'];
		}

		if (!$emailExists) {
			$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['tl_avisota_recipient_import']['emailMissing'];
		}

		return !$doubles && $emailExists;
	}


	/**
	 * Store the columns definition array.
	 *
	 * @param Widget $widget
	 *
	 * @return void
	 */
	public function storeFieldSelectorArray(MultiSelectWizard $widget)
	{
		// just do nothink
		// prevent update the non-existing table
	}


	/**
	 * Load the data.
	 *
	 * @param DataContainer $dc
	 */
	public function onload_callback(DataContainer $dc)
	{
		$sessionData = $this->Session->get('AVISOTA_IMPORT');

		if ($sessionData && is_array($sessionData)) {
			foreach ($sessionData as $k => $v) {
				$dc->setData($k, $v);
			}
		}
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

		// Get delimiter
		switch ($dc->getData('delimiter')) {
			case 'semicolon':
				$delimiter = ';';
				break;

			case 'tabulator':
				$delimiter = "\t";
				break;

			case 'linebreak':
				$delimiter = "\n";
				break;

			default:
				$delimiter = ',';
				break;
		}

		// Get enclosure
		switch ($dc->getData('enclosure')) {
			case 'single':
				$enclosure = '\'';
				break;

			default:
				$enclosure = '"';
				break;
		}

		// Get columns
		$rawColumns = $dc->getData('columns');

		$overwrite = $dc->getData('overwrite') ? true : false;
		$force     = $dc->getData('force') ? true : false;

		$this->Session->set(
			'AVISOTA_IMPORT',
			array(
				'delimiter' => $dc->getData('delimiter'),
				'enclosure' => $dc->getData('enclosure'),
				'columns'   => $dc->getData('columns')
			)
		);

		if ($this->validateFieldSelectorArray($rawColumns)) {
			$columns = array();
			foreach ($rawColumns as $row) {
				$columns[$row['values']['colnum']] = $row['values']['field'];
			}
			$startTime         = time();
			$totalCount     = 0;
			$overwriteCount = 0;
			$skipCount   = 0;
			$invalidCount   = 0;

			if (is_array($source)) {
				foreach ($source as $csvFile) {
					$file = new File($csvFile);

					if ($file->extension != 'csv') {
						$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $file->extension);
						continue;
					}

					$this->importRecipients(
						$file->handle,
						$delimiter,
						$enclosure,
						$columns,
						$overwrite,
						$force,
						$startTime,
						$totalCount,
						$overwriteCount,
						$skipCount,
						$invalidCount
					);
					$file->close();
				}
			}

			if ($upload) {
				$resFile = fopen($upload['tmp_name'], 'r');
				$this->importRecipients(
					$resFile,
					$delimiter,
					$enclosure,
					$columns,
					$overwrite,
					$force,
					$startTime,
					$totalCount,
					$overwriteCount,
					$skipCount,
					$invalidCount
				);
				fclose($resFile);
			}

			if ($totalCount > 0) {
				$_SESSION['TL_CONFIRM'][] = sprintf(
					$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['confirmed'],
					$totalCount
				);
			}

			if ($overwriteCount > 0) {
				$_SESSION['TL_CONFIRM'][] = sprintf(
					$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['overwritten'],
					$overwriteCount
				);
			}

			if ($skipCount > 0) {
				$_SESSION['TL_CONFIRM'][] = sprintf(
					$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['skipped'],
					$skipCount
				);
			}

			if ($invalidCount > 0) {
				$_SESSION['TL_INFO'][] = sprintf(
					$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['invalid'],
					$invalidCount
				);
			}

		}

		setcookie('BE_PAGE_OFFSET', 0, 0, '/');
		$this->reload();
	}


	/**
	 * Read a csv file and import the data.
	 *
	 * @param handle $resFile
	 * @param string $delimiter
	 * @param string $enclosure
	 * @param int    $time
	 * @param int    $totalCount
	 * @param int    $invalidCount
	 */
	protected function importRecipients(
		$resFile,
		$delimiter,
		$enclosure,
		$columns,
		$overwrite,
		$force,
		$time,
		&$totalCount,
		&$overwriteCount,
		&$skipCount,
		&$invalidCount
	) {
		$recipients = array();
		$emails      = array();
		$n             = 0;

		while (($row = @fgetcsv($resFile, null, $delimiter, $enclosure)) !== false) {
			$recipientData = array();
			foreach ($columns as $columnNumber => $field) {
				$recipientData[$field] = $row[$columnNumber];
			}

			// Skip invalid entries
			if (!$this->isValidEmailAddress($recipientData['email'])) {
				$this->log(
					'Recipient address "' . $recipientData['email'] . '" seems to be invalid and has been skipped',
					'Avisota importRecipients()',
					TL_ERROR
				);

				++$invalidCount;
			}
			else {
				$recipients[$n] = $recipientData;
				$emails[$n]      = $recipientData['email'];
				$n++;
			}
			unset($recipientData, $row);
		}

		$blacklist = array();
		if ($force) {
			$this->Database
				->prepare(
				"DELETE FROM tl_avisota_recipient_blacklist WHERE pid=? AND email IN (MD5('" . implode(
					"'),MD5('",
					array_map('md5', $emails)
				) . "'))"
			)
				->execute($this->Input->get('id'));
		}
		else {
			$blacklistResultSet = $this->Database
				->prepare(
				"SELECT * FROM tl_avisota_recipient_blacklist WHERE pid=? AND email IN (MD5('" . implode(
					"'),MD5('",
					$emails
				) . "'))"
			)
				->execute($this->Input->get('id'));
			while ($blacklistResultSet->next()) {
				$blacklist[$blacklistResultSet->email] = $blacklistResultSet->id;
			}
		}

		// Check whether the e-mail address exists
		$existingRecipients = array();
		$existingRecipient = $this->Database
			->prepare(
			"SELECT id,email FROM tl_avisota_recipient WHERE pid=? AND email IN ('" . implode("','", $emails) . "')"
		)
			->execute($this->Input->get('id'));
		while ($existingRecipient->next()) {
			$existingRecipients[$existingRecipient->email] = $existingRecipient->id;
		}

		foreach ($recipients as $recipientData) {
			// check blacklist
			if (!$force && isset($blacklist[$recipientData['email']])) {
				++$skipCount;
				continue;
			}

			$recipientData['tstamp'] = $time;

			if (!isset($existingRecipients[$recipientData['email']])) {
				$recipientData['pid']       = $this->Input->get('id');
				$recipientData['addedOn']   = $time;
				$recipientData['addedBy']   = $this->User->id;
				$recipientData['confirmed'] = 1;
				$this->Database
					->prepare("INSERT INTO tl_avisota_recipient %s")
					->set($recipientData)
					->execute();

				++$totalCount;
			}
			else if ($overwrite) {
				$this->Database
					->prepare("UPDATE tl_avisota_recipient %s WHERE pid=? AND email=?")
					->set($recipientData)
					->execute($this->Input->get('id'), $recipientData['email']);

				++$overwriteCount;
			}
			else {
				++$skipCount;
			}
		}
	}
}
