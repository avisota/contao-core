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
 * Table tl_avisota_recipient_notify
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_notify'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Memory',
		'closed'                      => true,
		'onload_callback'             => array
		(
			array('tl_avisota_recipient_notify', 'onload_callback'),
		),
		'onsubmit_callback'           => array
		(
			array('tl_avisota_recipient_notify', 'onsubmit_callback'),
		)
	),

	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'notify' => array('recipient', 'confirmations', 'notifications', 'overdue')
		)
	),
	
	// Fields
	'fields' => array
	(
		'recipient' => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['recipient'],
			'inputType'        => 'select',
			'options_callback' => array('tl_avisota_recipient_notify', 'getRecipients'),
			'eval'             => array('submitOnChange'=>true)
		),
		'confirmations' => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['confirmations'],
			'inputType'        => 'checkbox',
			'options'          => array(),
			'eval'             => array('multiple'=>true)
		),
		'notifications' => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['notifications'],
			'inputType'        => 'checkbox',
			'options'          => array(),
			'eval'             => array('multiple'=>true)
		),
		'overdue' => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['overdue'],
			'inputType'        => 'checkbox',
			'options'          => array(),
			'eval'             => array('multiple'=>true)
		)
	)
);

class tl_avisota_recipient_notify extends Backend
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
		$dc->setData('recipient', $this->Input->get('id'));

		$objLists = $this->Database
			->prepare("SELECT t.confirmationSent, t.reminderSent, t.reminderCount, m.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list m ON m.id=t.list
					   WHERE t.recipient=? AND t.confirmed=?
					   ORDER BY m.title")
			->execute($this->Input->get('id'), '');
		while ($objLists->next()) {
			$strLabel = $objLists->title;

			if ($objLists->reminderSent > 0) {
				$strLabel .= ' (' . sprintf(
					$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['reminderSent'],
					$objLists->reminderCount,
					$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objLists->reminderSent)) . ')';
			} else if ($objLists->confirmationSent > 0) {
				$strLabel .= ' (' . sprintf(
					$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['confirmationSent'],
					$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objLists->confirmationSent)) . ')';
			}

			if ($objLists->confirmationSent == 0) {
				$strField = 'confirmations';
			} else if ($GLOBALS['TL_CONFIG']['avisota_send_notification'] && $objLists->reminderCount < $GLOBALS['TL_CONFIG']['avisota_notification_count']) {
				$strField = 'notifications';
			} else {
				$strField = 'overdue';
			}

			$GLOBALS['TL_DCA']['tl_avisota_recipient_notify']['fields'][$strField]['options'][$objLists->id] = $strLabel;
		}
	}


	/**
	 * Do the import.
	 * 
	 * @param DataContainer $dc
	 */
	public function onsubmit_callback(DataContainer $dc)
	{
		$intRecipient = $dc->getData('recipient');

		if ($intRecipient != $this->Input->get('id')) {
			$this->redirect('contao/main.php?do=avisota_recipient&amp;table=tl_avisota_recipient_notify&amp;act=edit&amp;id=' . $intRecipient);
		}

		$arrConfirmations = $dc->getData('confirmations');
		$arrNotifications = $dc->getData('notifications');
		$arrOverdue       = $dc->getData('overdue');

		$objRecipient = new AvisotaIntegratedRecipient(array('id'=>$intRecipient));
		$objRecipient->load();

		if (is_array($arrConfirmations) && count($arrConfirmations)) {
			$objRecipient->sendSubscriptionConfirmation($arrConfirmations);
		}

		if (is_array($arrNotifications) && count($arrNotifications)) {
			$objRecipient->sendRemind($arrNotifications, true);
		}

		if (is_array($arrOverdue) && count($arrOverdue)) {
			$objRecipient->sendRemind($arrOverdue, true);
		}
	}
	
	public function getRecipients()
	{
		$arrOptions = array();
		$objRecipient = $this->Database->execute("SELECT * FROM tl_avisota_recipient ORDER BY email");
		while ($objRecipient->next()) {
			$strLabel = trim($objRecipient->firstname . ' ' . $objRecipient->lastname);
			if (strlen($strLabel)) {
				$strLabel .= ' &lt;' . $objRecipient->email . '&gt;';
			}
			else
			{
				$strLabel = $objRecipient->email;
			}

			$arrOptions[$objRecipient->id] = $strLabel;
		}
		return $arrOptions;
	}
}
