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

namespace Avisota\Contao;

use Avisota\Contao\Message\Renderer;
use Contao\Doctrine\ORM\EntityHelper;

/**
 * Class Backend
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class Preview
{
	/**
	 * @param \DC_General $dc
	 * @param string $table
	 */
	public function sendMessage($dc, $table)
	{
		global $container;

		$input = \Input::getInstance();
		$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');

		$messageId = $input->get('id');
		$message = $messageRepository->find($messageId);

		$renderer = $container['avisota.renderer'];
		header('Content-Type: text/html; charset=utf-8');
		echo $renderer->renderMessage($message);
		exit;
	}
}
