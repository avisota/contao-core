<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Event;

use Avisota\Contao\Entity\Message;
use Symfony\Component\EventDispatcher\Event;

/**
 * The base send immediate event.
 */
class BaseSendImmediateEvent extends Event
{
    /**
     * The message.
     *
     * @var Message
     */
    protected $message;

    /**
     * The turn step.
     *
     * @var integer
     */
    protected $turn;

    /**
     * The unique loop id.
     *
     * @var string
     */
    protected $loop;

    /**
     * BaseSendImmediateEvent constructor.
     *
     * @param Message $message The message.
     * @param integer $turn    The turn step.
     * @param string  $loop    The unique loop id.
     *
     * Fixme who message isn´t instance of Message
     */
    public function __construct($message, $turn, $loop)
    {
        $this->message = $message;
        $this->turn    = $turn;
        $this->loop    = $loop;
    }

    /**
     * Get the message.
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the turn step.
     *
     * @return integer
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * Get the loop unique id.
     *
     * @return string
     */
    public function getLoop()
    {
        return $this->loop;
    }
}
