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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class AvisotaRecipient
 *
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaRecipient extends Controller
{
	protected $arrData;

	/**
	 * @param $arrRecipient
	 */
	public function __construct(array $arrData = null)
	{
		parent::__construct();

		$this->import('Database');

		$this->arrData = array();

		if ($arrData != null) {
			$this->setData($arrData);
		}
	}

	public function setData($arrData)
	{
		foreach ($arrData as $k=>$v) {
			$this->$k = $v;
		}
	}

	public function __set($k, $v)
	{
		switch ($k) {
			case 'id':
				$v = intval($v);
				break;

			case 'email':
				$v = strtolower($v);
				break;
		}
		$this->arrData[$k] = $v;
	}

	public function __get($k)
	{
		return isset($this->arrData[$k]) ? $this->arrData[$k] : '';
	}

	public function __isset($k)
	{
		return isset($this->arrData[$k]);
	}

	public function __unset($k)
	{
		unset($this->arrData[$k]);
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
		if (!isset($arrData['email'])) {
			throw new AvisotaRecipientException($arrData, 'The recipient has no email!');
		}
		if (!$this->isValidEmailAddress($arrData['email'])) {
			throw new AvisotaRecipientException($arrData, 'The email "' . $arrData['email'] . '" is not valid!');
		}
	}

	public function isValid()
	{
		try {
			$this->validate($this->arrData);
			return true;
		} catch (AvisotaRecipientException $e) {
			return false;
		}
	}

	public function getMailingLists()
	{
		return isset($this->arrData['lists']) && is_array($this->arrData['lists']) ? $this->arrData['lists'] : array();
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
		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');

	}

	/**
	 * Confirm the subscription of the mailing lists.
	 *
	 * @param array $arrToken
	 * @throws AvisotaSubscriptionException
	 */
	public function confirmSubscription(array $arrToken)
	{
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
		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');
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
		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');
	}
}
