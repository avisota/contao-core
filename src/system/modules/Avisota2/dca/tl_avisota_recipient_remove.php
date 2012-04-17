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
 * Table tl_avisota_recipient_remove
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_remove'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Memory',
		'closed'                      => true,
		'onsubmit_callback'           => array
		(
			array('tl_avisota_recipient_remove', 'onsubmit_callback'),
		)
	),

	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'remove' => array('source', 'upload', 'emails')
		)
	),
	
	// Fields
	'fields' => array
	(
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['source'],
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'extensions'=>'csv')
		),
		'upload' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['upload'],
			'inputType'               => 'upload'
		),
		'emails' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['emails'],
			'inputType'               => 'textarea'
		)
	)
);

class tl_avisota_recipient_remove extends Backend
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
		$arrSource = $dc->getData('source');
		$arrUpload = $dc->getData('upload');
		$strEmails = $dc->getData('emails');
		
		$time = time();
		$intTotal = 0;
		
		if (is_array($arrSource))
		{
			foreach ($arrSource as $strCsvFile)
			{
				$objFile = new File($strCsvFile);
				$this->removeRecipients($objFile->getContent(), $intTotal);
			}
		}
		
		if ($arrUpload)
		{
			$this->removeRecipients(file_get_contents($arrUpload['tmp_name']), $intTotal);
		}
		
		if ($strEmails)
		{
			$this->removeRecipients($strEmails, $intTotal);
		}

		$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['confirm'], $intTotal);
	
		setcookie('BE_PAGE_OFFSET', 0, 0, '/');
		$this->reload();
	}
	
	
	/**
	 * Remove recipients.
	 * 
	 * @param string $strEmails
	 * @param int $intTotal
	 */
	protected function removeRecipients($strEmails, &$intTotal)
	{
		$arrEmails = preg_split('#[\s,;]#u', $strEmails, -1, PREG_SPLIT_NO_EMPTY);
		$arrEmails = array_filter(array_unique($arrEmails));
		
		$arrParams = array($this->Input->get('id'));
		$arrPlaceHolders = array();
		foreach ($arrEmails as $strEmail)
		{
			if (preg_match('#^".*"$#', $strEmail) || preg_match("#^'.*'$#", $strEmail))
			{
				$strEmail = substr($strEmail, 1, -1);
			}
			
			// Skip invalid entries
			if (!$this->isValidEmailAddress($strEmail))
			{
				continue;
			}
			
			$arrParams[] = $strEmail;
			$arrPlaceHolders[] = '?';
		}
		
		if (count($arrParams))
		{
			// Check whether the e-mail address exists
			$objDelete = $this->Database->prepare("DELETE FROM tl_avisota_recipient WHERE pid=? AND email IN (" . implode(',', $arrPlaceHolders) . ")")
										->execute($arrParams);
	
			$intTotal += $objDelete->affectedRows;
		}
	}
}
