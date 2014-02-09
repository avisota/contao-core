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

class send_preview_to_user extends \Avisota\Contao\Core\Send\AbstractWebRunner
{
	protected function execute(Message $message, \BackendUser $user)
	{
		global $container;

		$input = \Input::getInstance();
		$user  = $input->get('recipient_user');

		/** @var \Doctrine\DBAL\Connection $connection */
		$connection   = $container['doctrine.connection.default'];
		$queryBuilder = $connection->createQueryBuilder();
		/** @var \Doctrine\DBAL\Statement $statement */
		$statement = $queryBuilder
			->select('u.*')
			->from('tl_user', 'u')
			->where('id=:id')
			->setParameter(':id', $user)
			->execute();
		$data      = $statement->fetch();

		$environment = Environment::getInstance();
		$url         = sprintf(
			'%scontao/main.php?do=avisota_newsletter&table=orm_avisota_message&key=send&id=%s',
			$environment->base,
			$message->getId()
		);

		if (!$data) {
			$_SESSION['AVISOTA_SEND_PREVIEW_TO_USER_EMPTY'] = true;

			header('Location: ' . $url);
			exit;
		}

		$recipient = new \Avisota\Recipient\MutableRecipient($data['email'], $data);

		$additionalData = array('view_online_link' => $url);

		/** @var \Avisota\Contao\Core\Message\Renderer\MessagePreRendererInterface $renderer */
		$renderer        = $container['avisota.renderer'];
		$messageTemplate = $renderer->renderMessage($message);
		$messageMail     = $messageTemplate->render($recipient, $additionalData);

		/** @var TransportInterface $transport */
		$transport = $GLOBALS['container']['avisota.transport.' . $message
			->getQueue()
			->getTransport()
			->getId()];

		$transport->send($messageMail);

		$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['avisota_message_preview']['previewSend'], $email);

		header('Location: ' . $url);
		exit;
	}
}

$send_preview_to_user = new send_preview_to_user();
$send_preview_to_user->run();
