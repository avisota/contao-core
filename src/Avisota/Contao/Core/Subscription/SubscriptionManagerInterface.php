<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Subscription;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Entity\RecipientBlacklist;
use Avisota\Contao\Entity\RecipientSubscription;
use Avisota\Contao\Core\Event\ConfirmSubscriptionEvent;
use Avisota\Contao\Core\Event\RecipientEvent;
use Avisota\Contao\Core\Event\SubscribeEvent;
use Avisota\Contao\Core\Event\UnsubscribeEvent;
use Contao\Doctrine\ORM\EntityHelper;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Interface SubscriptionManagerInterface
 *
 * Manager for subscriptions.
 *
 * Accessible via DI container key avisota.subscription.
 * <pre>
 * global $container;
 * $subscriptionManager = $container->get('avisota.subscription');
 * </pre>
 *
 * @package    avisota/contao-core
 */
interface SubscriptionManagerInterface
{
	const BLACKLIST_GLOBAL = 'global';

	const OPT_IGNORE_BLACKLIST = 1;

	const OPT_NO_BLACKLIST = 2;

	const OPT_UNSUBSCRIBE_GLOBAL = 4;

	const OPT_ACTIVATE = 8;

	const OPT_NO_CONFIRMATION = 16;

	public function resolveRecipient($recipientClass, $recipientIdentity, $createIfNotExists = false);

	/**
	 * Check if a recipient is blacklisted.
	 *
	 * @param string|array $recipient Recipient email address or array of recipient details.
	 * @param null|array   $lists     Array of mailing lists to check the blacklist status
	 *
	 * @return bool|RecipientBlacklist[]
	 * Return <em>false</em> if the recipient is not blacklisted.
	 * Return an array of blacklisted lists, if <em>$lists</em> is given.
	 * Return <em>true</em> if the recipient is globally blacklisted, even if <em>$lists</em> is provided.
	 */
	public function isBlacklisted($recipient, $lists = null);

	/**
	 * Add a new subscription or update existing.
	 *
	 * @param array|int|string|Recipient $recipient
	 * Recipient data (array of details, useful to create a new recipient),
	 * numeric id, email adress or recipient entity..
	 * @param null|array                 $lists
	 * <em>null</em> to globally subscribe, or array of mailing lists to subscribe to.
	 *
	 * @return RecipientSubscription[]
	 */
	public function subscribe(
		$recipient,
		$lists = null,
		$options = 0
	);

	/**
	 * Confirm subscription.
	 *
	 * @param array|int|string|Recipient $recipient
	 * Recipient data (array of details, useful to create a new recipient),
	 * numeric id, email adress or recipient entity..
	 * @param null|array                 $lists
	 * <em>null</em> to globally subscribe, or array of mailing lists to subscribe to.
	 *
	 * @return array
	 */
	public function confirm(
		$recipient,
		array $token
	);

	/**
	 * Remove subscription.
	 *
	 * @param string|array $recipient Recipient email address or array of recipient details.
	 * @param null|array   $lists     <em>null</em> to globally unsubscribe from all mailing lists, or array of mailing lists to unsubscribe from.
	 */
	public function unsubscribe($recipient, $lists = null, $options = 0);

	/**
	 * Check if this subscription manager can handle the recipient.
	 *
	 * @param $recipient
	 *
	 * @return bool
	 */
	public function canHandle($recipient);
}