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
use Avisota\Contao\Entity\MemberSubscription;
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
		if ($recipientClass != 'Avisota\Contao:Member')
		{
			return false;
		}

        // new recipient
        if (is_array($recipientIdentity))
        {
            // ToDo: Add multi member support.
        }

        // by id
        else if (is_numeric($recipientIdentity))
        {
			$recipient = \Database::getInstance()
            ->prepare('SELECT * FROM `tl_member` WHERE id = ?')
            ->limit(1)
            ->execute($recipientIdentity);

            if ($recipient->numRows == 0)
            {
                throw new \RuntimeException('Found no recipient.');
            }
            
            return $recipient;
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
                throw new \RuntimeException('Found no recipient.');
            }
            
            return $recipient;
        }

        throw new \RuntimeException('Found no recipient.');
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
		global $container;
		$entityManager          = EntityHelper::getEntityManager();
        $subscriptionRepository = $entityManager->getRepository('Avisota\Contao:MemberSubscription');
        
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $recipient  = $this->resolveRecipient('Avisota\Contao:Member', $recipient, false);
        $lists      = $container['avisota.subscription']->resolveLists($this->clearList($lists), true);
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
	 * {@inheritdoc}
	 */
	public function confirm(
		$recipient,
		array $token
	) {
		$entityManager = EntityHelper::getEntityManager();

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $recipient = $this->resolveRecipient('Avisota\Contao:Member', $recipient);

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
	 * {@inheritdoc}
	 */
	public function unsubscribe($recipient, $lists = null, $options = 0)
	{
		global $container;
		
		$entityManager          = EntityHelper::getEntityManager();
        $subscriptionRepository = $entityManager->getRepository('Avisota\Contao:MemberSubscription');
		//ToDo: No Blacklistst for members yet
        //$blacklistRepository    = $entityManager->getRepository('Avisota\Contao:RecipientBlacklist');

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher        = $GLOBALS['container']['event-dispatcher'];

        if ($options & static::OPT_UNSUBSCRIBE_GLOBAL)
        {
            $listRepository = $entityManager->getRepository('Avisota\Contao:MailingList');
            $lists          = $listRepository->findAll();
        }

        $recipient = $this->resolveRecipient('Avisota\Contao:Member', $recipient);
        $lists     = $container['avisota.subscription']->resolveLists($lists, false);

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
					//ToDo: no Member blacklists yet
                    /*$blacklist = $blacklistRepository->findOneBy(
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
                    }*/
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
	 * {@inheritdoc}
	 */
	public function canHandle($recipient)
	{
		return is_string($recipient);
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
}