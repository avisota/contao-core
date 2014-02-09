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

abstract class AbstractWebRunner extends \Backend
{
	function __construct()
	{
		parent::__construct();
	}

	public function run()
	{
		$input = \Input::getInstance();
		$messageRepository = \Contao\Doctrine\ORM\EntityHelper::getRepository('Avisota\Contao:Message');

		$messageId = $input->get('id');
		$message = $messageRepository->find($messageId);
		/** @var \Avisota\Contao\Entity\Message $message */

		if (!$message) {
			header("HTTP/1.0 404 Not Found");
			echo '<h1>404 Not Found</h1>';
			exit;
		}

		$user = \BackendUser::getInstance();
		$user->authenticate();

		$this->execute($message, $user);
	}

	abstract protected function execute(Message $message, \BackendUser $user);
}
