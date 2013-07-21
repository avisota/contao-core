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


namespace Avisota\Contao\Message\Element;

use Avisota\Contao\Entity\MessageContent;
use Avisota\Recipient\RecipientInterface;

interface ElementInterface
{
	/**
	 * Parse and generate the element.
	 *
	 * @param string             $mode One of Renderer::MODE_* constants.
	 * @param MessageContent     $messageContent
	 * @param RecipientInterface $recipient
	 *
	 * @return string
	 */
	public function generate($mode, MessageContent $messageContent, RecipientInterface $recipient = null);
}
