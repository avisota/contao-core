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

namespace Avisota\Contao\Event;

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Entity\RecipientSubscription;
use Symfony\Component\EventDispatcher\Event;

class ResolveSubscriptionNameEvent extends Event
{
	const NAME = 'Avisota\Contao\Event\ResolveSubscriptionName';

	/**
	 * @var RecipientSubscription
	 */
	protected $subscription;

	/**
	 * @var string
	 */
	protected $subscriptionName;

	function __construct(RecipientSubscription $subscription)
	{
		$this->subscription     = $subscription;
		$this->subscriptionName = $subscription->getList();
	}

	/**
	 * @return RecipientSubscription
	 */
	public function getSubscription()
	{
		return $this->subscription;
	}

	/**
	 * @param string $name
	 */
	public function setSubscriptionName($name)
	{
		$this->subscriptionName = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSubscriptionName()
	{
		return $this->subscriptionName;
	}
}