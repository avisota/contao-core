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

	protected function load()
	{
		$objRecipient = $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient WHERE email=?")
			->execute($this->email);
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
		self::validate($this->arrData);

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

			$arrSet[] = $k;
			$arrArgs[] = trim($v);
		}

		$this->Database
			->prepare(sprintf('INSERT INTO tl_avisota_recipient SET %1$s ON DUPLICATE KEY UPDATE %1$s', implode(',', $arrSet)))
			->execute(array_merge($arrArgs, $arrArgs));

		$this->load();
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

		var_dump($arrLists, $this->getMailingLists());
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
	 * @param array $arrLists
	 * @throws AvisotaSubscriptionException
	 */
	public function confirmSubscription(array $arrLists)
	{
		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}


		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');
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

		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');
	}

	/**
	 * Send the subscription confirmation mail to the given mailing lists
	 * or all unconfirmed mailing lists, the recipient has subscribed.
	 *
	 * @param array|null $arrLists
	 * @throws AvisotaSubscriptionException
	 */
	public function sendSubscriptionConfirmation(array $arrLists = null)
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

		$time = time();

		$objList = $this->Database
			->execute("SELECT l.* FROM tl_avisota_recipient_to_mailing_list t
					   INNER JOIN tl_avisota_mailing_list l
					   ON l.id=t.list
					   WHERE t.confirmationSent=0"
					   . ($arrLists !== null ? " AND t.list IN (" . implode(',', $arrLists) . ")" : '')
					   . "ORDER BY l.title");

		$arrListsByPage = array();
		while ($objList->next()) {
			$arrList = $objList->row();

			// generate a token
			if (empty($arrList['token'])) {
				$arrList['token'] = substr(md5(mt_rand() . '-' . $this->id . '-' . $objList->id . '-' . $this->email . '-' . time()), 0, 8);
			}

			// set send time
			$arrList['confirmationSent'] = $time;

			$arrListsByPage[$objList->integratedRecipientManageSubscriptionPage][$objList->id] = $arrList;
		}

		foreach ($arrListsByPage as $intPage=>$arrLists) {
			$objPage = $this->getPageDetails($intPage);

			$arrToken = array();
			foreach ($arrLists as $arrList) {
				$arrToken[] = $arrList['token'];
			}
			$strUrl = $this->generateFrontendUrl($objPage) . '?subscribetoken=' . implode(',', $arrToken);

			$objPlain = new FrontendTemplate($GLOBALS['TL_CONFIG']['avisota_template_subscribe_mail_plain']);
			$objPlain->content = sprintf($GLOBALS['TL_LANG']['avisota_subscription']['subscribe']['plain'], implode(', ', $arrList), $strUrl);

			$objHtml = new FrontendTemplate($GLOBALS['TL_CONFIG']['avisota_template_subscribe_mail_html']);
			$objHtml->title = $GLOBALS['TL_LANG']['avisota']['subscribe']['subject'];
			$objHtml->content = sprintf($GLOBALS['TL_LANG']['avisota_subscription']['subscribe']['html'], implode(', ', $arrList), $strUrl);

			$objEmail = new BasicEmail();
		}

		$strUrl = $this->generateSubscribeUrl($arrTokens);

		$arrList = $this->getListNames($arrRecipient['lists']);

		if ($this->sendMail('subscribe', $objPlain->parse(), $objHtml->parse(), $arrRecipient['email']))
		{
			unset($arrRecipient['lists']);
			$arrRecipient['tstamp'] = $time;
			$arrRecipient['confirmed'] = '';
			$arrRecipient['addedOn'] = $time;
			$arrRecipient['addedByModule'] = $this->id;
			$arrRecipient['addedOnPage'] = $GLOBALS['objPage']->id;
			foreach ($arrTokens as $intId => $strToken)
			{
				$arrRecipient['pid'] = $intId;
				$arrRecipient['token'] = $strToken;
				$this->Database->prepare("INSERT INTO `tl_avisota_recipient` %s")
					->set($arrRecipient)
					->execute();

				$this->Database->prepare("DELETE FROM tl_avisota_recipient_blacklist WHERE pid=? AND email=?")
					->execute($intId, md5($arrRecipient['email']));
			}

			$_SESSION['avisota_subscription'][] = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['send'], $arrRecipient['email']).'|confirmation';
			$this->log('Add new recipient ' . $arrRecipient['email'] . ' to ' . implode(', ', $arrList), 'ModuleAvisotaSubscription::subscribe', TL_INFO);
		}
		else
		{
			$_SESSION['avisota_subscription'][] = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['rejected'], $arrRecipient['email']).'|error';
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaSubscribe']) && is_array($GLOBALS['TL_HOOKS']['avisotaSubscribe']))
		{
			foreach ($GLOBALS['TL_HOOKS']['avisotaSubscribe'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($arrRecipient, $arrTokens);
			}
		}
	}

	/**
	 * Send a reminder to the given mailing lists
	 * or all unconfirmed, not reminded mailing lists, the recipient has subscribed.
	 *
	 * @param array|null $arrLists
	 * @throws AvisotaSubscriptionException
	 */
	public function sendRemind(array $arrLists = null)
	{
		if (!$this->id) {
			throw new AvisotaSubscriptionException($this, 'This recipient has no ID!');
		}

		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');
	}
}
