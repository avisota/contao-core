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
class RootSubscriptionManager extends SubscriptionManagerChain
{
	/**
	 * Resolve a list of mailing lists into an identifier list.
	 *
	 * @return array
	 */
	public function resolveLists($lists, $includeGlobal = false)
	{
		$lists = (array) $lists;

		if ($includeGlobal && !in_array(static::BLACKLIST_GLOBAL, $lists)) {
			$lists[] = static::BLACKLIST_GLOBAL;
		}

		$lists = array_map(
			function ($list) {
				if (is_numeric($list)) {
					return 'mailing_list:' . $list;
				}
				else if ($list instanceof MailingList) {
					return 'mailing_list:' . $list->getId();
				}
				return $list;
			},
			$lists
		);

		return array_values($lists);
	}
}