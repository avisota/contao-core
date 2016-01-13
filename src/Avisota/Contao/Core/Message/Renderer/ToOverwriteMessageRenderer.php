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
class ToOverwriteMessageRenderer extends DelegateMessageRenderer
{
    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $toName;

    /**
     * DelegateMessageRenderer constructor.
     *
     * @param MessageRendererInterface $delegate
     * @param                          $to
     * @param                          $toName
     */
    function __construct(MessageRendererInterface $delegate, $to, $toName)
    {
        parent::__construct($delegate);
        $this->to     = (string) $to;
        $this->toName = (string) $toName;
    }

    /**
     * @param string $replyTo
     *
     * @return $this
     */
    public function setTo($replyTo)
    {
        $this->to = (string) $replyTo;
        return $this;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $replyToName
     *
     * @return $this
     */
    public function setToName($replyToName)
    {
        $this->toName = (string) $replyToName;
        return $this;
    }

    /**
     * @return string
     */
    public function getToName()
    {
        return $this->toName;
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

        $swiftMessage->setTo($this->to, $this->toName);

        return $swiftMessage;
    }
}
