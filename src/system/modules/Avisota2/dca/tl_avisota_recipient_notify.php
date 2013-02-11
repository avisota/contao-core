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
 * Table tl_avisota_recipient_notify
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_notify'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onload_callback'   => array
		(
			array('tl_avisota_recipient_notify', 'onload_callback'),
		),
		'onsubmit_callback' => array
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
	'fields'       => array
	(
		'recipient'     => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['recipient'],
			'inputType'        => 'select',
			'options_callback' => array('tl_avisota_recipient_notify', 'getRecipients'),
			'eval'             => array('submitOnChange' => true)
		),
		'confirmations' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['confirmations'],
			'inputType' => 'checkbox',
			'options'   => array(),
			'eval'      => array('multiple' => true)
		),
		'notifications' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['notifications'],
			'inputType' => 'checkbox',
			'options'   => array(),
			'eval'      => array('multiple' => true)
		),
		'overdue'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['overdue'],
			'inputType' => 'checkbox',
			'options'   => array(),
			'eval'      => array('multiple' => true)
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

		$list = $this->Database
			->prepare(
			"SELECT t.confirmationSent, t.reminderSent, t.reminderCount, m.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list m ON m.id=t.list
					   WHERE t.recipient=? AND t.confirmed=?
					   ORDER BY m.title"
		)
			->execute($this->Input->get('id'), '');
		while ($list->next()) {
			$label = $list->title;

			if ($list->reminderSent > 0) {
				$label .= ' (' . sprintf(
					$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['reminderSent'],
					$list->reminderCount,
					$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $list->reminderSent)
				) . ')';
			}
			else if ($list->confirmationSent > 0) {
				$label .= ' (' . sprintf(
					$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['confirmationSent'],
					$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $list->confirmationSent)
				) . ')';
			}

			if ($list->confirmationSent == 0) {
				$field = 'confirmations';
			}
			else if ($GLOBALS['TL_CONFIG']['avisota_send_notification'] && $list->reminderCount < $GLOBALS['TL_CONFIG']['avisota_notification_count']) {
				$field = 'notifications';
			}
			else {
				$field = 'overdue';
			}

			$GLOBALS['TL_DCA']['tl_avisota_recipient_notify']['fields'][$field]['options'][$list->id] = $label;
		}
	}


	/**
	 * Do the import.
	 *
	 * @param DataContainer $dc
	 */
	public function onsubmit_callback(DataContainer $dc)
	{
		$recipientId = $dc->getData('recipient');

		if ($recipientId != $this->Input->get('id')) {
			$this->redirect(
				'contao/main.php?do=avisota_recipient&amp;table=tl_avisota_recipient_notify&amp;act=edit&amp;id=' . $recipientId
			);
		}

		$confirmations = $dc->getData('confirmations');
		$notifications = $dc->getData('notifications');
		$overdue       = $dc->getData('overdue');

		$recipient = new AvisotaIntegratedRecipient(array('id' => $recipientId));
		$recipient->load();

		if (is_array($confirmations) && count($confirmations)) {
			$recipient->sendSubscriptionConfirmation($confirmations);
		}

		if (is_array($notifications) && count($notifications)) {
			$recipient->sendRemind($notifications, true);
		}

		if (is_array($overdue) && count($overdue)) {
			$recipient->sendRemind($overdue, true);
		}
	}

	public function getRecipients()
	{
		$options   = array();
		$recipient = $this->Database->execute("SELECT * FROM tl_avisota_recipient ORDER BY email");
		while ($recipient->next()) {
			$label = trim($recipient->firstname . ' ' . $recipient->lastname);
			if (strlen($label)) {
				$label .= ' &lt;' . $recipient->email . '&gt;';
			}
			else {
				$label = $recipient->email;
			}

			$options[$recipient->id] = $label;
		}
		return $options;
	}
}
