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
 * @license    LGPL
 * @filesource
 */

namespace Avisota\Contao\Event;

use Avisota\Contao\Entity\Recipient;
use Symfony\Component\EventDispatcher\Event;

class UnsubscribeEvent extends Event
{
	protected $recipient;

	protected $subscription;

	protected $blacklist;

	function __construct(Recipient $recipient, $subscription, $blacklist = null)
	{
		$this->recipient = $recipient;
		$this->list = $subscription;
		$this->blacklist = $blacklist;
	}

	/**
	 * @return Recipient
	 */
	public function getRecipient()
	{
		return $this->recipient;
	}

	/**
	 * @return string
	 */
	public function getSubscription()
	{
		return $this->list;
	}

	/**
	 * @return null
	 */
	public function getBlacklist()
	{
		return $this->blacklist;
	}
}