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
 * Class SubscriptionManagerChain
 *
 * @package Avisota
 */
class SubscriptionManagerChain implements SubscriptionManagerInterface
{
	protected $chain = array();

	/**
	 * Add subscription manager to this chain.
	 *
	 * @param SubscriptionManagerInterface $subscriptionManager
	 * @param int                          $priority
	 */
	public function addSubscriptionManager(SubscriptionManagerInterface $subscriptionManager, $priority = 0)
	{
		$this->removeSubscriptionManager($subscriptionManager);

		$hash = spl_object_hash($subscriptionManager);
		if (!isset($this->chain[$priority])) {
			$this->chain[$priority] = array($hash => $subscriptionManager);
			krsort($this->chain);
		}
		else {
			$this->chain[$priority][$hash] = $subscriptionManager;
		}
	}

	/**
	 * Remove subscription manager from this chain.
	 *
	 * @param SubscriptionManagerInterface $subscriptionManager
	 */
	public function removeSubscriptionManager(SubscriptionManagerInterface $subscriptionManager)
	{
		$hash = spl_object_hash($subscriptionManager);
		foreach ($this->chain as &$subscriptionManagers) {
			unset($subscriptionManagers[$hash]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function resolveRecipient($recipientClass, $recipientIdentity, $createIfNotExists = false)
	{
		foreach ($this->chain as $subscriptionManagers) {
			foreach ($subscriptionManagers as $subscriptionManager) {
				$recipient = $subscriptionManager->resolveRecipient($recipientClass, $recipientIdentity, $createIfNotExists);
				if ($recipient) {
					return $recipient;
				}
			}
		}

		throw new \RuntimeException('Could not resolve recipient ' . $recipientIdentity . ' of type ' . $recipientClass);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isBlacklisted($recipient, $lists = null)
	{
		foreach ($this->chain as $subscriptionManagers) {
			foreach ($subscriptionManagers as $subscriptionManager) {
				if ($subscriptionManager->canHandle($recipient)) {
					return $subscriptionManager->isBlacklisted($recipient, $lists);
				}
			}
		}

		throw new \RuntimeException('Could not handle recipient of type ' . get_class($recipient));
	}

	/**
	 * {@inheritdoc}
	 */
	public function subscribe(
		$recipient,
		$lists = null,
		$options = 0
	) {
		foreach ($this->chain as $subscriptionManagers) {
			foreach ($subscriptionManagers as $subscriptionManager) {
				if ($subscriptionManager->canHandle($recipient)) {
					return $subscriptionManager->subscribe($recipient, $lists, $options);
				}
			}
		}

		throw new \RuntimeException('Could not handle recipient of type ' . get_class($recipient));
	}

	/**
	 * {@inheritdoc}
	 */
	public function confirm(
		$recipient,
		array $token
	) {
		foreach ($this->chain as $subscriptionManagers) {
			foreach ($subscriptionManagers as $subscriptionManager) {
				if ($subscriptionManager->canHandle($recipient)) {
					return $subscriptionManager->confirm($recipient, $token);
				}
			}
		}

		throw new \RuntimeException('Could not handle recipient of type ' . get_class($recipient));
	}

	/**
	 * {@inheritdoc}
	 */
	public function unsubscribe($recipient, $lists = null, $options = 0)
	{
		foreach ($this->chain as $subscriptionManagers) {
			foreach ($subscriptionManagers as $subscriptionManager) {
				if ($subscriptionManager->canHandle($recipient)) {
					return $subscriptionManager->isBlacklisted($recipient, $lists, $options);
				}
			}
		}

		throw new \RuntimeException('Could not handle recipient of type ' . get_class($recipient));
	}

	/**
	 * {@inheritdoc}
	 */
	public function canHandle($recipient)
	{
		foreach ($this->chain as $subscriptionManagers) {
			foreach ($subscriptionManagers as $subscriptionManager) {
				if ($subscriptionManager->canHandle($recipient)) {
					return true;
				}
			}
		}

		throw new \RuntimeException('Could not handle recipient of type ' . get_class($recipient));
	}
}