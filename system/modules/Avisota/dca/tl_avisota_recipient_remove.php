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
 * Table tl_avisota_recipient_remove
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_remove'] = array
(

	// Config
	'config'   => array
	(
		'dataContainer'               => 'Memory',
		'closed'                      => true,
		'onload_callback'             => array
		(
			array('tl_avisota_recipient_remove', 'onload_callback'),
		),
		'onsubmit_callback'           => array
		(
			array('tl_avisota_recipient_remove', 'onsubmit_callback'),
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{remove_legend},source,upload,emails,blacklist'
	),

	// Fields
	'fields'   => array
	(
		'source'    => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['source'],
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType' => 'checkbox',
			                                   'files'     => true,
			                                   'filesOnly' => true,
			                                   'extensions'=> 'csv')
		),
		'upload'    => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['upload'],
			'inputType'               => 'upload'
		),
		'emails'    => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['emails'],
			'inputType'               => 'textarea'
		),
		'blacklist' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['blacklist'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=> 'm12'),
			'default'		  => true // Set default
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
	 * Load the data.
	 *
	 * @param DataContainer $dc
	 */
	public function onload_callback(DataContainer $dc)
	{
		$varData = $this->Session->get('AVISOTA_TRACKING_REMOVE');

		if ($varData && is_array($varData) && time() - $varData['tstamp'] < 300) {
			$dc->setData('blacklist', $varData['blacklist']);
		}
		else {
			// Ask for Default 
        		$blnBlacklist = $GLOBALS['TL_DCA']['tl_avisota_recipient_remove']['fields']['blacklist']['default'] ? true : false; 
			$dc->setData('blacklist', $blnBlacklist);
		}
	}


	/**
	 * Do the import.
	 *
	 * @param DataContainer $dc
	 */
	public function onsubmit_callback(DataContainer $dc)
	{
		$arrSource    = $dc->getData('source');
		$arrUpload    = $dc->getData('upload');
		$strEmails    = $dc->getData('emails');
		$blnBlacklist = $dc->getData('blacklist') ? true : false;

		$this->Session->set('AVISOTA_TRACKING_REMOVE', array(
			'tstamp'    => time(),
			'blacklist' => $blnBlacklist
		));

		$time     = time();
		$intTotal = 0;

		if (is_array($arrSource)) {
			foreach ($arrSource as $strCsvFile) {
				$objFile = new File($strCsvFile);
				$this->removeRecipients($objFile->getContent(), $intTotal, $blnBlacklist);
			}
		}

		if ($arrUpload) {
			$this->removeRecipients(file_get_contents($arrUpload['tmp_name']), $intTotal, $blnBlacklist);
		}

		if ($strEmails) {
			$this->removeRecipients($strEmails, $intTotal, $blnBlacklist);
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
	 * @param bool $blnBlacklist
	 */
	protected function removeRecipients($strEmails, &$intTotal, $blnBlacklist)
	{
		$arrEmails = preg_split('#[\s,;]#u', $strEmails, -1, PREG_SPLIT_NO_EMPTY);
		$arrEmails = array_map('strtolower', $arrEmails);
		$arrEmails = array_filter(array_unique($arrEmails));

		$arrParams       = array($this->Input->get('id'));
		$arrPlaceHolders = array();
		foreach ($arrEmails as $strEmail) {
			if (preg_match('#^".*"$#', $strEmail) || preg_match("#^'.*'$#", $strEmail)) {
				$strEmail = substr($strEmail, 1, -1);
			}

			// Skip invalid entries
			if (!$this->isValidEmailAddress($strEmail)) {
				continue;
			}

			$arrParams[]       = $strEmail;
			$arrPlaceHolders[] = '?';
		}

		$objList = $this->Database
			->prepare('SELECT * FROM tl_avisota_recipient_list WHERE id=?')
			->execute($this->Input->get('id'));
		if (!$objList->next()) {
			$this->log('Recipient list ID ' . $this->Input->get('id') . ' not found!', 'tl_avisota_recipient_remove', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		if (count($arrParams)) {
			// Check whether the e-mail address exists
			$objDelete = $this->Database->prepare("DELETE FROM tl_avisota_recipient WHERE pid=? AND email IN (" . implode(',', $arrPlaceHolders) . ")")
				->execute($arrParams);

			// Log activity
			foreach ($arrEmails as $strEmail) {
				$this->log('Recipient ' . $strEmail . ' was removed from ' . $objList->title . ' by ' . $this->User->name . ' (' . $this->User->username . ')', 'tl_avisota_recipient_remove::removeRecipients', TL_AVISOTA_SUBSCRIPTION);
			}

			// make blacklist entry
			if ($blnBlacklist) {
				$arrValues = array();
				foreach ($arrEmails as $strEmail) {
					$arrValues[] = sprintf('(%d, %d, \'%s\')', $this->Input->get('id'), time(), md5($strEmail));
				}
				$this->Database
					->query('INSERT INTO tl_avisota_recipient_blacklist (pid, tstamp, email) VALUES ' . implode(', ', $arrValues));
			}

			$intTotal += $objDelete->affectedRows;
		}
	}
}

?>