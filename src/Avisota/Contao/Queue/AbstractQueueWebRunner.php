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

namespace Avisota\Contao\Queue;

use Avisota\Contao\Entity\Queue;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractQueueWebRunner extends \Backend
{
	function __construct()
	{
		parent::__construct();
	}

	public function run(Request $request)
	{
		$queueRepository = \Contao\Doctrine\ORM\EntityHelper::getRepository('Avisota\Contao:Queue');

		$queueId = $request->get('id');
		$queue   = $queueRepository->find($queueId);
		/** @var \Avisota\Contao\Entity\Queue $queue */

		if (!$queue) {
			header("HTTP/1.0 404 Not Found");
			echo '<h1>404 Not Found</h1>';
			exit;
		}

		$user = \BackendUser::getInstance();
		$user->authenticate();

		try {
			return $this->execute($request, $queue, $user);
		}
		catch (\Exception $exception) {
			$response = new JsonResponse(
				array(
					'error' => sprintf(
						'%s in %s:%d',
						$exception->getMessage(),
						$exception->getFile(),
						$exception->getLine()
					)
				),
				500
			);
			$response->prepare($request);
			return $response;
		}
	}

	abstract protected function execute(Request $request, Queue $messageData, \BackendUser $user);
}
