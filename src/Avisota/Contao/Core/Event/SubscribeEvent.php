<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Event;

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Entity\RecipientSubscription;
use Symfony\Component\EventDispatcher\Event;

class SubscribeEvent extends Event
{
	const NAME = 'Avisota\Contao\Core\Event\Subscribe';

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
	 * @return RecipientSubscription
	 */
	public function getSubscription()
	{
		return $this->list;
	}
}