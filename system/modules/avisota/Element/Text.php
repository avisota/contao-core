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


namespace Avisota\Contao\Message\Element;

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
class Text implements ElementInterface
{
	/**
	 * @var string
	 */
	const TEMPLATE = 'mce_text';

	/**
	 * Parse and generate the element.
	 *
	 * @param MessageContent $messageContent
	 *
	 * @return string
	 */
	public function generate($mode, MessageContent $messageContent, RecipientInterface $recipient = null)
	{
		$space   = deserialize($messageContent->getSpace());
		$cssID   = deserialize($messageContent->getCssID(), true);

		$headline    = deserialize($messageContent->getHeadline());
		$hl       = is_array($headline) ? $headline['unit'] : 'h1';
		$headline = is_array($headline) ? $headline['value'] : $headline;

		$string = \String::getInstance();

		$context = $messageContent->toArray(Entity::REF_INCLUDE);

		switch ($mode) {
			case Renderer::MODE_HTML:
				// Clean RTE output
				$context['text'] = $string->encodeEmail($context['text']);
				break;

			case Renderer::MODE_PLAIN:
				if (!$context['plain']) {
					$context['plain'] = $this->getPlainFromHTML($context['plain']);
				}
		}

		$template = new \TwigTemplate(static::TEMPLATE, $mode);
		return $template->parse($context);
	}
}
