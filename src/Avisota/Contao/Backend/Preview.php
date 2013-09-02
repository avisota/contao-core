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
use DcGeneral\DC_General;

class Preview extends \Controller
{
	/**
	 * @param DC_General $dc
	 */
	public function sendMessage(DC_General $dc)
	{
		$this->loadLanguageFile('avisota_message_preview');
		$this->loadLanguageFile('orm_avisota_message');

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

		$modules = new \StringBuilder();
		/** @var \Avisota\Contao\Send\SendModuleInterface $module */
		foreach ($GLOBALS['AVISOTA_SEND_MODULE'] as $className) {
			$class = new \ReflectionClass($className);
			$module = $class->newInstance();
			$modules->append($module->run($message));
		}

		$context = array(
			'message' => $message,
			'modules' => $modules,
		);

		$template = new \TwigTemplate('avisota/backend/preview', 'html5');
		return $template->parse($context);
	}
}
