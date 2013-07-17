<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL
 * @filesource
 */

namespace Avisota\Contao\Message\Renderer;

use Avisota\Contao\Entity\MessageContent;
use Avisota\Recipient\RecipientInterface;

interface ChainableMessageContentRendererInterface extends MessageContentRendererInterface
{
    public function canRender(MessageContent $content, RecipientInterface $recipient = null);
}
