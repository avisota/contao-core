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

class SubscribeEvent extends Event
{
	protected $recipient;

	protected $subscription;

	function __construct(Recipient $recipient, $subscription)
	{
		$this->recipient = $recipient;
		$this->list = $subscription;
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
}