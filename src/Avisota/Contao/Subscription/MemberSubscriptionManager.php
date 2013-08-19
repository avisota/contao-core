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

namespace Avisota\Contao\Subscription;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Entity\RecipientBlacklist;
use Avisota\Contao\Entity\RecipientSubscription;
use Avisota\Contao\Event\ConfirmSubscriptionEvent;
use Avisota\Contao\Event\RecipientEvent;
use Avisota\Contao\Event\SubscribeEvent;
use Avisota\Contao\Event\UnsubscribeEvent;
use Contao\Doctrine\ORM\EntityHelper;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class MemberSubscriptionManager
 *
 * @package Avisota
 */
class MemberSubscriptionManager implements SubscriptionManagerInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function resolveRecipient($recipientClass, $recipientIdentity, $createIfNotExists = false)
	{
		// TODO
	}

	/**
	 * {@inheritdoc}
	 */
	public function isBlacklisted($recipient, $lists = null)
	{
		// TODO
	}

	/**
	 * {@inheritdoc}
	 */
	public function subscribe(
		$recipient,
		$lists = null,
		$options = 0
	) {
		// TODO
	}

	/**
	 * {@inheritdoc}
	 */
	public function confirm(
		$recipient,
		array $token
	) {
		// TODO
	}

	/**
	 * {@inheritdoc}
	 */
	public function unsubscribe($recipient, $lists = null, $options = 0)
	{
		// TODO
	}

	/**
	 * {@inheritdoc}
	 */
	public function canHandle($recipient)
	{
		// TODO
	}
}