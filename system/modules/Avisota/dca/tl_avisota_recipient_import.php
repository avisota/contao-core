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
		'onload_callback'             => array
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
		'default'                     => '{import_legend},source,upload;{format_legend},delimiter,enclosure,columns,overwrite,force;{added_legend},notice'
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
			'eval'                    => array('tl_class'=>'w50')
		),
		'columns' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['columns'],
			'inputType'               => 'multiColumnWizard',

			// 'save_callback'			  => array('tl_avisota_recipient_import', 'storeFieldSelectorArray'),
			'eval'                    => array
			(
				'columnFields' => array
				(
					'colnum' => array
					(
						'label' 			=> array($GLOBALS['TL_LANG']['tl_avisota_recipient_import']['colnum']),
						'inputType' 		=> 'select',
						'options' 			=> array('1','2','3','4','5','6','7','8','8','9','10','11','12','13','14','15','16','17'),
						'eval'				=> array('style' => 'width:100px', 'chosen'=>'true')
					),
					'field' => array
					(
						'label' 			=> array($GLOBALS['TL_LANG']['tl_avisota_recipient_import']['field']),
						'inputType'			=> 'select',
						'options_callback'	=> array('tl_avisota_recipient_import', 'getImportableFields'),
						'eval'				=> array('chosen'=>'true')
					)
				)
			)
		),
		'overwrite' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['overwrite'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'force' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['force'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'notice' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['notice'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class' => 'clr long', 'maxlength' => 255)
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
	 * options_callback to find importable fields
	 * 
	 * @return array
	 */
	public function getImportableFields()
	{
		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');
		

		$arr = array();
		foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $k=>$v)
		{
			if (isset($v['eval']) && isset($v['eval']['importable']) && $v['eval']['importable'])
			{
				$arr[$k] = $v['label'][0];
			}
		}
		
		return $arr;
	}
	
	
	/**
	 * Validate columns definition
	 */
	public function validateFieldSelectorArray($varValue)
	{
		$arrColnums = array();
		$arrFields = array();
		$blnEmailExists = false;
		$blnDoubles = false;
		
		foreach ($varValue as $row)
		{
			$intColnum = $row['colnum'];
			$strField = $row['field'];
			
			if (in_array($intColnum, $arrColnums))
			{
				$blnDoubles = true;
			}
			else
			{
				$arrColnums[] = $intColnum;
			}
			
			if (in_array($strField, $arrFields))
			{
				$blnDoubles = true;
			}
			else
			{
				$arrFields[] = $strField;
			}
			
			if ($strField == 'email')
			{
				$blnEmailExists = true;
			}
		}
		
		if ($blnDoubles)
		{
			$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['tl_avisota_recipient_import']['doubles'];
		}
		
		if (!$blnEmailExists)
		{
			$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['tl_avisota_recipient_import']['emailMissing'];
		}
		
		return !$blnDoubles && $blnEmailExists;
	}
	
	
	/**
	 * Store the columns definition array.
	 * 
	 * @param Widget $objWidget
	 * @return void
	 */
	public function storeFieldSelectorArray()
	{
		// just do nothink
		// prevent update the non-existing table
		return '';
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
		
		// Get columns
		$arrColumnsRaw = $this->Input->post('columns');


		$blnOverwrite = $dc->getData('overwrite') ? true : false;
		$blnForce = $dc->getData('force') ? true : false;
		$strNotice = $dc->getData('notice');
		
		$this->Session->set('AVISOTA_IMPORT', array(
			'delimiter' => $dc->getData('delimiter'),
			'enclosure' => $dc->getData('enclosure'),
			'columns'   => $dc->getData('columns')
		));
	
		if ($this->validateFieldSelectorArray($arrColumnsRaw))
		{
			$arrColumns = array();
			foreach ($arrColumnsRaw as $arrRow)
			{
				$arrColumns[$arrRow['colnum']-1] = $arrRow['field'];
			}
			$time = time();
			$intTotal = 0;
			$intOverwrite = 0;
			$intSkipped = 0;
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
					
					$this->importRecipients($objFile->handle, $strDelimiter, $strEnclosure, $arrColumns, $blnOverwrite, $blnForce, $strNotice, $time, $intTotal, $intOverwrite, $intSkipped, $intInvalid);
					$objFile->close();
				}
			}
			
			if ($arrUpload)
			{
				$resFile = fopen($arrUpload['tmp_name'], 'r');
				$this->importRecipients($resFile, $strDelimiter, $strEnclosure, $arrColumns, $blnOverwrite, $blnForce, $strNotice, $time, $intTotal, $intOverwrite, $intSkipped, $intInvalid);
				fclose($resFile);
			}
	
			if ($intTotal > 0)
			{
				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient_import']['confirmed'], $intTotal);
			}
			
			if ($intOverwrite > 0)
			{
				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient_import']['overwritten'], $intOverwrite);
			}
			
			if ($intSkipped > 0)
			{
				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient_import']['skipped'], $intSkipped);
			}
			
			if ($intInvalid > 0)
			{
				$_SESSION['TL_INFO'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient_import']['invalid'], $intInvalid);
			}
		
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
	protected function importRecipients($resFile, $strDelimiter, $strEnclosure, $arrColumns, $blnOverwrite, $blnForce, $strNotice, $time, &$intTotal, &$intOverwrite, &$intSkipped, &$intInvalid)
	{
		$arrRecipients = array();
		$arrEmail = array();
		$n = 0;

		while(($arrRow = @fgetcsv($resFile, NULL, $strDelimiter, $strEnclosure)) !== false)
		{
			$arrRecipient = array();
			foreach ($arrColumns as $intColnum=>$strField)
			{
				$arrRecipient[$strField] = trim($arrRow[$intColnum]);
			}
			
			// Skip invalid entries
			if (!$this->isValidEmailAddress($arrRecipient['email']))
			{
				$this->log('Recipient address "' . $arrRecipient['email'] . '" seems to be invalid and has been skipped', 'Avisota importRecipients()', TL_ERROR);

				++$intInvalid;
			}
			else
			{
				$arrRecipient['email'] = strtolower($arrRecipient['email']);

				$arrRecipients[$n] = $arrRecipient;
				$arrEmail[$n] = $arrRecipient['email'];
				$n ++;
			}
			unset($arrRecipient, $arrRow);
		}
		
		$arrBlacklist = array();
		if ($blnForce) {
			$this->Database
				->prepare("DELETE FROM tl_avisota_recipient_blacklist WHERE pid=? AND email IN ('" . implode("','", array_map('md5', $arrEmail)) . "')")
				->execute($this->Input->get('id'));
		} else {
			$objBlacklist = $this->Database
				->prepare("SELECT * FROM tl_avisota_recipient_blacklist WHERE pid=? AND email IN (MD5('" . implode("'),MD5('", $arrEmail) . "'))")
				->execute($this->Input->get('id'));
			while ($objBlacklist->next())
			{
				$arrBlacklist[$objBlacklist->email] = $objBlacklist->id;
			}
		}

		// Check whether the e-mail address exists
		$arrExistingRecipients = array();
		$objExistingRecipients = $this->Database
			->prepare("SELECT id,email FROM tl_avisota_recipient WHERE pid=? AND email IN ('" . implode("','", $arrEmail) . "')")
			->execute($this->Input->get('id'));
		while ($objExistingRecipients->next())
		{
			$arrExistingRecipients[$objExistingRecipients->email] = $objExistingRecipients->id;
		}
		
		foreach ($arrRecipients as $arrRecipient)
		{
			// check blacklist
			if (!$blnForce && isset($arrBlacklist[md5($arrRecipient['email'])]))
			{
				++$intSkipped;
				continue;
			}

			if ($strNotice) {
				$arrRecipient['addedNotice'] = $strNotice;
			}
			$arrRecipient['tstamp']    = $time;

			$objList = $this->Database
				->prepare('SELECT * FROM tl_avisota_recipient_list WHERE id=?')
				->execute($this->Input->get('id'));
			if (!$objList->next()) {
				$this->log('Recipient list ID ' . $this->Input->get('id') . ' not found!', 'tl_avisota_recipient_import', TL_ERROR);
				$this->redirect('contao/main.php?act=error');
			}
			
			if (!isset($arrExistingRecipients[$arrRecipient['email']]))
			{
				if (!empty($arrRecipient['addedOn'])) {
					$addedOn = strtotime($arrRecipient['addedOn']);
				} else {
					$addedOn = $time;
				}

				$arrRecipient['pid']       = $this->Input->get('id');
				$arrRecipient['addedOn']   = $addedOn;
				$arrRecipient['addedBy']   = $this->User->id;
				$arrRecipient['confirmed'] = 1;
				$id = $this->Database
                    ->prepare("INSERT INTO tl_avisota_recipient %s")
					->set($arrRecipient)
					->execute()
                    ->insertId;

                $arrExistingRecipients[$arrRecipient['email']] = $id;

				// Log activity
				$this->log('Recipient ' . $arrRecipient['email'] . ' was imported to ' . $objList->title . ' by ' . $this->User->name . ' (' . $this->User->username . ')', 'tl_avisota_recipient_import::importRecipients', TL_AVISOTA_SUBSCRIPTION);

				++$intTotal;
			}
			else if ($blnOverwrite)
			{
				$this->Database->prepare("UPDATE tl_avisota_recipient %s WHERE pid=? AND email=?")
					->set($arrRecipient)
					->execute($this->Input->get('id'), $arrRecipient['email']);

				// Log activity
				$this->log('Recipient ' . $arrRecipient['email'] . ' was updated in ' . $objList->title . ' by ' . $this->User->name . ' (' . $this->User->username . ')', 'tl_avisota_recipient_import::importRecipients', TL_AVISOTA_SUBSCRIPTION);

				++$intOverwrite;
			}
			else
			{
				++$intSkipped;
			}
		}
	}
}

?>