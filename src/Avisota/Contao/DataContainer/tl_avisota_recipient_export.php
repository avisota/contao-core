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


class tl_avisota_recipient_export extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');

		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');
	}


	/**
	 * Get the fields list.
	 */
	public function getFields()
	{
		$options = array();
		foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $field => $data) {
			if (isset($data['eval']) && isset($data['eval']['exportable']) && $data['eval']['exportable']) {
				$options[$field] = empty($data['label'][0]) ? $field
					: $data['label'][0] . ' [' . $field . ']';
			}
		}

		return $options;
	}


	/**
	 * Load the data.
	 *
	 * @param DataContainer $dc
	 */
	public function onload_callback(DataContainer $dc)
	{
		$sessionData = $this->Session->get('AVISOTA_EXPORT');

		if ($sessionData && is_array($sessionData)) {
			foreach ($sessionData as $k => $v) {
				$dc->setData($k, $v);
			}
		}
	}


	/**
	 * Do the export.
	 *
	 * @param DataContainer $dc
	 */
	public function onsubmit_callback(DataContainer $dc)
	{
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

		// Get fields
		$fields = $dc->getData('fields');

		// Get field labels
		$labels = array();
		foreach ($fields as $field) {
			switch ($field) {
				default:
					$fieldConfig = $GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$field];
					if (empty($fieldConfig['label'][0])) {
						$labels[] = $field;
					}
					else {
						$labels[] = $fieldConfig['label'][0] . ' [' . $field . ']';
					}
					break;
			}
		}

		$this->Session->set(
			'AVISOTA_EXPORT',
			array(
				'delimiter' => $dc->getData('delimiter'),
				'enclosure' => $dc->getData('enclosure'),
				'fields'    => $dc->getData('fields')
			)
		);

		// search for the list
		$list = $this->Database
			->prepare("SELECT * FROM tl_avisota_mailing_list WHERE id=?")
			->execute($this->Input->get('id'));

		if (!$list->next()) {
			$this->log(
				'The recipient list ID ' . $this->Input->get('id') . ' does not exists!',
				'tl_avisota_recipient_export',
				TL_ERROR
			);
			$this->redirect('contao/main.php?act=error');
		}

		// create temporary file
		$temporaryPathname = substr(tempnam(TL_ROOT . '/system/tmp', 'recipients_export_') . '.csv', strlen(TL_ROOT) + 1);

		// create new file object
		$temporaryFile = new File($temporaryPathname);

		// open file handle
		$temporaryFile->write('');

		// write the headline
		fputcsv($temporaryFile->handle, $labels, $delimiter, $enclosure);

		// write recipient rows
		$recipient = $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient WHERE pid=?")
			->execute($this->Input->get('id'));
		while ($recipient->next()) {
			$row = array();
			foreach ($fields as $field) {
				switch ($field) {
					default:
						$row[] = $recipient->$field;
				}
			}

			fputcsv($temporaryFile->handle, $row, $delimiter, $enclosure);
		}

		// close file handle
		$temporaryFile->close();

		// create temporary zip file
		$zipFile = $temporaryPathname . '.zip';

		// create a zip writer
		$zip = new ZipWriter($zipFile);

		// add the temporary csv
		$zip->addFile($temporaryPathname, $list->title . '.csv');

		// close the zip
		$zip->close();

		// create new file object
		$zip = new File($zipFile);

		// Open the "save as â€¦" dialogue
		header('Content-Type: ' . $zip->mime);
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="' . $list->title . '.zip"');
		header('Content-Length: ' . $zip->filesize);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Expires: 0');

		// send the zip file
		$resFile = fopen(TL_ROOT . '/' . $zipFile, 'rb');
		fpassthru($resFile);
		fclose($resFile);

		// delete temporary files
		$temporaryFile->delete();
		$zip->delete();

		exit;
	}
}
