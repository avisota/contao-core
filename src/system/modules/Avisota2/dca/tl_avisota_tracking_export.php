<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */



/**
 * Table tl_avisota_tracking_export
 */
$GLOBALS['TL_DCA']['tl_avisota_tracking_export'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Memory',
		'closed'                      => true,
		'onload_callback'           => array
		(
			array('tl_avisota_tracking_export', 'onload_callback'),
		),
		'onsubmit_callback'           => array
		(
			array('tl_avisota_tracking_export', 'onsubmit_callback'),
		)
	),

	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'format' => array(':hide', 'delimiter', 'enclosure')
		)
	),

	// Fields
	'fields' => array
	(
		'delimiter' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_tracking_export']['delimiter'],
			'inputType'               => 'select',
			'options'                 => array('comma', 'semicolon', 'tabulator', 'linebreak'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
		),
		'enclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_tracking_export']['enclosure'],
			'inputType'               => 'select',
			'options'                 => array('double', 'single'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_tracking_export'],
			'eval'                    => array('tl_class'=>'w50')
		)
	)
);

class tl_avisota_tracking_export extends Backend
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
	 * Load the data.
	 *
	 * @param DataContainer $dc
	 */
	public function onload_callback(DataContainer $dc)
	{
		$varData = $this->Session->get('AVISOTA_TRACKING_EXPORT');

		if ($varData && is_array($varData))
		{
			foreach ($varData as $k=>$v)
			{
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
		switch ($dc->getData('delimiter'))
		{
			case 'semicolon':
				$strDelimiter = ';';
				break;

			case 'tabulator':
				$strDelimiter = "\t";
				break;

			case 'linebreak':
				$strDelimiter = "\n";
				break;

			default:
				$strDelimiter = ',';
				break;
		}

		// Get enclosure
		switch ($dc->getData('enclosure'))
		{
			case 'single':
				$strEnclosure = '\'';
				break;

			default:
				$strEnclosure = '"';
				break;
		}

		$this->Session->set('AVISOTA_TRACKING_EXPORT', array(
			'delimiter' => $dc->getData('delimiter'),
			'enclosure' => $dc->getData('enclosure')
		));

		// build labels
		$arrLabels = array
		(
			$GLOBALS['TL_LANG']['tl_avisota_tracking_export']['newsletter'],
			$GLOBALS['TL_LANG']['tl_avisota_tracking_export']['date'],
			$GLOBALS['TL_LANG']['tl_avisota_tracking_export']['total'],
			$GLOBALS['TL_LANG']['tl_avisota_tracking_export']['reads'],
			$GLOBALS['TL_LANG']['tl_avisota_tracking_export']['readsPercent'],
			$GLOBALS['TL_LANG']['tl_avisota_tracking_export']['reacts'],
			$GLOBALS['TL_LANG']['tl_avisota_tracking_export']['reactsPercent']
		);

		// create temporary file
		$strFile = substr(tempnam(TL_ROOT . '/system/tmp', 'tracking_export_') . '.csv', strlen(TL_ROOT) + 1);

		// create new file object
		$objFile = new File($strFile);

		// open file handle
		$objFile->write('');

		// write the headline
		fputcsv($objFile->handle, $arrLabels, $strDelimiter, $strEnclosure);

		// write rows
		$objNewsletter = $this->Database
			->execute("SELECT * FROM tl_avisota_newsletter WHERE sendOn>0 ORDER BY sendOn DESC");
		while ($objNewsletter->next())
		{
			$objResultSet = $this->Database
				->prepare("SELECT COUNT(r.id) as sum
					FROM tl_avisota_newsletter_outbox_recipient r
					INNER JOIN tl_avisota_newsletter_outbox o
					WHERE o.pid=? AND r.send>0")
				->execute($objNewsletter->id);
			$intTotal = $objResultSet->sum;

			$objResultSet = $this->Database
				->prepare("SELECT COUNT(id) as sum
					FROM tl_avisota_statistic_raw_recipient
					WHERE pid=? AND readed=?")
				->execute($objNewsletter->id, 1);
			$intReads = $objResultSet->sum;

			$objResultSet = $this->Database
				->prepare("SELECT COUNT(id) as sum
					FROM (
						SELECT id
						FROM tl_avisota_statistic_raw_link_hit
						WHERE pid=?
						GROUP BY linkID,recipientLinkID
					) t")
				->execute($objNewsletter->id);
			$intReacts = $objResultSet->sum;

			$intReadsPercent = $intTotal > 0 ? round($intReads / $intTotal * 100) : 0;
			$intReactsPercent = $intReads > 0 ? round($intReacts / $intReads * 100) : 0;

			$arrRow = array
			(
				$objNewsletter->subject,
				$this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objNewsletter->sendOn),
				$intTotal,
				$intReads,
				$intReadsPercent . ' %',
				$intReacts,
				$intReactsPercent . ' %'
			);

			fputcsv($objFile->handle, $arrRow, $strDelimiter, $strEnclosure);
		}

		// close file handle
		$objFile->close();

		// create temporary zip file
		$strZip = $strFile . '.zip';

		// create a zip writer
		$objZip = new ZipWriter($strZip);

		// add the temporary csv
		$objZip->addFile($strFile, 'Newsletter Statistics - ' . $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], time()) . '.csv');

		// close the zip
		$objZip->close();

		// create new file object
		$objZip = new File($strZip);

		// Open the "save as â€¦" dialogue
        header('Content-Type: ' . $objZip->mime);
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; filename="Newsletter Statistics - ' . $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], time()) . '.zip"');
        header('Content-Length: ' . $objZip->filesize);
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');

 		// send the zip file
        $resFile = fopen(TL_ROOT . '/' . $strZip, 'rb');
        fpassthru($resFile);
        fclose($resFile);

		// delete temporary files
		$objFile->delete();
		$objZip->delete();

		exit;
	}
}
