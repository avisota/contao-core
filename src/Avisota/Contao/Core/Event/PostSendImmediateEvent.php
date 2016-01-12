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
class PostSendImmediateEvent extends Event
{
	/**
	 * @var int
	 */
	protected $count;

	/**
	 * @var Message
	 */
	protected $message;

	/**
	 * @var int
	 */
	protected $turn;

	/**
	 * @var string
	 */
	protected $loop;

	/**
	 * PostSendImmediateEvent constructor.
	 *
	 * @param         $count
	 * @param Message $message
	 * @param         $turn
	 * @param         $loop
     */
    function __construct($count, Message $message, $turn, $loop)
	{
		$this->count   = $count;
		$this->message = $message;
		$this->turn    = $turn;
		$this->loop    = $loop;
	}

	/**
	 * @return int
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 * @return Message
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @return int
	 */
	public function getTurn()
	{
		return $this->turn;
	}

	/**
	 * @return mixed
	 */
	public function getLoop()
	{
		return $this->loop;
	}
}
