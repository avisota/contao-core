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
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Message\Renderer;

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Message\PreRenderedMessageTemplateInterface;
use Avisota\Recipient\RecipientInterface;

interface MessagePreRendererInterface extends MessageContentPreRendererInterface
{
	/**
	 * Render a complete message.
	 *
	 * @param Message            $message
	 * @param RecipientInterface $recipient
	 *
	 * @return PreRenderedMessageTemplateInterface
	 */
	public function renderMessage(Message $message);

	/**
	 * Check if this renderer can render the given message.
	 *
	 * @param Message            $message
	 * @param RecipientInterface $recipient
	 *
	 * @return bool
	 */
	public function canRenderMessage(Message $message);
}
