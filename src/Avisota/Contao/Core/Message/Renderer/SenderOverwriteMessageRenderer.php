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

namespace Avisota\Contao\Core\Message\Renderer;

use Avisota\Message\MessageInterface;
use Avisota\Renderer\DelegateMessageRenderer;
use Avisota\Renderer\MessageRendererInterface;

/**
 * Class DelegateMessageRenderer
 *
 * Implementation of a delegate message renderer.
 * Primary used as base class for custom implementations.
 */
class SenderOverwriteMessageRenderer extends DelegateMessageRenderer
{
    /**
     * @var string
     */
    protected $sender;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * DelegateMessageRenderer constructor.
     *
     * @param MessageRendererInterface $delegate
     * @param                          $sender
     * @param                          $senderName
     */
    function __construct(MessageRendererInterface $delegate, $sender, $senderName)
    {
        parent::__construct($delegate);
        $this->sender     = (string) $sender;
        $this->senderName = (string) $senderName;
    }

    /**
     * @param string $sender
     *
     * @return $this
     */
    public function setSender($sender)
    {
        $this->sender = (string) $sender;
        return $this;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $senderName
     *
     * @return $this
     */
    public function setSenderName($senderName)
    {
        $this->senderName = (string) $senderName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * Render a message and create a Swift_Message.
     *
     * @param MessageInterface $message
     *
     * @return \Swift_Message
     */
    public function renderMessage(MessageInterface $message)
    {
        $swiftMessage = $this->delegate->renderMessage($message);

        $swiftMessage->setSender($this->sender, $this->senderName);

        return $swiftMessage;
    }
}
