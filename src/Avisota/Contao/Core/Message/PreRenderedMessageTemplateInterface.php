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

namespace Avisota\Contao\Core\Message;


use Avisota\Recipient\RecipientInterface;
use Avisota\Templating\MessageTemplateInterface;

/**
 * A template to generate a message from.
 *
 * @package avisota-core
 */
interface PreRenderedMessageTemplateInterface extends MessageTemplateInterface
{
    /**
     * Return the content type.
     *
     * @return string
     */
    public function getContentType();

    /**
     * Return the content encoding.
     *
     * @return string
     */
    public function getContentEncoding();

    /**
     * Get a name descriptor (file name) for this content,
     * e.g. "newsletter-hello-world.html".
     *
     * @return string
     */
    public function getContentName();

    /**
     * Get the (binary) content.
     *
     * @return string
     */
    public function getContent();

    /**
     * Render a preview.
     *
     * @param RecipientInterface $recipient
     *
     * @param array              $additionalData
     *
     * @return mixed The content only, not a message.
     */
    public function renderPreview(RecipientInterface $recipient, array $additionalData = array());
}
