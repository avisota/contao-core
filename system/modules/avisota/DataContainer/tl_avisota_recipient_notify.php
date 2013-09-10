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
 * @license    LGPL-3.0+
 * @filesource
 */


class orm_avisota_recipient_notify extends Backend
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
			"SELECT t.confirmationSent, t.reminderSent, t.reminderCount, m.* FROM orm_avisota_recipient_to_mailing_list t
					   INNER JOIN orm_avisota_mailing_list m ON m.id=t.list
					   WHERE t.recipient=? AND t.confirmed=?
					   ORDER BY m.title"
		)
			->execute($this->Input->get('id'), '');
		while ($list->next()) {
			$label = $list->title;

			if ($list->reminderSent > 0) {
				$label .= ' (' . sprintf(
					$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['reminderSent'],
					$list->reminderCount,
					$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $list->reminderSent)
				) . ')';
			}
			else if ($list->confirmationSent > 0) {
				$label .= ' (' . sprintf(
					$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['confirmationSent'],
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

			$GLOBALS['TL_DCA']['orm_avisota_recipient_notify']['fields'][$field]['options'][$list->id] = $label;
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
				'contao/main.php?do=avisota_recipient&amp;table=orm_avisota_recipient_notify&amp;act=edit&amp;id=' . $recipientId
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
		$recipient = $this->Database->execute("SELECT * FROM orm_avisota_recipient ORDER BY email");
		while ($recipient->next()) {
			$label = trim($recipient->forename . ' ' . $recipient->surname);
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
