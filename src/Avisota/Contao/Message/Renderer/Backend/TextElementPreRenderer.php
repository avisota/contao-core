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


namespace Avisota\Contao\Message\Renderer\Backend;

use Avisota\Contao\Entity\MessageContent;
use Avisota\Contao\Message\Renderer;
use Avisota\Recipient\RecipientInterface;
use Contao\Doctrine\ORM\Entity;


/**
 * Class Text
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class TextElementPreRenderer implements Renderer\MessageContentPreRendererInterface
{
	/**
	 * @var string
	 */
	const TEMPLATE = 'avisota/message/renderer/backend/mce_text';

	/**
	 * Render a single message content element.
	 *
	 * @param MessageContent     $content
	 * @param RecipientInterface $recipient
	 *
	 * @return string
	 */
	public function renderContent(MessageContent $content)
	{
		$context = $content->toArray(Entity::REF_INCLUDE);
		$template = new \TwigTemplate(static::TEMPLATE, 'html');
		return $template->parse($context);
	}

	/**
	 * Check if this renderer can render the given message content element.
	 *
	 * @param MessageContent     $content
	 * @param RecipientInterface $recipient
	 *
	 * @return bool
	 */
	public function canRenderContent(MessageContent $content)
	{
		return $content->getType() == 'text';
	}
}
