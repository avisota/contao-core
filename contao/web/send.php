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

class preview extends Backend
{
	function __construct()
	{
		parent::__construct();
	}

	public function run()
	{
		global $container;

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

		$user = BackendUser::getInstance();
		$user->authenticate();

		$this->loadLanguageFile('avisota_message_preview');

		$action = $input->get('action');

		switch ($action) {
			case 'preview';
				$email = $input->get('recipient_email');
				$user  = $input->get('recipient_user');

				if (!$email) {
					/** @var \Doctrine\DBAL\Connection $connection */
					$connection = $container['doctrine.connection.default'];
					$queryBuilder = $connection->createQueryBuilder();
					/** @var \Doctrine\DBAL\Statement $statement */
					$statement = $queryBuilder
						->select('u.email')
						->from('tl_user', 'u')
						->where('id=:id')
						->setParameter(':id', $user)
						->execute();
					$email = $statement->fetchColumn();
				}

				$environment = Environment::getInstance();
				$url = $environment->base . 'contao/main.php?do=avisota_newsletter&table=orm_avisota_message&key=send&id=' . $message->getId();

				$recipient = new \Avisota\Recipient\MutableRecipient($email);

				$additionalData = array('view_online_link' => $url);

				/** @var \Avisota\Contao\Message\Renderer\MessagePreRendererInterface $renderer */
				$renderer = $container['avisota.renderer'];
				$messageTemplate = $renderer->renderMessage($message);
				$messageMail = $messageTemplate->render($recipient, $additionalData);

				/** @var TransportInterface $transport */
				$transport = $GLOBALS['container']['avisota.transport.' . $message->getTransport()->getId()];

				$transport->send($messageMail);

				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['avisota_message_preview']['previewSend'], $email);

				header('Location: ' . $url);
				exit;
				break;

			case 'schedule':
				break;

			default:
				header("HTTP/1.0 400 Bad Request");
				echo '<h1>400 Bad Request</h1>';
				exit;
		}
	}
}

$preview = new preview();
$preview->run();
