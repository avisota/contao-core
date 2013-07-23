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

namespace Avisota\Contao;

use Avisota\Contao\Event\ConfirmSubscriptionEvent;
use Avisota\Contao\Event\RecipientEvent;
use Avisota\Contao\Event\SubscribeEvent;
use Avisota\Contao\Event\UnsubscribeEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriptionLogger implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return array(
			'avisota-recipient-subscribe'            => 'subscribe',
			'avisota-recipient-confirm-subscription' => 'confirm',
			'avisota-recipient-unsubscribe'          => 'unsubscribe',
			'avisota-recipient-create'               => 'create',
			'avisota-recipient-remove'               => 'remove',
		);
	}

	/**
	 * @param SubscribeEvent $event
	 */
	public function subscribe(SubscribeEvent $event)
	{
		/** @var LoggerInterface $logger */
		$logger = $GLOBALS['container']['avisota.logger.subscription'];

		$recipient    = $event->getRecipient();
		$subscription = $event->getSubscription();

		$logger->info(
			sprintf(
				'Recipient %s start subscription to %s',
				$recipient->getEmail(),
				$subscription->getList()
			)
		);
	}

	/**
	 * @param ConfirmSubscriptionEvent $event
	 */
	public function confirm(ConfirmSubscriptionEvent $event)
	{
		/** @var LoggerInterface $logger */
		$logger = $GLOBALS['container']['avisota.logger.subscription'];

		$recipient    = $event->getRecipient();
		$subscription = $event->getSubscription();

		$logger->info(
			sprintf(
				'Recipient %s confirmed subscription to %s',
				$recipient->getEmail(),
				$subscription->getList()
			)
		);
	}

	/**
	 * @param UnsubscribeEvent $event
	 */
	public function unsubscribe(UnsubscribeEvent $event)
	{
		/** @var LoggerInterface $logger */
		$logger = $GLOBALS['container']['avisota.logger.subscription'];

		$recipient    = $event->getRecipient();
		$subscription = $event->getSubscription();

		$logger->info(
			sprintf(
				'Recipient %s cancel subscription to %s',
				$recipient->getEmail(),
				$subscription->getList()
			)
		);
	}

	/**
	 * @param RecipientEvent $event
	 */
	public function create(RecipientEvent $event)
	{
		/** @var LoggerInterface $logger */
		$logger = $GLOBALS['container']['avisota.logger.subscription'];

		$recipient = $event->getRecipient();

		$logger->info(
			sprintf(
				'Recipient %s was created',
				$recipient->getEmail()
			),
			array('recipient' => $recipient->toArray())
		);
	}

	/**
	 * @param RecipientEvent $event
	 */
	public function remove(RecipientEvent $event)
	{
		/** @var LoggerInterface $logger */
		$logger = $GLOBALS['container']['avisota.logger.subscription'];

		$recipient = $event->getRecipient();

		$logger->info(
			sprintf(
				'Recipient %s was deleted',
				$recipient->getEmail()
			),
			array('recipient' => $recipient->toArray())
		);
	}
}
