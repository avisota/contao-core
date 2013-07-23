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


/**
 * Class AvisotaRecipient
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class AvisotaRecipient extends Controller
{
	protected $data;

	/**
	 * @param $arrRecipient
	 */
	public function __construct(array $data = null)
	{
		parent::__construct();

		$this->import('Database');

		$this->data = array();

		if ($data != null) {
			$this->setData($data);
		}
	}

	public function setData($data)
	{
		foreach ($data as $k => $v) {
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
		$this->data[$k] = $v;
	}

	public function __get($k)
	{
		return isset($this->data[$k]) ? $this->data[$k] : '';
	}

	public function __isset($k)
	{
		return isset($this->data[$k]);
	}

	public function __unset($k)
	{
		unset($this->data[$k]);
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
		if (!isset($data['email'])) {
			throw new AvisotaRecipientException($data, 'The recipient has no email!');
		}
		if (!$this->isValidEmailAddress($data['email'])) {
			throw new AvisotaRecipientException($data, 'The email "' . $data['email'] . '" is not valid!');
		}
	}

	public function isValid()
	{
		try {
			$this->validate($this->data);
			return true;
		}
		catch (AvisotaRecipientException $e) {
			return false;
		}
	}

	public function getMailingLists()
	{
		return isset($this->data['lists']) && is_array($this->data['lists']) ? $this->data['lists'] : array();
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
		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');

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
		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');
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
		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');
	}

	/**
	 * Send the subscription confirmation mail to the given mailing lists
	 * or all unconfirmed mailing lists, the recipient has subscribed.
	 *
	 * @param array|null $listIds
	 *
	 * @throws AvisotaSubscriptionException
	 */
	public function sendSubscriptionConfirmation(array $listIds = null)
	{
		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');
	}

	/**
	 * Send a reminder to the given mailing lists
	 * or all unconfirmed, not reminded mailing lists, the recipient has subscribed.
	 *
	 * @param array|null $listIds
	 *
	 * @throws AvisotaSubscriptionException
	 */
	public function sendRemind(array $listIds = null)
	{
		throw new AvisotaSubscriptionException($this, 'This recipient cannot subscribe!');
	}
}
