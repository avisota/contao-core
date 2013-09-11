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

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Entity\RecipientBlacklist;
use Avisota\Contao\Entity\MemberSubscription;
use Avisota\Contao\Event\ConfirmSubscriptionEvent;
use Avisota\Contao\Event\RecipientEvent;
use Avisota\Contao\Event\SubscribeEvent;
use Avisota\Contao\Event\UnsubscribeEvent;
use Avisota\Contao\Subscription\SubscriptionManagerInterface;
use Contao\Doctrine\ORM\EntityHelper;
use Model\Collection;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class MemberSubscriptionManager
 *
 * Manager for subscriptions.
 *
 * Accessible via DI container key avisota.subscription.
 * <pre>
 * global $container;
 * MemberSubscriptionManager = $container->get('avisota.subscription');
 * </pre>
 *
 * @package Avisota
 */
class MemberSubscriptionManager extends \Controller implements SubscriptionManagerInterface
{
	public function __construct()
	{
		parent::__construct();
	}

	public function resolveRecipient($recipientClass, $recipientIdentity, $createIfNotExists = false)
    {
        // new recipient
        if (is_array($recipientIdentity))
        {
            // ToDo: Add multi member support.

            /** @var \Avisota\Contao\Entity\Recipient $recipient */
            /*
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
            /* $eventDispatcher = $GLOBALS['container']['event-dispatcher'];
              $eventDispatcher->dispatch(CreateRecipientEvent::NAME, new CreateRecipientEvent($recipient));

              $entityManager->persist($recipient);
              $entityManager->flush();
              } */
        }

        // by id
        else if (is_numeric($recipientIdentity))
        {
            throw new \RuntimeException('Ups, we got an ID. Sorry i can not work so.');
        }

        // by email
        else if (is_string($recipientIdentity))
        {
            $recipient = \Database::getInstance()
            ->prepare('SELECT * FROM `tl_member` WHERE email = ?')
            ->limit(1)
            ->execute($recipientIdentity);

            if ($recipient->numRows == 0)
            {
                throw new \RuntimeException('Found no  recipient.');
            }
            
            return $recipient;
        }

        throw new \RuntimeException('Found no recipient.');
    }
    
    protected function resolveLists($lists, $includeGlobal = false)
    {
        if ($includeGlobal && !in_array(static::BLACKLIST_GLOBAL, $lists))
        {
            $lists[] = static::BLACKLIST_GLOBAL;
        }

        $lists = array_map(
                function ($list)
                {
                    if ($list instanceof MailingList)
                    {
                        return 'mailing_list:' . $list->getId();
                    }
                    // TODO better use a regex here, but I´m not sure what ids could be possible
                    else if ($list !== 'global')
                    {
                        return 'mailing_list:' . $list;
                    }
                    return $list;
                }, $lists
        );

        return array_values($lists);
    }
    
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
    public function isBlacklisted($recipient, $lists = null)
    {
        $entityManager = EntityHelper::getEntityManager();

        if (!$recipient)
        {
            return false;
        }        

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
                ->select('b')
                ->from('Avisota\Contao:RecipientBlacklist', 'b')
                ->where('b.email=?1')
                ->setParameter(1, md5(strtolower($recipient->email)));

        $whereList = array();
        foreach ($lists as $index => $list)
        {
            $whereList[] = 'b.list=?' . ($index + 2);
            $queryBuilder->setParameter($index + 2, $list);
        }

        $queryBuilder->orWhere('(' . implode(' OR ', $whereList) . ')');

        $query            = $queryBuilder->getQuery();
        $blacklistEntries = $query->getResult();

        if (count($blacklistEntries))
        {
            return $blacklistEntries;
        }

        return false;
    }

    /**
     * Add a new subscription or update existing.
     *
     * @param array|int|string|Recipient $recipient
     * Recipient data (array of details, useful to create a new recipient),
     * numeric id, email adress or recipient entity.
     * 
     * @param null|array $lists
     * <em>null</em> to globally subscribe, or array of mailing lists to subscribe to.
     *
     * @return MemberSubscription[]
     */
    public function subscribe($recipient, $lists = null, $options = 0)
    {
        $entityManager          = EntityHelper::getEntityManager();
        $subscriptionRepository = $entityManager->getRepository('Avisota\Contao:MemberSubscription');
        
        $recipient  = $this->resolveRecipient($recipient, false);
        $lists      = $this->resolveLists($this->clearList($lists), true);
        $blacklists = $this->isBlacklisted($recipient, $lists);

        if ($blacklists)
        {
            if ($options & static::OPT_IGNORE_BLACKLIST)
            {
                foreach ($blacklists as $blacklist)
                {
                    $entityManager->remove($blacklist);
                }
            }
            else
            {
                foreach ($blacklists as $blacklist)
                {
                    if ($blacklist->getList() == static::BLACKLIST_GLOBAL)
                    {
                        // break on global blacklist
                        return;
                    }
                    else
                    {
                        $pos = array_search($blacklist->getList(), $lists);
                        unset($lists[$pos]);
                    }
                }
            }
        }

        $subscriptions = array();
        foreach ($lists as $list)
        {           
            $subscription = $subscriptionRepository->findOneBy(
                    array(
                        'member' => $recipient->id,
                        'list'      => $list
                    )
            );

            if (!$subscription)
            {
                $subscription = new MemberSubscription();
                $subscription->setMember($recipient->id);
                $subscription->setList($list);
                $subscription->setConfirmed($options & static::OPT_ACTIVATE);
                $subscription->setToken(
                        substr(md5($recipient->email . '|' . mt_rand() . '|' . microtime(true)), 0, 16)
                );
                
                $entityManager->persist($subscription);

//                $eventDispatcher->dispatch(
//                        'avisota-recipient-subscribe', new SubscribeEvent($recipient, $subscription)
//                );
            }

            if (!$subscription->getConfirmed())
            {
                $subscriptions[] = $subscription;
            }
        }
        
        $entityManager->flush();         

        if ($options ^ static::OPT_NO_CONFIRMATION)
        {
            // TODO send confirmations
        }

        return $subscriptions;
    }

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
    public function confirm($recipient, array $token)
    {
        $entityManager = EntityHelper::getEntityManager();

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $recipient = $this->resolveRecipient($recipient);

        if (!$recipient || empty($token))
        {
            return false;
        }

        $queryBuilder  = $entityManager->createQueryBuilder();
        $query         = $queryBuilder
                ->select('s')
                ->from('Avisota\Contao:MemberSubscription', 's')
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

        /** @var MemberSubscription $subscription */
        foreach ($subscriptions as $subscription)
        {
            $subscription->setConfirmed(true);
            $subscription->setConfirmedAt(new \DateTime());
            $entityManager->persist($subscription);

            $eventDispatcher->dispatch(
                    ConfirmSubscriptionEvent::NAME, new ConfirmSubscriptionEvent($recipient, $subscription)
            );
        }

        $entityManager->flush();

        return $subscriptions;
    }

    /**
     * Remove subscription.
     *
     * @param string|array $recipient Recipient email address or array of recipient details.
     * @param null|array   $lists     <em>null</em> to globally unsubscribe from all mailing lists, or array of mailing lists to unsubscribe from.
     */
    public function unsubscribe($recipient, $lists = null, $options = 0)
    {
        $entityManager          = EntityHelper::getEntityManager();
        $subscriptionRepository = $entityManager->getRepository('Avisota\Contao:MemberSubscription');
        $blacklistRepository    = $entityManager->getRepository('Avisota\Contao:RecipientBlacklist');

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher        = $GLOBALS['container']['event-dispatcher'];

        if ($options & static::OPT_UNSUBSCRIBE_GLOBAL)
        {
            $listRepository = $entityManager->getRepository('Avisota\Contao:MailingList');
            $lists          = $listRepository->findAll();
        }

        $recipient = $this->resolveRecipient($recipient);
        $lists     = $this->resolveLists($lists, false);

        if (!$recipient)
        {
            return false;
        }

        $subscriptions = array();

        foreach ($lists as $list)
        {            

            $subscription = $subscriptionRepository->findOneBy(
                    array(
                        'member' => $recipient->id,
                        'list'   => $list


                    )
            );

            if ($subscription)
            {
                if ($options ^ static::OPT_NO_BLACKLIST)
                {
                    $blacklist = $blacklistRepository->findOneBy(
                            array(
                                'email' => md5(strtolower($recipient->email)),
                                'list'  => $list
                            )
                    );

                    if (!$blacklist)
                    {
                        $blacklist = new RecipientBlacklist();
                        $blacklist->setEmail(md5(strtolower($recipient->email)));
                        $blacklist->setList($list);
                        $entityManager->persist($blacklist);
                    }
                }

                $entityManager->remove($subscription);

                $subscriptions[] = $subscription;

//                    $eventDispatcher->dispatch(
//                            'avisota-recipient-unsubscribe', new UnsubscribeEvent($recipient, $subscription, $blacklist)
//                    );

            }
        }

        $entityManager->flush();
        

        $remainingSubscriptions = $subscriptionRepository
                ->findBy(array('member' => $recipient->id));

        if (!$remainingSubscriptions || !count($remainingSubscriptions))
        {
//                $eventDispatcher->dispatch(RemoveRecipientEvent::NAME, new RemoveRecipientEvent($recipient));
//            $entityManager->remove($recipient);
//            $entityManager->flush();
        }

        if ($options ^ static::OPT_NO_CONFIRMATION)
        {
            // TODO send confirmations
        }

        return $subscriptions;
    }

    /**
     * Clear the lists, cause we get a array with a deserelized array :| funny.
     * 
     * @param mixed $mixList
     * 
     * @return array
     */
    protected function clearList($mixList)
    {
        $arrReturn = array();

        if (is_array($mixList))
        {
            foreach ($mixList as $key => $value)
            {
                $arrReturn = array_merge($arrReturn, $this->clearList($value));
            }
        }
        else
        {
            $arrReturn = (array) deserialize($mixList, true);
        }

        return $arrReturn;
    }

	/**
	 * Check if this subscription manager can handle the recipient.
	 *
	 * @param $recipient
	 *
	 * @return bool
	 */
	public function canHandle($recipient)
	{
		if ($recipient instanceof \Database_Result) {
			// TODO
		}
		else if ($recipient instanceof \MemberModel) {
			return true;
		}
		else if ($recipient instanceof Collection && $recipient->current() instanceof \MemberModel) {
			return true;
		}

		return false;
	}
}