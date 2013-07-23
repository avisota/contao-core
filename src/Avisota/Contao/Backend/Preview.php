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

namespace Avisota\Contao\Backend;

use Avisota\Contao\Message\Renderer;
use Contao\Doctrine\ORM\EntityHelper;

class Preview extends \Controller
{
	/**
	 * @param \DC_General $dc
	 * @param string      $table
	 */
	public function sendMessage($dc, $table)
	{
		$input             = \Input::getInstance();
		$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');

		$messageId = $input->get('id');
		$message   = $messageRepository->find($messageId);

		if (!$message) {
			$environment = \Environment::getInstance();
			$this->redirect(
				preg_replace(
					'#&(key=send|id=[a-f0-9\-]+)#',
					'',
					$environment->request
				)
			);
		}

		$this->loadLanguageFile('avisota_message_preview');
		$this->loadLanguageFile('orm_avisota_message');

		$database = \Database::getInstance();
		$users    = $database
			->query('SELECT * FROM tl_user ORDER BY name')
			->fetchAllAssoc();

		$context = array(
			'message' => $message,
			'referer' => $this->getReferer(true),
			'users'   => $users,
		);

		$template = new \TwigTemplate('avisota/backend/preview', 'html5');
		return $template->parse($context);
	}
}
