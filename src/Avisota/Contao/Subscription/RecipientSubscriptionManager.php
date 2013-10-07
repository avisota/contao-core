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
use Avisota\Contao\Event\CreateRecipientEvent;
use Avisota\Contao\Event\RecipientEvent;
use Avisota\Contao\Event\RemoveRecipientEvent;
use Avisota\Contao\Event\SubscribeEvent;
use Avisota\Contao\Event\UnsubscribeEvent;
use Contao\Doctrine\ORM\EntityHelper;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class RecipientSubscriptionManager
 *
 * @package Avisota
 */
class RecipientSubscriptionManager implements SubscriptionManagerInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function resolveRecipient($recipientClass, $recipientIdentity, $createIfNotExists = false)
	{
		if ($recipientClass != 'Avisota\Contao\Entity\Recipient' &&
			$recipientClass != 'Avisota\Contao:Recipient' 
		) {
			return false;
		}

		$entityManager = EntityHelper::getEntityManager();
		$repository    = $entityManager->getRepository('Avisota\Contao\Entity\Recipient');

		// new recipient
		if (is_array($recipientIdentity)) {
			/** @var \Avisota\Contao\Entity\Recipient $recipient */
			$recipient = $repository->findOneBy(array('email' => $recipientIdentity['email']));

			$store = false;
			if (!$recipient) {
				if (!$createIfNotExists) {
					return false;
				}
				$recipient = new Recipient();
				$store = true;
			}
			$recipient->fromArray($recipientIdentity);
			if ($store) {
				/** @var EventDispatcher $eventDispatcher */
				$eventDispatcher = $GLOBALS['container']['event-dispatcher'];
				$eventDispatcher->dispatch(CreateRecipientEvent::NAME, new CreateRecipientEvent($recipient));

				$entityManager->persist($recipient);
				$entityManager->flush();
			}
		}

		// by id
		else if (is_string($recipientIdentity) && preg_match('#^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$#', $recipientIdentity)) {
			/** @var \Avisota\Contao\Entity\Recipient $recipient */
			$recipient = $repository->find($recipientIdentity);
		}

		// by email
		else if (is_string($recipientIdentity)) {
			/** @var \Avisota\Contao\Entity\Recipient $recipient */
			$recipient = $repository->findOneBy(array('email' => $recipientIdentity));
		}

		else {
			$recipient = $recipientIdentity;
		}

		if (!$recipient instanceof Recipient) {
			throw new \RuntimeException('Invalid argument ' . gettype($recipientIdentity));
		}

		return $recipient;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isBlacklisted($recipient, $lists = null)
	{
		global $container;

		$entityManager = EntityHelper::getEntityManager();

		$recipient = $this->resolveRecipient('Avisota\Contao:Recipient', $recipient);
		$lists     = $container['avisota.subscription']->resolveLists($lists, true);

		if (!$recipient) {
			return false;
		}

		$queryBuilder = $entityManager->createQueryBuilder();
		$queryBuilder
			->select('b')
			->from('Avisota\Contao:RecipientBlacklist', 'b')
			->where('b.email=?1')
			->setParameter(1, md5(strtolower($recipient->getEmail())));

		$whereList = array();
		foreach ($lists as $index => $list) {
			$whereList[] = 'b.list=?' . ($index + 2);
			$queryBuilder->setParameter($index + 2, $list);
		}

		$queryBuilder->orWhere('(' . implode(' OR ', $whereList) . ')');

		$query            = $queryBuilder->getQuery();
		$blacklistEntries = $query->getResult();

		if (count($blacklistEntries)) {
			return $blacklistEntries;
		}

		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function subscribe(
		$recipient,
		$lists = null,
		$options = 0
	) {
		global $container;

		$entityManager          = EntityHelper::getEntityManager();
		$subscriptionRepository = $entityManager->getRepository('Avisota\Contao:RecipientSubscription');

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$recipient = $this->resolveRecipient('Avisota\Contao:Recipient', $recipient, true);
		$lists     = $container['avisota.subscription']->resolveLists($lists, true);

		$blacklists = $this->isBlacklisted($recipient, $lists);
		if ($blacklists) {
			if ($options & static::OPT_IGNORE_BLACKLIST) {
				foreach ($blacklists as $blacklist) {
					$entityManager->remove($blacklist);
				}
			}
			else {
				foreach ($blacklists as $blacklist) {
					if ($blacklist->getList() == static::BLACKLIST_GLOBAL) {
						// break on global blacklist
						return;
					}
					else {
						$pos = array_search($blacklist->getList(), $lists);
						unset($lists[$pos]);
					}
				}
			}
		}

		$subscriptions = array();
		foreach ($lists as $list) {
			$subscription = $subscriptionRepository->findOneBy(
				array(
					 'recipient' => $recipient->getId(),
					 'list'      => $list
				)
			);

			if (!$subscription) {
				$subscription = new RecipientSubscription();
				$subscription->setRecipient($recipient);
				$subscription->setList($list);
				$subscription->setConfirmed($options & static::OPT_ACTIVATE);
				$subscription->setToken(
					substr(md5($recipient->getEmail() . '|' . mt_rand() . '|' . microtime(true)), 0, 16)
				);
				$entityManager->persist($subscription);

				$eventDispatcher->dispatch(
					SubscribeEvent::NAME,
					new SubscribeEvent($recipient, $subscription)
				);
			}

			if (!$subscription->getConfirmed()) {
				$subscriptions[] = $subscription;
			}
		}

		$entityManager->flush();

		if ($options ^ static::OPT_NO_CONFIRMATION) {
			// TODO send confirmations
		}

		return $subscriptions;
	}

	/**
	 * {@inheritdoc}
	 */
	public function confirm(
		$recipient,
		array $token
	) {
		$entityManager = EntityHelper::getEntityManager();

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$recipient = $this->resolveRecipient('Avisota\Contao:Recipient', $recipient);

		if (!$recipient || empty($token)) {
			return false;
		}

		$queryBuilder  = $entityManager->createQueryBuilder();
		$query = $queryBuilder
			->select('s')
			->from('Avisota\Contao:RecipientSubscription', 's')
			->where('s.recipient=:recipient')
			->andWhere(
				$queryBuilder
					->expr()
					->in('s.token', ':token')
			)
			->andWhere('s.confirmed=:confirmed')
			->setParameter(':recipient', $recipient->getId())
			->setParameter(':token', $token)
			->setParameter(':confirmed', false)
			->getQuery();
		$subscriptions = $query->getResult();

		/** @var RecipientSubscription $subscription */
		foreach ($subscriptions as $subscription) {
			$subscription->setConfirmed(true);
			$subscription->setConfirmedAt(new \DateTime());
			$entityManager->persist($subscription);

			$eventDispatcher->dispatch(
				ConfirmSubscriptionEvent::NAME,
				new ConfirmSubscriptionEvent($recipient, $subscription)
			);
		}

		$entityManager->flush();

		return $subscriptions;
	}

	/**
	 * {@inheritdoc}
	 */
	public function unsubscribe($recipient, $lists = null, $options = 0)
	{
		global $container;

		$entityManager          = EntityHelper::getEntityManager();
		$subscriptionRepository = $entityManager->getRepository('Avisota\Contao:RecipientSubscription');
		$blacklistRepository    = $entityManager->getRepository('Avisota\Contao:RecipientBlacklist');

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		if ($options & static::OPT_UNSUBSCRIBE_GLOBAL) {
			$listRepository = $entityManager->getRepository('Avisota\Contao:MailingList');
			$lists          = $listRepository->findAll();
		}

		$recipient = $this->resolveRecipient('Avisota\Contao:Recipient', $recipient);
		$lists     = $container['avisota.subscription']->resolveLists($lists, true);

		if (!$recipient) {
			return false;
		}

		$subscriptions = array();

		foreach ($lists as $list) {
			$subscription = $subscriptionRepository->findOneBy(
				array(
					 'recipient' => $recipient->getId(),
					 'list'      => $list
				)
			);
			
			if ($subscription) {
				if ($options ^ static::OPT_NO_BLACKLIST) {
					$blacklist = $blacklistRepository->findOneBy(
						array(
							 'email' => md5(strtolower($recipient->getEmail())),
							 'list'  => $list
						)
					);
					if (!$blacklist) {
						$blacklist = new RecipientBlacklist();
						$blacklist->setEmail(md5(strtolower($recipient->getEmail())));
						$blacklist->setList($list);
						$entityManager->persist($blacklist);
					}
				}

				$entityManager->remove($subscription);

				$subscriptions[] = $subscription;

				$eventDispatcher->dispatch(
					UnsubscribeEvent::NAME,
					new UnsubscribeEvent($recipient, $subscription, $blacklist)
				);
			}
		}

		$entityManager->flush();

		$remainingSubscriptions = $subscriptionRepository
			->findBy(array('recipient' => $recipient->getId()));
		if (!$remainingSubscriptions || !count($remainingSubscriptions)) {
			$eventDispatcher->dispatch(RemoveRecipientEvent::NAME, new RemoveRecipientEvent($recipient));
			$entityManager->remove($recipient);
			$entityManager->flush();
		}

		if ($options ^ static::OPT_NO_CONFIRMATION) {
			// TODO send confirmations
		}

		return $subscriptions;
	}

	/**
	 * {@inheritdoc}
	 */
	public function canHandle($recipient)
	{
		//this manager can handle the Recipient class, an array containing an email field and an email string
		return ($recipient instanceof Recipient) || is_string($recipient) || (is_array($recipient) && $recipient['email']);
	}
}