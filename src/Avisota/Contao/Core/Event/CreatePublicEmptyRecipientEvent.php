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
use Avisota\Recipient\RecipientInterface;
use Symfony\Component\EventDispatcher\Event;

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
