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

namespace Avisota\Contao\Core\Send;

use Avisota\Contao\Entity\Message;

class PreviewModule implements SendModuleInterface
{
	public function run(Message $message)
	{
		$template = new \TwigTemplate('avisota/send/preview', 'html5');
		return $template->parse(array('message' => $message));
	}
}
