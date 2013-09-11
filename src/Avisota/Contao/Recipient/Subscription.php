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

namespace Avisota\Contao\Recipient;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Event\CollectSubscriptionListsEvent;
use Avisota\Contao\Event\ResolveSubscriptionNameEvent;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\DBAL\Driver\PDOStatement;

class Subscription extends \Controller
{
	static public function resolveSubscriptionName(ResolveSubscriptionNameEvent $event)
	{
		if ($event->getSubscriptionName() == 'global') {
			$subscription = new Subscription();
			$subscription->loadLanguageFile('orm_avisota_recipient');

			$event->setSubscriptionName(
				$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscription_global']
			);
		}
		else if (preg_match('#^mailing_list:(.*)$#', $event->getSubscriptionName(), $matches)) {
			$subscription = new Subscription();
			$subscription->loadLanguageFile('orm_avisota_recipient');

			$mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
			/** @var MailingList $mailingList */
			$mailingList = $mailingListRepository->find($matches[1]);
			if ($mailingList) {
				$event->setSubscriptionName(
					sprintf(
						$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscription_mailingList'],
						$mailingList->getTitle()
					)
				);
			}
		}
	}

	static public function collectSubscriptionLists(CollectSubscriptionListsEvent $event)
	{
		$mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
		/** @var MailingList[] $mailingLists */
		$mailingLists = $mailingListRepository->findAll();

		$mailingListOptions = array();
		foreach ($mailingLists as $mailingList) {
			$mailingListOptions['mailing_list:' . $mailingList->id()] = $mailingList->getTitle();
		}

		$options = $event->getOptions();
		$options['mailing_list'] = $mailingListOptions;
	}
}
