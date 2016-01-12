<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Event;

use Avisota\Contao\Entity\Message;
use Symfony\Component\EventDispatcher\Event;

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
