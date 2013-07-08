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

class RecipientEvent extends Event
{
	protected $recipient;

	function __construct(Recipient $recipient)
	{
		$this->recipient = $recipient;
	}

	/**
	 * @return Recipient
	 */
	public function getRecipient()
	{
		return $this->recipient;
	}
}