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
use Avisota\Recipient\RecipientInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * The base create recipient event.
 */
class BaseCreateRecipientEvent extends Event
{
    /**
     * The message.
     *
     * @var Message|null
     */
    protected $message;

    /**
     * The recipient.
     *
     * @var RecipientInterface
     */
    protected $recipient;

    /**
     * CreateFakeRecipientEvent constructor.
     *
     * @param Message|null $message
     */
    public function __construct(Message $message = null)
    {
        $this->message = $message;
    }

    /**
     * Get the message.
     *
     * @return Message|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the recipient.
     *
     * @return RecipientInterface
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set the recipient.
     *
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
