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
 * The from overwrite message render.
 */
class FromOverwriteMessageRenderer extends DelegateMessageRenderer
{
    /**
     * The from address.
     *
     * @var string
     */
    protected $from;

    /**
     * The from name.
     *
     * @var string
     */
    protected $fromName;

    /**
     * FromOverwriteMessageRenderer constructor.
     *
     * @param MessageRendererInterface $delegate The delegate message renderer.
     * @param string                   $from     The from address.
     * @param string                   $fromName The from name.
     */
    public function __construct(MessageRendererInterface $delegate, $from, $fromName)
    {
        parent::__construct($delegate);
        $this->from     = (string) $from;
        $this->fromName = (string) $fromName;
    }

    /**
     * Set the from address.
     *
     * @param string $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = (string) $from;
        return $this;
    }

    /**
     * Get the from address.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set the from name.
     *
     * @param string $fromName
     *
     * @return $this
     */
    public function setFromName($fromName)
    {
        $this->fromName = (string) $fromName;
        return $this;
    }

    /**
     * Get the from name.
     *
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Render a message and create a Swift_Message.
     *
     * @param MessageInterface $message The message.
     *
     * @return \Swift_Message
     */
    public function renderMessage(MessageInterface $message)
    {
        $swiftMessage = $this->delegate->renderMessage($message);

        $swiftMessage->setFrom($this->from, $this->fromName);

        return $swiftMessage;
    }
}
