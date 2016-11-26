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
 * The to overwrite message renderer.
 *
 * Implementation of a delegate message renderer.
 * Primary used as base class for custom implementations.
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class ToOverwriteMessageRenderer extends DelegateMessageRenderer
{
    /**
     * The to address.
     *
     * @var string
     */
    protected $to;

    /**
     * The to name.
     *
     * @var string
     */
    protected $toName;

    /**
     * DelegateMessageRenderer constructor.
     *
     * @param MessageRendererInterface $delegate The delegate message renderer.
     * @param string                   $to       The to address.
     * @param string                   $toName   The to name.
     */
    public function __construct(MessageRendererInterface $delegate, $to, $toName)
    {
        parent::__construct($delegate);
        $this->to     = (string) $to;
        $this->toName = (string) $toName;
    }

    /**
     * Set the to address.
     *
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
     * Get the to address.
     *
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set the to name.
     *
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
     * Get the to name.
     *
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
