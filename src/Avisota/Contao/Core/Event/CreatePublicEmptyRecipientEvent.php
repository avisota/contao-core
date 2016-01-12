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

use Avisota\Contao\Entity\Message;
use Avisota\Recipient\RecipientInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event is the base class for classes containing event data.
 *
 * This class contains no event data. It is used by events that do not pass
 * state information to an event handler when an event is raised.
 *
 * You can call the method stopPropagation() to abort the execution of
 * further listeners in your event listener.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CreatePublicEmptyRecipientEvent extends Event
{
	/**
	 * @var Message|null
	 */
	protected $message;

	/**
	 * @var RecipientInterface
	 */
	protected $recipient;

	/**
	 * CreatePublicEmptyRecipientEvent constructor.
	 *
	 * @param Message|null $message
     */
    function __construct(Message $message = null)
	{
		$this->message = $message;
	}

	/**
	 * @return Message|null
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @return RecipientInterface
	 */
	public function getRecipient()
	{
		return $this->recipient;
	}

	/**
	 * @param RecipientInterface $recipient
	 *
	 * @return $this
	 */
	public function setRecipient(RecipientInterface $recipient)
	{
		$this->recipient = $recipient;
		return $this;
	}
}
