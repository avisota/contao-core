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
 * @license    LGPL
 * @filesource
 */

namespace Avisota;

/**
 * Class SubscriptionManager
 *
 * Manager for subscriptions.
 *
 * Accessible via DI container key avisota.subscription.
 * <pre>
 * global $container;
 * $subscriptionManager = $container->get('avisota.subscription');
 * </pre>
 *
 * @package Avisota
 */
class SubscriptionManager
{
	/**
	 * Check if a recipient is blacklisted.
	 *
	 * @param string|array $recipient Recipient email address or array of recipient details.
	 * @param null|array   $lists     Array of mailing lists to check the blacklist status
	 *
	 * @return bool|array
	 * Return <em>false</em> if the recipient is not blacklisted.
	 * Return an array of blacklisted lists, if <em>$lists</em> is given.
	 * Return <em>true</em> if the recipient is globally blacklisted, even if <em>$lists</em> is provided.
	 */
	public function isBlacklisted($recipient, $lists = null)
	{
		if (is_array($recipient)) {
			$email = $recipient['email'];
		}
		else if (is_object($recipient)) {
			$email = $recipient->email;
		}
		else {
			$email = (string) $recipient;
		}

		if ($lists === null) {
			$lists = array(0);
		}
		else {
			$lists = array_map('intval', $lists);
			$lists = array_filter($lists);
			// globally blacklisted
			$lists[] = '0';
		}

		$database = Database::getInstance();

		$listIds        = implode(',', $lists);
		$blacklistEntry = $database
			->prepare(
				'SELECT *
				 FROM tl_avisota_recipient_blacklist
				 WHERE email=?
				 AND pid IN (' . $listIds . ')'
			)
			->execute(md5(strtolower($email)));
		if ($blacklistEntry->numRows) {
			$blackklistedLists = $blacklistEntry->fetchEach('list');

			if (in_array('0', $blackklistedLists)) {
				return true;
			}
			else {
				return $blackklistedLists;
			}
		}

		return false;
	}

	/**
	 * Add a new subscription or update existing.
	 *
	 * @param string|array $recipient Recipient email address or array of recipient details.
	 * @param null|array   $lists     <em>null</em> to globally subscribe, or array of mailing lists to subscribe to.
	 */
	public function subscribe($recipient, $lists = null)
	{
		if (!is_array($recipient)) {
			$recipient = array('email' => $recipient);
		}

		// TODO
	}

	/**
	 * Remove subscription.
	 *
	 * @param string|array $recipient Recipient email address or array of recipient details.
	 * @param null|array   $lists     <em>null</em> to globally unsubscribe from all mailing lists, or array of mailing lists to unsubscribe from.
	 */
	public function unsubscribe($recipient, $lists = null)
	{
		if (!is_array($recipient)) {
			$recipient = array('email' => $recipient);
		}

		// TODO
	}
}