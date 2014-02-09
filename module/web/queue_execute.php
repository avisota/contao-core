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

use Avisota\Contao\Entity\Queue;
use Avisota\Contao\Core\Event\PreQueueExecuteEvent;
use Avisota\Contao\Core\Queue\AbstractQueueWebRunner;
use Avisota\Queue\ExecutionConfig;
use Symfony\Component\HttpFoundation\JsonResponse;

$dir = dirname(isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : __FILE__);

while ($dir && $dir != '.' && $dir != '/' && !is_file($dir . '/system/initialize.php')) {
	$dir = dirname($dir);

}

if (!is_file($dir . '/system/initialize.php')) {
	header("HTTP/1.0 500 Internal Server Error");
	header('Content-Type: text/html; charset=utf-8');
	echo '<h1>500 Internal Server Error</h1>';
	echo '<p>Could not find initialize.php!</p>';
	exit(1);
}

define('TL_MODE', 'FE');
require($dir . '/system/initialize.php');

class qeueue_execute extends AbstractQueueWebRunner
{
	protected function execute(Request $request, Queue $queueData, \BackendUser $user)
	{
		global $container;

		if (!$queueData->getAllowManualSending()) {
			$response = new JsonResponse(array('error' => 'manual sending is forbidden'), 403);
			$response->prepare($request);
			return $response;
		}

			$serviceName = sprintf('avisota.queue.%s', $queueData->getId());
			/** @var QueueInterface $queue */
			$queue = $container[$serviceName];

		$transportServiceName = sprintf(
			'avisota.transport.%s',
			$queueData
				->getTransport()
				->getId()
		);
		$transport            = $container[$transportServiceName];

		$config = new ExecutionConfig();
		if ($queueData->getMaxSendTime() > 0) {
			$config->setTimeLimit($queueData->getMaxSendTime());
		}
		if ($queueData->getMaxSendCount() > 0) {
			$config->setMessageLimit($queueData->getMaxSendCount());
		}

		$event = new PreQueueExecuteEvent($queue, $transport, $config);
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];
		$eventDispatcher->dispatch(PreQueueExecuteEvent::NAME, $event);

		$queue     = $event->getQueue();
		$transport = $event->getTransport();
		$config    = $event->getConfig();

		$status = $queue->execute($transport, $config);

		$jsonData = array(
			'success' => 0,
			'failed'  => 0,
		);
		foreach ($status as $stat) {
			$jsonData['success'] += $stat->getSuccessfullySend();
			$jsonData['failed']  += count($stat->getFailedRecipients());
		}

		$response = new JsonResponse($jsonData);
		$response->prepare($request);
		return $response;
	}
}

sleep(rand(5, 25));
$request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
$qeueue_execute = new qeueue_execute();
$response = $qeueue_execute->run($request);
$response->send();
