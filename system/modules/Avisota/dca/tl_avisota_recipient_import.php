<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  InfinitySoft 2010,2011
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
	'config' => array
	(
		'dataContainer'               => 'Memory',
		'closed'                      => true,
		'onload_callback'           => array
		(
			array('tl_avisota_recipient_import', 'onload_callback'),
		),
		'onsubmit_callback'           => array
		(
			array('tl_avisota_recipient_import', 'onsubmit_callback'),
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{import_legend},source,upload;{format_legend:hide},delimiter,enclosure,force'
	),
	
	// Fields
	'fields' => array
	(
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['source'],
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'extensions'=>'csv')
		),
		'upload' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['upload'],
			'inputType'               => 'upload'
		),
		'personals' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['personals'],
			'inputType'               => 'checkbox'
		),
		'delimiter' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['delimiter'],
			'inputType'               => 'select',
			'options'                 => array('comma', 'semicolon', 'tabulator', 'linebreak'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
		),
		'enclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['enclosure'],
			'inputType'               => 'select',
			'options'                 => array('double', 'single'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import'],
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
		),
		'force' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['force'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
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
	 * Load the data.
	 * 
	 * @param DataContainer $dc
	 */
	public function onload_callback(DataContainer $dc)
	{
		$varData = $this->Session->get('AVISOTA_IMPORT');
		
		if ($varData && is_array($varData))
		{
			foreach ($varData as $k=>$v)
			{
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
		$arrSource = $dc->getData('source');
		$arrUpload = $dc->getData('upload');
		
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
		
		$blnForce = $dc->getData('force') ? true : false;
		
		$this->Session->set('AVISOTA_IMPORT', array(
			'delimiter' => $dc->getData('delimiter'),
			'enclosure' => $dc->getData('enclosure'),
			'columns'   => $dc->getData('columns')
		));
	
		$time = time();
		$intTotal = 0;
		$intInvalid = 0;
		
		if (is_array($arrSource))
		{
			foreach ($arrSource as $strCsvFile)
			{
				$objFile = new File($strCsvFile);
				
				if ($objFile->extension != 'csv')
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension);
					continue;
				}
				
				$this->importRecipients($objFile->handle, $strDelimiter, $strEnclosure, $blnForce, $time, $intTotal, $intInvalid);
				$objFile->close();
			}
		}
		
		if ($arrUpload)
		{
			$resFile = fopen($arrUpload['tmp_name'], 'r');
			$this->importRecipients($resFile, $strDelimiter, $strEnclosure, $blnForce, $time, $intTotal, $intInvalid);
			fclose($resFile);
		}

		$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient_import']['confirm'], $intTotal);
	
		if ($intInvalid > 0)
		{
			$_SESSION['TL_INFO'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient_import']['invalid'], $intInvalid);
		}
	
		setcookie('BE_PAGE_OFFSET', 0, 0, '/');
		$this->reload();
	}
	
	
	/**
	 * Read a csv file and import the data.
	 * 
	 * @param handle $resFile
	 * @param string $strDelimiter
	 * @param string $strEnclosure
	 * @param int $time
	 * @param int $intTotal
	 * @param int $intInvalid
	 */
	protected function importRecipients($resFile, $strDelimiter, $strEnclosure, $blnForce, $time, &$intTotal, &$intInvalid)
	{
		$arrRecipients = array();

		while(($arrRow = @fgetcsv($resFile, null, $strDelimiter, $strEnclosure)) !== false)
		{
			$arrRecipients = array_merge($arrRecipients, $arrRow);
		}

		$arrRecipients = array_filter(array_unique($arrRecipients));

		foreach ($arrRecipients as $strRecipient)
		{
			// Skip invalid entries
			if (!$this->isValidEmailAddress($strRecipient))
			{
				$this->log('Recipient address "' . $strRecipient . '" seems to be invalid and has been skipped', 'Avisota importRecipients()', TL_ERROR);

				++$intInvalid;
				continue;
			}

			$objBlacklist = $this->Database->prepare("SELECT * FROM tl_avisota_recipient_blacklist WHERE pid=? AND email=?")
				->execute($this->Input->get('id'), md5($strRecipient));
			
			// check blacklist
			if (!$blnForce && $objBlacklist->numRows > 0)
			{
				++$intSkipped;
				continue;
			}
			else if ($blnForce && $objBlacklist->numRows > 0)
			{
				$this->Database->prepare("DELETE FROM tl_avisota_recipient_blacklist WHERE pid=? AND email=?")
					->execute($this->Input->get('id'), md5($strRecipient));
			}
			
			// Check whether the e-mail address exists
			$objRecipient = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_avisota_recipient WHERE pid=? AND email=?")
										   ->execute($this->Input->get('id'), $strRecipient);

			if ($objRecipient->total < 1)
			{
				$this->Database->prepare("INSERT INTO tl_avisota_recipient SET pid=?, tstamp=$time, email=?, confirmed=1")
							   ->execute($this->Input->get('id'), $strRecipient);

				++$intTotal;
			}
		}
	}
}

?>