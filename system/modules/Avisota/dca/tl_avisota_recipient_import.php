<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright



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
		'default'                     => '{import_legend},source,upload;{format_legend:hide},delimiter,enclosure,columns,overwrite,force'
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
			'eval'                    => array('tl_class'=>'w50')
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
			'inputType'               => 'multiSelectWizard',
			'eval'                    => array('columnsCallback'=>array('tl_avisota_recipient_import', 'createFieldSelectorArray'), 'storeCallback'=>array('tl_avisota_recipient_import', 'storeFieldSelectorArray'), 'tl_class'=>'clr')
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
				'key' => 'colnum',
				'label' => $GLOBALS['TL_LANG']['tl_avisota_recipient_import']['colnum'],
				'source' => array(0=>1,1=>2,2=>3,3=>4,4=>5,5=>6,6=>7,7=>8,8=>9,9=>10,10=>11,11=>12,12=>13,13=>14,14=>15,15=>16,16=>17,17=>18,18=>19,19=>20),
				'style' => 'width:100px'
			),
			array
			(
				'key' => 'field',
				'label' => $GLOBALS['TL_LANG']['tl_avisota_recipient_import']['field'],
				'source' => array()
			)
		);
		
		foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $k=>$v)
		{
			$arr[1]['source'][$k] = $v['label'][0];
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
			$intColnum = $row['values']['colnum'];
			$strField = $row['values']['field'];
			
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
	public function storeFieldSelectorArray(MultiSelectWizard $objWidget)
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
		$arrColumnsRaw = $dc->getData('columns');
		
		$blnOverwrite = $dc->getData('overwrite') ? true : false;
		$blnForce = $dc->getData('force') ? true : false;
		
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
				$arrColumns[$arrRow['values']['colnum']] = $arrRow['values']['field'];
			}
			$time = time();
			$intTotal = 0;
			$intOverwrite = 0;
			$intSkipped = 0;
			$intInvalid = 0;
			
			foreach ($arrSource as $strCsvFile)
			{
				$objFile = new File($strCsvFile);
				
				if ($objFile->extension != 'csv')
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension);
					continue;
				}
				
				$this->importRecipients($objFile->handle, $strDelimiter, $strEnclosure, $arrColumns, $blnOverwrite, $blnForce, $time, $intTotal, $intOverwrite, $intSkipped, $intInvalid);
				$objFile->close();
			}
			
			if ($arrUpload)
			{
				$resFile = fopen($arrUpload['tmp_name'], 'r');
				$this->importRecipients($resFile, $strDelimiter, $strEnclosure, $arrColumns, $blnOverwrite, $blnForce, $time, $intTotal, $intOverwrite, $intSkipped, $intInvalid);
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
	protected function importRecipients($resFile, $strDelimiter, $strEnclosure, $arrColumns, $blnOverwrite, $blnForce, $time, &$intTotal, &$intOverwrite, &$intSkipped, &$intInvalid)
	{
		$arrRecipients = array();

		while(($arrRow = @fgetcsv($resFile, null, $strDelimiter, $strEnclosure)) !== false)
		{
			$arrRecipient = array();
			foreach ($arrColumns as $intColnum=>$strField)
			{
				$arrRecipient[$strField] = $arrRow[$intColnum];
			}
			$arrRecipients[] = $arrRecipient;
		}
		
		foreach ($arrRecipients as $arrRecipient)
		{
			// Skip invalid entries
			if (!$this->isValidEmailAddress($arrRecipient['email']))
			{
				$this->log('Recipient address "' . $arrRecipient['email'] . '" seems to be invalid and has been skipped', 'Avisota importRecipients()', TL_ERROR);

				++$intInvalid;
				continue;
			}
			
			$objBlacklist = $this->Database->prepare("SELECT * FROM tl_avisota_recipient_blacklist WHERE pid=? AND email=?")
				->execute($this->Input->get('id'), md5($arrRecipient['email']));
			
			// check blacklist
			if (!$blnForce && $objBlacklist->numRows > 0)
			{
				++$intSkipped;
				continue;
			}
			else if ($blnForce && $objBlacklist->numRows > 0)
			{
				$this->Database->prepare("DELETE FROM tl_avisota_recipient_blacklist WHERE pid=? AND email=?")
					->execute($this->Input->get('id'), md5($arrRecipient['email']));
			}
			
			// Check whether the e-mail address exists
			$objRecipient = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_avisota_recipient WHERE pid=? AND email=?")
										   ->execute($this->Input->get('id'), $arrRecipient['email']);

			$arrRecipient['tstamp']    = $time;
			
			if ($objRecipient->total < 1)
			{
				$arrRecipient['pid']       = $this->Input->get('id');
				$arrRecipient['addedOn']   = $time;
				$arrRecipient['addedBy']   = $this->User->id;
				$arrRecipient['confirmed'] = 1;
				$this->Database->prepare("INSERT INTO tl_avisota_recipient %s")
					->set($arrRecipient)
					->execute();

				++$intTotal;
			}
			else if ($blnOverwrite)
			{
				$this->Database->prepare("UPDATE tl_avisota_recipient %s WHERE pid=? AND email=?")
					->set($arrRecipient)
					->execute($this->Input->get('id'), $arrRecipient['email']);
				
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