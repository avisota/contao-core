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
use Avisota\Contao\Core\Message\Renderer;
use Avisota\Recipient\RecipientInterface;


/**
 * Class Text
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
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
