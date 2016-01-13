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
 * {@inheritDoc}
 */

/**
 * Class FromOverwriteMessageRenderer
 *
 * @package Avisota\Contao\Core\Message\Renderer
 */
class FromOverwriteMessageRenderer extends DelegateMessageRenderer
{
    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $fromName;

    /**
     * {@inheritDoc}
     */
    /**
     * FromOverwriteMessageRenderer constructor.
     *
     * @param MessageRendererInterface $delegate
     * @param                          $from
     * @param                          $fromName
     */
    public function __construct(MessageRendererInterface $delegate, $from, $fromName)
    {
        parent::__construct($delegate);
        $this->from     = (string) $from;
        $this->fromName = (string) $fromName;
    }

    /**
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
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
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
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
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

        $swiftMessage->setFrom($this->from, $this->fromName);

        return $swiftMessage;
    }
}
