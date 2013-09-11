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

		$queueHelper = new \Avisota\Queue\QueueHelper();
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
				'id'   => $message->getId(),
				'turn' => $turn + 1,
			);
			$url = sprintf(
				'Location: %ssystem/modules/avisota/web/send_immediate.php?%s',
				$environment->base,
				http_build_query($parameters)
			);
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
}

$send_immediate = new send_immediate();
$send_immediate->run();
