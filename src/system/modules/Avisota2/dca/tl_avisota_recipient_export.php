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
 * Table tl_avisota_recipient_export
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_export'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onload_callback'   => array
		(
			array('tl_avisota_recipient_export', 'onload_callback'),
		),
		'onsubmit_callback' => array
		(
			array('tl_avisota_recipient_export', 'onsubmit_callback'),
		)
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'format' => array(':hide', 'delimiter', 'enclosure', 'fields')
		)
	),
	// Fields
	'fields'       => array
	(
		'delimiter' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['delimiter'],
			'inputType' => 'select',
			'options'   => array('comma', 'semicolon', 'tabulator', 'linebreak'),
			'reference' => &$GLOBALS['TL_LANG']['MSC'],
			'eval'      => array('mandatory' => true, 'tl_class' => 'w50')
		),
		'enclosure' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['enclosure'],
			'inputType' => 'select',
			'options'   => array('double', 'single'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_recipient_export'],
			'eval'      => array('tl_class' => 'w50')
		),
		'fields'    => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['fields'],
			'inputType'        => 'checkboxWizard',
			'options_callback' => array('tl_avisota_recipient_export', 'getFields'),
			'eval'             => array('multiple' => true, 'tl_class' => 'clr')
		)
	)
);

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
