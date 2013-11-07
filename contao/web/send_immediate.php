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

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Entity\MessageHistory;
use Avisota\Contao\Entity\MessageHistoryDetails;
use Contao\Doctrine\ORM\EntityHelper;

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

class send_immediate extends \Avisota\Contao\Send\AbstractWebRunner
{
	protected function execute(Message $message, \BackendUser $user)
	{
		global $container;

		$input = \Input::getInstance();

		$entityManager		= EntityHelper::getEntityManager();
		$historyRepository	= $entityManager->getRepository('Avisota\Contao\Entity\MessageHistory');

		$queueData   = $message->getQueue();
		$serviceName = sprintf('avisota.queue.%s', $queueData->getId());
		$queue       = $container[$serviceName];

		$recipientSourceData = $message->getRecipients();
		$serviceName         = sprintf('avisota.recipientSource.%s', $recipientSourceData->getId());

		/** @var RecipientSourceInterface $recipientSource */
		$recipientSource = $container[$serviceName];

		/** @var \Avisota\Contao\Message\Renderer\MessagePreRendererInterface $renderer */
		$renderer        = $container['avisota.renderer'];
		$messageTemplate = $renderer->renderMessage($message);

		$environment = Environment::getInstance();
		$url         = sprintf(
			'%scontao/main.php?do=avisota_newsletter&table=orm_avisota_message&key=send&id=%s',
			$environment->base,
			$message->getId()
		);
		// TODO fix view online link
		$additionalData = array('view_online_link' => $url);

		$turn = $input->get('turn');
		if (!$turn) {
			$turn = 0;
		}

		// get the history
		$history = $input->get('history');
		if ($history) {
			$history = $historyRepository->findOneBy(array('id' => $history));
		}

		//create a new history if none was found
		if (!$history) {
			$history = new MessageHistory();
			$history->setId($this->createUUID());
			$history->setMessage($message);

			$entityManager->persist($history);
		}

		$queueHelper = new \Avisota\Queue\QueueHelper();
		$queueHelper->setEventDispatcher($GLOBALS['container']['event-dispatcher']);
		$queueHelper->setQueue($queue);
		$queueHelper->setRecipientSource($recipientSource);
		$queueHelper->setMessageTemplate($messageTemplate);
		$queueHelper->setNewsletterData($additionalData);

		$count = $queueHelper->enqueue(30, $turn * 30);

		if ($count) {
			$this->loadLanguageFile('avisota_message_preview');

			$_SESSION['TL_CONFIRM'][] = sprintf(
				$GLOBALS['TL_LANG']['avisota_message_preview']['messagesEnqueued'],
				$count,
				$turn + 1
			);

			$parameters = array(
				'id' 		=> $message->getId(),
				'turn'		=> $turn + 1,
				'history'	=> $history->getId(),
			);
			$url = sprintf(
				'%ssystem/modules/avisota/web/send_immediate.php?%s',
				$environment->base,
				http_build_query($parameters)
			);

			$history->setMailCount($count);

			$entityManager->flush();
			//ToDo: this is just a hotfix to replace the browser redirect which will break after 10-20 redirects
			//I know this is ugly but works until we find a better solution
			echo '<html><head><meta http-equiv="refresh" content="2; URL=' . $url .'"></head><body>Still generating...</body></html>';
		}
		else {
			$parameters = array(
				'do'      => 'avisota_outbox',
				'execute' => $queueData->getId(),
			);
			$url = sprintf(
				'Location: %scontao/main.php?%s',
				$environment->base,
				http_build_query($parameters)
			);
		}

		header($url);
		exit;
	}

	function createUUID() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		  mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
		  mt_rand(0, 0x0fff) | 0x4000,
		  mt_rand(0, 0x3fff) | 0x8000,
		  mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
	}
}

$send_immediate = new send_immediate();
$send_immediate->run();
