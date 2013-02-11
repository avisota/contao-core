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
 * Class AvisotaIntegratedRecipient
 *
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaIntegratedRecipient extends AvisotaRecipient
{
	/**
	 * @static
	 *
	 * @param $email
	 */
	public static function byEmail($email)
	{
		$recipient = new AvisotaIntegratedRecipient(array('email' => $email));
		$recipient->load();
		return $recipient;
	}

	public static function bySubscribeTokens($tokens)
	{
		$whereParts = array();
		foreach ($tokens as $token) {
			$whereParts[] = 'token=?';
		}

		$recipientRef = Database::getInstance()
			->prepare(
			'SELECT DISTINCT recipient FROM tl_avisota_recipient_to_mailing_list WHERE ' . implode(' OR ', $whereParts)
		)
			->execute($tokens);

		if ($recipientRef->numRows > 1) {
			throw new AvisotaRecipientException('Illegal token list.');
		}
		else if ($recipientRef->next()) {
			$recipient     = new AvisotaIntegratedRecipient();
			$recipient->id = $recipientRef->recipient;
			$recipient->load();
			return $recipient;
		}
		else {
			return null;
		}
	}

	public static function checkBlacklisted($email, $lists)
	{
		$lists = array_map('intval', $lists);
		$lists = array_filter($lists);
		if (count($lists)) {
			$listIds     = implode(',', $lists);
			$blacklistEntry = Database::getInstance()
				->prepare(
				"SELECT * FROM tl_avisota_recipient_blacklist
				           WHERE email=? AND pid IN (" . $listIds . ")"
			)
				->execute(md5(strtolower($email)));
			if ($blacklistEntry->numRows) {
				return $blacklistEntry->fetchEach('list');
			}
		}
		return false;
	}

	public function __construct(array $data = null)
	{
		parent::__construct($data);

		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');
	}

	public function load($uncached = false)
	{
		if (isset($this->data['id'])) {
			$field = 'id';
			$value = $this->id;
		}
		else if (isset($this->data['email'])) {
			$field = 'email';
			$value = $this->email;
		}
		else {
			throw new AvisotaRecipientException($this->data, 'The recipient has no ID or EMAIL that can identify him!');
		}

		// fetch existing data
		$recipient = $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient WHERE $field=?");
		// fetch uncached, e.g. if store is called before
		if ($uncached) {
			$recipient = $recipient->executeUncached($value);
		}
		// fetch cached result
		else {
			$recipient = $recipient->execute($value);
		}

		if ($recipient->next()) {
			$recipientData = $recipient->row();

			foreach ($recipientData as $k => $v) {
				if (isset($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['load_callback']) && is_array(
					$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['load_callback']
				)
				) {
					foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['load_callback'] as $callback) {
						$this->import($callback[0]);
						$recipientData[$k] = $v = $this->$callback[0]->$callback[1]($v);
					}
				}
			}

			$this->setData($recipientData);
		}
		else {
			throw new AvisotaRecipientException($this->data, 'The recipient data for ' . $this->email . ' could not be loaded!');
		}
	}

	/**
	 * Store this recipient into the database.
	 *
	 * @throws AvisotaRecipientException
	 */
	public function store()
	{
		$this->validate($this->data);

		$data           = $this->data;
		$data['tstamp'] = time();

		$set  = array();
		$args = array();

		foreach ($data as $k => $v) {
			if (isset($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['save_callback']) && is_array(
				$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['save_callback']
			)
			) {
				foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['save_callback'] as $callback) {
					$this->import($callback[0]);
					$v = $this->$callback[0]->$callback[1]($v);
				}
			}

			$set[]  = $k . '=?';
			$args[] = trim($v);
		}

		$insertSet = array();
		// added on
		$insertSet[] = time();
		// added by backend user
		if (TL_MODE == 'BE') {
			$backendUser = BackendUser::getInstance();
			$backendUser->authenticate();
			$insertSet[] = $backendUser->id;
		}
		// added by recipient itself
		else {
			$insertSet[] = '0';
		}

		$this->Database
			->prepare(
			sprintf(
				'INSERT INTO tl_avisota_recipient SET addedOn=?, addedBy=?, %1$s ON DUPLICATE KEY UPDATE %1$s',
				implode(',', $set)
			)
		)
			->execute(array_merge($insertSet, $args, $args));

		$this->load(true);
	}

	/**
	 * Validate the recipient object.
	 *
	 * @static
	 *
	 * @param array $data
	 *
	 * @throws AvisotaRecipientException
	 */
	public function validate(array $data)
	{
		foreach ($data as $k => $v) {
			$v = trim($v);
			if ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['eval']['mandatory'] && empty($v)) {
				throw new AvisotaRecipientException($data, 'The recipient data field "' . $k . '" is mandatory!');
			}
		}
		parent::validate($data);
	}

	public function getMailingLists()
	{
		return array_map(
			'intval',
			$this->Database
				->prepare("SELECT * FROM tl_avisota_recipient_to_mailing_list rtml WHERE recipient=?")
				->execute($this->id)
				->fetchEach('list')
		);
	}

	/**
	 * Subscribe this recipient to the mailing lists.
	 * Will <strong>not</strong> send any confirmation mails.
	 * Throws an exception, if the recipient is in the blacklist.
	 *
	 * @param array $lists
	 * @param bool  $ignoreBlacklist
	 *
	 * @throws AvisotaSubscriptionException
	 * @throws AvisotaBlacklistException
	 */
	public function subscribe(array $lists, $ignoreBlacklist = false)
	{
		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		$lists = array_filter(array_map('intval', $lists));
		if (!count($lists)) {
			return false;
		}

		if (!$ignoreBlacklist) {
			$isBlacklisted = self::checkBlacklisted($this->email, $lists);
			if ($isBlacklisted) {
				throw new AvisotaBlacklistException($this->email, $isBlacklisted);
			}
		}

		$lists = array_diff($lists, $this->getMailingLists());
		if (count($lists)) {
			$values = array();
			$args   = array();
			foreach ($lists as $listId) {
				$values[] = '(?, ?)';
				$args[]   = $this->id;
				$args[]   = $listId;
			}
			$this->Database
				->prepare(
				"INSERT INTO tl_avisota_recipient_to_mailing_list (recipient, list) VALUES " . implode(', ', $values)
			)
				->execute($args);

			// clean up blacklist
			$this->Database
				->prepare(
				"DELETE FROM tl_avisota_recipient_blacklist WHERE email=? AND pid IN (" . implode(',', $lists) . ")"
			)
				->execute(md5(strtolower($this->email)));

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSubscribe']) && is_array(
				$GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSubscribe']
			)
			) {
				foreach ($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSubscribe'] as $callback) {
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this, $lists);
				}
			}

			return $lists;
		}

		return true;
	}

	/**
	 * Confirm the subscription of the mailing lists.
	 *
	 * @param array $tokens
	 *
	 * @throws AvisotaSubscriptionException
	 */
	public function confirmSubscription(array $tokens)
	{
		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		$lists = array_filter(array_map('trim', $tokens));
		if (!count($lists)) {
			return false;
		}

		$whereParts = array();
		$args  = array($this->id, '');
		foreach ($tokens as $token) {
			$whereParts[] = '?';
			$args[]  = $token;
		}

		$list = $this->Database
			->prepare(
			"SELECT l.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list l
					   ON l.id=t.list
					   WHERE t.recipient=? AND t.confirmed=? AND t.token IN (" . implode(',', $whereParts) . ")
					   ORDER BY l.title"
		)
			->execute($args);

		$lists = array();
		while ($list->next()) {
			$lists[$list->id] = $list->row();

			$this->log(
				'Recipient ' . $this->email . ' confirmed subscription to mailing list "' . $list->title . '" [' . $list->id . ']',
				'AvisotaIntegratedRecipient::confirmSubscription',
				TL_INFO
			);

			$this->Database
				->prepare("UPDATE tl_avisota_recipient_to_mailing_list SET confirmed=? WHERE recipient=? AND list=?")
				->execute(1, $this->id, $list->id);
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientConfirmSubscription']) &&
			is_array($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientConfirmSubscription'])
		) {
			foreach ($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientConfirmSubscription'] as $callback) {
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this, $lists);
			}
		}

		return $lists;
	}

	/**
	 * Remove the subscription to the mailing lists.
	 *
	 * @param array $listIds
	 * @param bool  $doNotBlacklist
	 *
	 * @throws AvisotaSubscriptionException
	 */
	public function unsubscribe(array $listIds, $doNotBlacklist = false)
	{
		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		$listIds = array_filter(array_map('intval', $listIds));
		if (!count($listIds)) {
			return false;
		}

		$this->loadLanguageFile('avisota_subscription');

		$list = $this->Database
			->prepare(
			"SELECT l.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list l
					   ON l.id=t.list
					   WHERE t.recipient=? AND t.list IN (" . implode(',', $listIds) . ")
					   ORDER BY l.title"
		)
			->execute($this->id);

		$listsByPage = array();
		while ($list->next()) {
			$listsByPage[$list->integratedRecipientManageSubscriptionPage][$list->id] = $list->row();
		}

		foreach ($listsByPage as $pageId => $lists) {
			$page = $this->getPageDetails($pageId);

			$title = array();
			foreach ($lists as $listData) {
				$title[] = $listData['title'];
			}
			$url = $this->generateFrontendUrl($page->row());

			$plainTemplate          = new AvisotaNewsletterTemplate($GLOBALS['TL_CONFIG']['avisota_template_unsubscribe_mail_plain']);
			$plainTemplate->content = sprintf(
				$GLOBALS['TL_LANG']['avisota_subscription']['unsubscribe']['plain'],
				implode(', ', $title),
				$url
			);

			$htmlTemplate          = new AvisotaNewsletterTemplate($GLOBALS['TL_CONFIG']['avisota_template_unsubscribe_mail_html']);
			$htmlTemplate->title   = $GLOBALS['TL_LANG']['avisota']['unsubscribe']['subject'];
			$htmlTemplate->content = sprintf(
				$GLOBALS['TL_LANG']['avisota_subscription']['unsubscribe']['html'],
				implode(', ', $title),
				$url
			);

			$email = new Mail();

			$email->setSubject($GLOBALS['TL_LANG']['avisota_subscription']['unsubscribe']['subject']);
			$email->setText($plainTemplate->parse());
			$email->setHtml($htmlTemplate->parse());

			$transport = AvisotaTransport::getTransportModule();
			$transport->transportEmail($this->email, $email);

			foreach ($lists as $listData) {
				$this->log(
					'Recipient ' . $this->email . ' was unsubscribed from mailing list "' . $listData['title'] . '" [' . $listData['id'] . ']',
					'AvisotaIntegratedRecipient::unsubscribe',
					TL_INFO
				);

				$this->Database
					->prepare("DELETE FROM tl_avisota_recipient_to_mailing_list WHERE recipient=? AND list=?")
					->execute($this->id, $listData['id']);
			}
		}

		// delete recipient
		$list = $this->Database
			->prepare(
			"SELECT COUNT(t.list) AS c FROM tl_avisota_recipient_to_mailing_list t
					   WHERE t.recipient=?"
		)
			->execute($this->id);

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientUnsubscribe']) &&
			is_array($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientUnsubscribe'])
		) {
			foreach ($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientUnsubscribe'] as $callback) {
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this, $listsByPage, $list->c == 0);
			}
		}

		if ($list->c == 0) {
			$this->Database
				->prepare("DELETE FROM tl_avisota_recipient WHERE id=?")
				->execute($this->id);
		}

		return $listsByPage;
	}

	/**
	 * Send the subscription confirmation mail to the given mailing lists
	 * or all unconfirmed mailing lists, the recipient has subscribed.
	 *
	 * @param array|null $listIds
	 * @param bool       $resend
	 *
	 * @throws AvisotaSubscriptionException
	 */
	public function sendSubscriptionConfirmation(array $listIds = null, $resend = false)
	{
		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		if ($listIds !== null) {
			$listIds = array_filter(array_map('intval', $listIds));
			if (!count($listIds)) {
				return false;
			}
		}

		$this->loadLanguageFile('avisota_subscription');

		$time = time();

		$list = $this->Database
			->prepare(
			"SELECT l.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list l
					   ON l.id=t.list
					   WHERE t.recipient=? AND t.confirmed=?" . ($resend ? " AND t.confirmationSent=0" : '')
				. ($listIds !== null ? " AND t.list IN (" . implode(',', $listIds) . ")" : '')
				. "ORDER BY l.title"
		)
			->execute($this->id, '');

		$listsByPage = array();
		while ($list->next()) {
			$listData = $list->row();

			// generate a token
			if (empty($listData['token'])) {
				$listData['token'] = substr(
					md5(mt_rand() . '-' . $this->id . '-' . $list->id . '-' . $this->email . '-' . time()),
					0,
					8
				);
			}

			// set send time
			$listData['confirmationSent'] = $time;

			$pageId                                = $list->integratedRecipientManageSubscriptionPage
				? $list->integratedRecipientManageSubscriptionPage : $GLOBALS['objPage']->id;

			$listsByPage[$pageId][$list->id] = $listData;
		}

		foreach ($listsByPage as $pageId => $lists) {
			$page = $this->getPageDetails($pageId);

			$titles = array();
			$tokens = array();
			foreach ($lists as $listData) {
				$titles[] = $listData['title'];
				$tokens[] = $listData['token'];
			}
			$url = $this->generateFrontendUrl($page->row()) . '?subscribetoken=' . implode(',', $tokens);

			$plainTemplate          = new AvisotaNewsletterTemplate($GLOBALS['TL_CONFIG']['avisota_template_subscribe_mail_plain']);
			$plainTemplate->content = sprintf(
				$GLOBALS['TL_LANG']['avisota_subscription']['subscribe']['plain'],
				implode(', ', $titles),
				$url
			);

			$htmlTemplate          = new AvisotaNewsletterTemplate($GLOBALS['TL_CONFIG']['avisota_template_subscribe_mail_html']);
			$htmlTemplate->title   = $GLOBALS['TL_LANG']['avisota']['subscribe']['subject'];
			$htmlTemplate->content = sprintf(
				$GLOBALS['TL_LANG']['avisota_subscription']['subscribe']['html'],
				implode(', ', $titles),
				$url
			);

			$email = new Mail();

			$email->setSubject($GLOBALS['TL_LANG']['avisota_subscription']['subscribe']['subject']);
			$email->setText($plainTemplate->parse());
			$email->setHtml($htmlTemplate->parse());

			$transport = AvisotaTransport::getTransportModule();
			$transport->transportEmail($this->email, $email);

			foreach ($lists as $listData) {
				$this->log(
					'Send subscription confirmation for recipient ' . $this->email . ' in mailing list "' . $listData['title'] . '" [' . $listData['id'] . ']',
					'AvisotaIntegratedRecipient::sendSubscriptionConfirmation',
					TL_INFO
				);

				$this->Database
					->prepare(
					"UPDATE tl_avisota_recipient_to_mailing_list SET confirmationSent=?, token=? WHERE recipient=? AND list=?"
				)
					->execute($listData['confirmationSent'], $listData['token'], $this->id, $listData['id']);
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionConfirmation']) &&
			is_array($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionConfirmation'])
		) {
			foreach ($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionConfirmation'] as $callback) {
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this, $listsByPage);
			}
		}

		return $listsByPage;
	}

	/**
	 * Send a reminder to the given mailing lists
	 * or all unconfirmed, not reminded mailing lists, the recipient has subscribed.
	 *
	 * @param array|null $listIds
	 *
	 * @throws AvisotaSubscriptionException
	 */
	public function sendRemind(array $listIds = null, $force = false)
	{
		if (!$GLOBALS['TL_CONFIG']['avisota_send_notification']) {
			return false;
		}

		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		if ($listIds !== null) {
			$listIds = array_filter(array_map('intval', $listIds));
			if (!count($listIds)) {
				return false;
			}
		}

		$this->loadLanguageFile('avisota_subscription');

		$time = time();

		$reminderTime = $GLOBALS['TL_CONFIG']['avisota_notification_time'] * 24 * 60 * 60;

		$list = $this->Database
			->prepare(
			"SELECT l.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list l
					   ON l.id=t.list
					   WHERE t.recipient=? AND t.confirmed=?" .
				($force
					? ''
					: "AND t.confirmationSent>0
					     AND (
					         (t.reminderSent=0 AND UNIX_TIMESTAMP()-t.confirmationSent>?)
					       OR
					         (t.reminderSent>0 AND UNIT_TIMESTAMP()-t.reminderSent>(?+(?*t.reminderCount/2) AND t.reminderCount<?)
					     )") .
				($listIds !== null ? " AND t.list IN (" . implode(',', $listIds) . ")" : '') .
				"ORDER BY l.title"
		)
			->execute(
			$this->id,
			'',
			$reminderTime,
			$reminderTime,
			$reminderTime,
			$GLOBALS['TL_CONFIG']['avisota_notification_count']
		);

		$listsByPage = array();
		while ($list->next()) {
			$listData = $list->row();

			// generate a token
			if (empty($listData['token'])) {
				$listData['token'] = substr(
					md5(mt_rand() . '-' . $this->id . '-' . $list->id . '-' . $this->email . '-' . time()),
					0,
					8
				);
			}

			// set send time
			$listData['reminderSent'] = $time;

			$pageId                                = $list->integratedRecipientManageSubscriptionPage
				? $list->integratedRecipientManageSubscriptionPage : $GLOBALS['objPage']->id;
			$listsByPage[$pageId][$list->id] = $listData;
		}

		foreach ($listsByPage as $pageId => $lists) {
			$page = $this->getPageDetails($pageId);

			$titles = array();
			$tokens = array();
			foreach ($lists as $listData) {
				$titles[] = $listData['title'];
				$tokens[] = $listData['token'];
			}
			$url = $this->generateFrontendUrl($page->row()) . '?subscribetoken=' . implode(',', $tokens);

			$plainTemplate          = new AvisotaNewsletterTemplate($GLOBALS['TL_CONFIG']['avisota_template_notification_mail_plain']);
			$plainTemplate->content = sprintf(
				$GLOBALS['TL_LANG']['avisota_subscription']['notification']['plain'],
				implode(', ', $titles),
				$url
			);

			$htmlTemplate          = new AvisotaNewsletterTemplate($GLOBALS['TL_CONFIG']['avisota_template_notification_mail_html']);
			$htmlTemplate->title   = $GLOBALS['TL_LANG']['avisota']['subscribe']['subject'];
			$htmlTemplate->content = sprintf(
				$GLOBALS['TL_LANG']['avisota_subscription']['notification']['html'],
				implode(', ', $titles),
				$url
			);

			$email = new Mail();

			$email->setSubject($GLOBALS['TL_LANG']['avisota_subscription']['notification']['subject']);
			$email->setText($plainTemplate->parse());
			$email->setHtml($htmlTemplate->parse());

			$transport = AvisotaTransport::getTransportModule();
			$transport->transportEmail($this->email, $email);

			foreach ($lists as $listData) {
				$this->log(
					'Send subscription reminder for recipient ' . $this->email . ' in mailing list "' . $listData['title'] . '"',
					'AvisotaIntegratedRecipient::sendRemind',
					TL_INFO
				);

				$this->Database
					->prepare(
					"UPDATE tl_avisota_recipient_to_mailing_list SET reminderSent=?, reminderCount=reminderCount+1, token=? WHERE recipient=? AND list=?"
				)
					->execute($listData['reminderSent'], $listData['token'], $this->id, $listData['id']);
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionReminder']) &&
			is_array($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionReminder'])
		) {
			foreach ($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionReminder'] as $callback) {
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this, $listsByPage);
			}
		}

		return $listsByPage;
	}
}
