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


namespace Avisota\Contao\Core\Message\Element;

use Avisota\Contao\Entity\MessageContent;
use Avisota\Contao\Core\Message\Renderer;
use Avisota\Recipient\RecipientInterface;


/**
 * Class Text
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 */
class ElementTemplate
{
	public function __construct(
		$template = '',
		$mode = ''
	) {
		parent::__construct($template, $contentType ? $contentType : 'text/' . $mode);
		$this->setFormat($mode);
	}
}
