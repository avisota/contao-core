<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


namespace Avisota\Contao\Core\Message\Element;

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
