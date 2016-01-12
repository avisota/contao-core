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

class CreateFakeRecipientEvent extends Event
{
    /**
     * @var Message|null
     */
    protected $message;

    /**
     * @var RecipientInterface
     */
    protected $recipient;

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
     */
    public function setRecipient(RecipientInterface $recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }
}
