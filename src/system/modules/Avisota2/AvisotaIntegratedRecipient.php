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
	 * @param $strEmail
	 */
	public static function byEmail($strEmail)
	{
		$objRecipient = new AvisotaIntegratedRecipient(array('email' => $strEmail));
		$objRecipient->load();
		return $objRecipient;
	}

	public static function checkBlacklisted($strEmail, $arrLists)
	{
		$arrLists = array_map('intval', $arrLists);
		$arrLists = array_filter($arrLists);
		if (count($arrLists)) {
			$strLists = implode(',', $arrLists);
			$objBlacklist = Database::getInstance()
				->prepare("SELECT * FROM tl_avisota_recipient_blacklist
				           WHERE email=? AND pid IN (" . $strLists . ")")
				->execute(md5(strtolower($strEmail)));
			if ($objBlacklist->numRows) {
				return $objBlacklist->fetchEach('list');
			}
		}
		return false;
	}

	public function __construct(array $arrData = null)
	{
		parent::__construct($arrData);

		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');
	}

	public function load($blnUncached = false)
	{
		if (isset($this->arrData['id'])) {
			$strField = 'id';
			$strValue = $this->id;
		} else if (isset($this->arrData['email'])) {
			$strField = 'email';
			$strValue = $this->email;
		} else {
			throw new AvisotaRecipientException($this->arrData, 'The recipient has no ID or EMAIL that can identify him!');
		}

		// fetch existing data
		$objRecipient = $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient WHERE $strField=?");
		// fetch uncached, e.g. if store is called before
		if ($blnUncached) {
			$objRecipient = $objRecipient->executeUncached($strValue);
		}
		// fetch cached result
		else {
			$objRecipient = $objRecipient->execute($strValue);
		}

		if ($objRecipient->next()) {
			$arrRecipient = $objRecipient->row();

			foreach ($arrRecipient as $k=>$v) {
				if (isset($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['load_callback']) && is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['load_callback'])) {
					foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['load_callback'] as $callback) {
						$this->import($callback[0]);
						$arrRecipient[$k] = $v = $this->$callback[0]->$callback[1]($v);
					}
				}
			}

			$this->setData($arrRecipient);
		} else {
			throw new AvisotaRecipientException($this->arrData, 'The recipient data for ' . $this->email . ' could not be loaded!');
		}
	}

	/**
	 * Store this recipient into the database.
	 *
	 * @throws AvisotaRecipientException
	 */
	public function store()
	{
		$this->validate($this->arrData);

		$arrData = $this->arrData;
		$arrData['tstamp'] = time();

		$arrSet = array();
		$arrArgs = array();

		foreach ($arrData as $k=>$v)
		{
			if (isset($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['save_callback']) && is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['save_callback'])) {
				foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['save_callback'] as $callback) {
					$this->import($callback[0]);
					$v = $this->$callback[0]->$callback[1]($v);
				}
			}

			$arrSet[] = $k . '=?';
			$arrArgs[] = trim($v);
		}

		$this->Database
			->prepare(sprintf('INSERT INTO tl_avisota_recipient SET %1$s ON DUPLICATE KEY UPDATE %1$s', implode(',', $arrSet)))
			->execute(array_merge($arrArgs, $arrArgs));

		$this->load(true);
	}

	/**
	 * Validate the recipient object.
	 *
	 * @static
	 * @param array $arrData
	 * @throws AvisotaRecipientException
	 */
	public function validate(array $arrData)
	{
		foreach ($arrData as $k=>$v) {
			$v = trim($v);
			if ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['eval']['mandatory'] && empty($v)) {
				throw new AvisotaRecipientException($arrData, 'The recipient data field "' . $k . '" is mandatory!');
			}
		}
		parent::validate($arrData);
	}

	public function getMailingLists()
	{
		return array_map('intval', $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient_to_mailing_list rtml WHERE recipient=?")
			->execute($this->id)
			->fetchEach('list'));
	}

	/**
	 * Subscribe this recipient to the mailing lists.
	 * Will <strong>not</strong> send any confirmation mails.
	 * Throws an exception, if the recipient is in the blacklist.
	 *
	 * @param array $arrLists
	 * @param bool $blnIgnoreBlacklist
	 * @throws AvisotaSubscriptionException
	 * @throws AvisotaBlacklistException
	 */
	public function subscribe(array $arrLists, $blnIgnoreBlacklist = false)
	{
		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		$arrLists = array_filter(array_map('intval', $arrLists));
		if (!count($arrLists)) {
			return false;
		}

		if (!$blnIgnoreBlacklist) {
			$arrBlacklisted = self::checkBlacklisted($this->email, $arrLists);
			if ($arrBlacklisted) {
				throw new AvisotaBlacklistException($this->email, $arrBlacklisted);
			}
		}

		$arrLists = array_diff($arrLists, $this->getMailingLists());
		if (count($arrLists)) {
			$arrValues = array();
			$arrArgs = array();
			foreach ($arrLists as $intList) {
				$arrValues[] = '(?, ?)';
				$arrArgs[] = $this->id;
				$arrArgs[] = $intList;
			}
			$this->Database
				->prepare("INSERT INTO tl_avisota_recipient_to_mailing_list (recipient, list) VALUES " . implode(', ', $arrValues))
				->execute($arrArgs);

			// clean up blacklist
			$this->Database
				->prepare("DELETE FROM tl_avisota_recipient_blacklist WHERE email=? AND pid IN (" . implode(',', $arrLists) . ")")
				->execute(md5(strtolower($this->email)));

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSubscribe']) && is_array($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSubscribe']))
			{
				foreach ($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSubscribe'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this, $arrLists);
				}
			}

			return $arrLists;
		}

		return true;
	}

	/**
	 * Confirm the subscription of the mailing lists.
	 *
	 * @param array $arrToken
	 * @throws AvisotaSubscriptionException
	 */
	public function confirmSubscription(array $arrToken)
	{
		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		$arrLists = array_filter(array_map('trim', $arrToken));
		if (!count($arrLists)) {
			return false;
		}

		$arrWhere = array();
		$arrArgs = array($this->id, '');
		foreach ($arrToken as $strToken) {
			$arrWhere[] = '?';
			$arrArgs[]  = $strToken;
		}

		$objList = $this->Database
			->prepare("SELECT l.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list l
					   ON l.id=t.list
					   WHERE t.recipient=? AND t.confirmed=? AND t.token IN (" . implode(',', $arrWhere) . ")
					   ORDER BY l.title")
			->execute($arrArgs);

		$arrLists = array();
		while ($objList->next()) {
			$arrLists[$objList->id] = $objList->row();

			$this->log('Recipient ' . $this->email . ' confirmed subscription to mailing list "' . $objList->title . '" [' . $objList->id . ']',
				'AvisotaIntegratedRecipient::confirmSubscription', TL_INFO);

			$this->Database
				->prepare("UPDATE tl_avisota_recipient_to_mailing_list SET confirmed=? WHERE recipient=? AND list=?")
				->execute(1, $this->id, $objList->id);
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientConfirmSubscription']) &&
			is_array($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientConfirmSubscription']))
		{
			foreach ($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientConfirmSubscription'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this, $arrLists);
			}
		}

		return $arrLists;
	}

	/**
	 * Remove the subscription to the mailing lists.
	 *
	 * @param array $arrLists
	 * @param bool $blnDoNotBlacklist
	 * @throws AvisotaSubscriptionException
	 */
	public function unsubscribe(array $arrLists, $blnDoNotBlacklist = false)
	{
		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		$arrLists = array_filter(array_map('intval', $arrLists));
		if (!count($arrLists)) {
			return false;
		}

		$this->loadLanguageFile('avisota_subscription');

		$objList = $this->Database
			->prepare("SELECT l.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list l
					   ON l.id=t.list
					   WHERE t.recipient=? AND t.list IN (" . implode(',', $arrLists) . ")
					   ORDER BY l.title")
			->execute($this->id);

		$arrListsByPage = array();
		while ($objList->next()) {
			$arrListsByPage[$objList->integratedRecipientManageSubscriptionPage][$objList->id] = $objList->row();
		}

		foreach ($arrListsByPage as $intPage=>$arrLists) {
			$objPage = $this->getPageDetails($intPage);

			$arrTitle = array();
			foreach ($arrLists as $arrList) {
				$arrTitle[] = $arrList['title'];
			}
			$strUrl = $this->generateFrontendUrl($objPage);

			$objPlain = new FrontendTemplate($GLOBALS['TL_CONFIG']['avisota_template_unsubscribe_mail_plain']);
			$objPlain->content = sprintf($GLOBALS['TL_LANG']['avisota_subscription']['unsubscribe']['plain'], implode(', ', $arrTitle), $strUrl);

			$objHtml = new FrontendTemplate($GLOBALS['TL_CONFIG']['avisota_template_unsubscribe_mail_html']);
			$objHtml->title = $GLOBALS['TL_LANG']['avisota']['unsubscribe']['subject'];
			$objHtml->content = sprintf($GLOBALS['TL_LANG']['avisota_subscription']['unsubscribe']['html'], implode(', ', $arrTitle), $strUrl);

			$objEmail = new Mail();

			$objEmail->setSubject($GLOBALS['TL_LANG']['avisota_subscription']['unsubscribe']['subject']);
			$objEmail->setText($objPlain->parse());
			$objEmail->setHtml($objHtml->parse());

			$objTransport = AvisotaTransport::getTransportModule();
			$objTransport->transportEmail($this->email, $objEmail);

			foreach ($arrLists as $arrList) {
				$this->log('Recipient ' . $this->email . ' was unsubscribed from mailing list "' . $arrList['title'] . '" [' . $arrList['id'] . ']',
					'AvisotaIntegratedRecipient::unsubscribe', TL_INFO);

				$this->Database
					->prepare("DELETE FROM tl_avisota_recipient_to_mailing_list WHERE recipient=? AND list=?")
					->execute($this->id, $arrList['id']);
			}
		}

		// delete recipient
		$objList = $this->Database
			->prepare("SELECT COUNT(t.list) AS c FROM tl_avisota_recipient_to_mailing_list t
					   WHERE t.recipient=?")
			->execute($this->id);

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientUnsubscribe']) &&
			is_array($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientUnsubscribe']))
		{
			foreach ($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientUnsubscribe'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this, $arrListsByPage, $objList->c == 0);
			}
		}

		if ($objList->c == 0) {
			$this->Database
				->prepare("DELETE FROM tl_avisota_recipient WHERE id=?")
				->execute($this->id);
		}

		return $arrListsByPage;
	}

	/**
	 * Send the subscription confirmation mail to the given mailing lists
	 * or all unconfirmed mailing lists, the recipient has subscribed.
	 *
	 * @param array|null $arrLists
	 * @param bool $blnResend
	 * @throws AvisotaSubscriptionException
	 */
	public function sendSubscriptionConfirmation(array $arrLists = null, $blnResend = false)
	{
		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		if ($arrLists !== null) {
			$arrLists = array_filter(array_map('intval', $arrLists));
			if (!count($arrLists)) {
				return false;
			}
		}

		$this->loadLanguageFile('avisota_subscription');

		$time = time();

		$objList = $this->Database
			->prepare("SELECT l.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list l
					   ON l.id=t.list
					   WHERE t.recipient=?" . ($blnResend ? " AND t.confirmationSent=0" : '')
					   . ($arrLists !== null ? " AND t.list IN (" . implode(',', $arrLists) . ")" : '')
					   . "ORDER BY l.title")
			->execute($this->id);

		$arrListsByPage = array();
		while ($objList->next()) {
			$arrList = $objList->row();

			// generate a token
			if (empty($arrList['token'])) {
				$arrList['token'] = substr(md5(mt_rand() . '-' . $this->id . '-' . $objList->id . '-' . $this->email . '-' . time()), 0, 8);
			}

			// set send time
			$arrList['confirmationSent'] = $time;

			$intPage = $objList->integratedRecipientManageSubscriptionPage ? $objList->integratedRecipientManageSubscriptionPage : $GLOBALS['objPage']->id;
			$arrListsByPage[$intPage][$objList->id] = $arrList;
		}

		foreach ($arrListsByPage as $intPage=>$arrLists) {
			$objPage = $this->getPageDetails($intPage);

			$arrTitle = array();
			$arrToken = array();
			foreach ($arrLists as $arrList) {
				$arrTitle[] = $arrList['title'];
				$arrToken[] = $arrList['token'];
			}
			$strUrl = $this->generateFrontendUrl($objPage->row()) . '?subscribetoken=' . implode(',', $arrToken);

			$objPlain = new FrontendTemplate($GLOBALS['TL_CONFIG']['avisota_template_subscribe_mail_plain']);
			$objPlain->content = sprintf($GLOBALS['TL_LANG']['avisota_subscription']['subscribe']['plain'], implode(', ', $arrTitle), $strUrl);

			$objHtml = new FrontendTemplate($GLOBALS['TL_CONFIG']['avisota_template_subscribe_mail_html']);
			$objHtml->title = $GLOBALS['TL_LANG']['avisota']['subscribe']['subject'];
			$objHtml->content = sprintf($GLOBALS['TL_LANG']['avisota_subscription']['subscribe']['html'], implode(', ', $arrTitle), $strUrl);

			$objEmail = new Mail();

			$objEmail->setSubject($GLOBALS['TL_LANG']['avisota_subscription']['subscribe']['subject']);
			$objEmail->setText($objPlain->parse());
			$objEmail->setHtml($objHtml->parse());

			$objTransport = AvisotaTransport::getTransportModule();
			$objTransport->transportEmail($this->email, $objEmail);

			foreach ($arrLists as $arrList) {
				$this->log('Send subscription confirmation for recipient ' . $this->email . ' in mailing list "' . $arrList['title'] . '" [' . $arrList['id'] . ']',
					'AvisotaIntegratedRecipient::sendSubscriptionConfirmation', TL_INFO);

				$this->Database
					->prepare("UPDATE tl_avisota_recipient_to_mailing_list SET confirmationSent=?, token=? WHERE recipient=? AND list=?")
					->execute($arrList['confirmationSent'], $arrList['token'], $this->id, $arrList['id']);
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionConfirmation']) &&
			is_array($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionConfirmation']))
		{
			foreach ($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionConfirmation'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this, $arrListsByPage);
			}
		}

		return $arrListsByPage;
	}

	/**
	 * Send a reminder to the given mailing lists
	 * or all unconfirmed, not reminded mailing lists, the recipient has subscribed.
	 *
	 * @param array|null $arrLists
	 * @throws AvisotaSubscriptionException
	 */
	public function sendRemind(array $arrLists = null, $blnForce = false)
	{
		if (!$GLOBALS['TL_CONFIG']['avisota_send_notification']) {
			return false;
		}

		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		if ($arrLists !== null) {
			$arrLists = array_filter(array_map('intval', $arrLists));
			if (!count($arrLists)) {
				return false;
			}
		}

		$this->loadLanguageFile('avisota_subscription');

		$time = time();

		$reminderTime = $GLOBALS['TL_CONFIG']['avisota_notification_time'] * 24 * 60 * 60;

		$objList = $this->Database
			->prepare("SELECT l.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list l
					   ON l.id=t.list
					   WHERE t.recipient=? AND t.confirmed=?" .
					   ($blnForce ? '' : "AND t.confirmationSent>0
					     AND (
					         (t.reminderSent=0 AND UNIX_TIMESTAMP()-t.confirmationSent>?)
					       OR
					         (t.reminderSent>0 AND UNIT_TIMESTAMP()-t.reminderSent>(?+(?*t.reminderCount/2) AND t.reminderCount<?)
					     )") .
					   ($arrLists !== null ? " AND t.list IN (" . implode(',', $arrLists) . ")" : '') .
					   "ORDER BY l.title")
			->execute($this->id, '', $reminderTime, $reminderTime, $reminderTime, $GLOBALS['TL_CONFIG']['avisota_notification_count']);

		$arrListsByPage = array();
		while ($objList->next()) {
			$arrList = $objList->row();

			// generate a token
			if (empty($arrList['token'])) {
				$arrList['token'] = substr(md5(mt_rand() . '-' . $this->id . '-' . $objList->id . '-' . $this->email . '-' . time()), 0, 8);
			}

			// set send time
			$arrList['reminderSent'] = $time;

			$intPage = $objList->integratedRecipientManageSubscriptionPage ? $objList->integratedRecipientManageSubscriptionPage : $GLOBALS['objPage']->id;
			$arrListsByPage[$intPage][$objList->id] = $arrList;
		}

		foreach ($arrListsByPage as $intPage=>$arrLists) {
			$objPage = $this->getPageDetails($intPage);

			$arrTitle = array();
			$arrToken = array();
			foreach ($arrLists as $arrList) {
				$arrTitle[] = $arrList['title'];
				$arrToken[] = $arrList['token'];
			}
			$strUrl = $this->generateFrontendUrl($objPage->row()) . '?subscribetoken=' . implode(',', $arrToken);

			$objPlain = new FrontendTemplate($GLOBALS['TL_CONFIG']['avisota_template_notification_mail_plain']);
			$objPlain->content = sprintf($GLOBALS['TL_LANG']['avisota_subscription']['notification']['plain'], implode(', ', $arrTitle), $strUrl);

			$objHtml = new FrontendTemplate($GLOBALS['TL_CONFIG']['avisota_template_notification_mail_html']);
			$objHtml->title = $GLOBALS['TL_LANG']['avisota']['subscribe']['subject'];
			$objHtml->content = sprintf($GLOBALS['TL_LANG']['avisota_subscription']['notification']['html'], implode(', ', $arrTitle), $strUrl);

			$objEmail = new Mail();

			$objEmail->setSubject($GLOBALS['TL_LANG']['avisota_subscription']['notification']['subject']);
			$objEmail->setText($objPlain->parse());
			$objEmail->setHtml($objHtml->parse());

			$objTransport = AvisotaTransport::getTransportModule();
			$objTransport->transportEmail($this->email, $objEmail);

			foreach ($arrLists as $arrList) {
				$this->log('Send subscription reminder for recipient ' . $this->email . ' in mailing list "' . $arrList['title'] . '"',
					'AvisotaIntegratedRecipient::sendRemind', TL_INFO);

				$this->Database
					->prepare("UPDATE tl_avisota_recipient_to_mailing_list SET reminderSent=?, reminderCount=reminderCount+1, token=? WHERE recipient=? AND list=?")
					->execute($arrList['reminderSent'], $arrList['token'], $this->id, $arrList['id']);
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionReminder']) &&
			is_array($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionReminder']))
		{
			foreach ($GLOBALS['TL_HOOKS']['avisotaIntegratedRecipientSendSubscriptionReminder'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this, $arrListsByPage);
			}
		}

		return $arrListsByPage;
	}
}
