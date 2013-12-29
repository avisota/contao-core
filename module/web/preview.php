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

class preview
{
	public function run()
	{
		global $container;

		$input = \Input::getInstance();
		$messageRepository = \Contao\Doctrine\ORM\EntityHelper::getRepository('Avisota\Contao:Message');

		$messageId = $input->get('id');
		$message = $messageRepository->find($messageId);

		if (!$message) {
			header("HTTP/1.0 404 Not Found");
			echo '<h1>404 Not Found</h1>';
			exit;
		}

		$user = BackendUser::getInstance();
		$user->authenticate();

		// TODO HACK
		// see https://github.com/contao/core/pull/6146
		if (version_compare(VERSION, '3', '>=')) {
			$class = new ReflectionClass($user);
			$property = $class->getProperty('arrData');
			$property->setAccessible(true);
			$data = $property->getValue($user);
		}
		else {
			$data = $user->getData();
		}

		$recipient = new \Avisota\Recipient\MutableRecipient($user->email, $data);

		$additionalData = array(
			'view_online_link' => 'system/modules/avisota/web/preview.php?id=' . $message->getId(),
		);

		/** @var \Avisota\Contao\Message\Renderer\MessagePreRendererInterface $renderer */
		$renderer = $container['avisota.renderer'];
		$messageTemplate = $renderer->renderMessage($message);
		$messagePreview = $messageTemplate->renderPreview($recipient, $additionalData);

		header('Content-Type: ' . $messageTemplate->getContentType() . '; charset=' . $messageTemplate->getContentEncoding());
		header('Content-Disposition: inline; filename="' . $messageTemplate->getContentName() . '"');
		echo $messagePreview;
		exit;
	}
}

$preview = new preview();
$preview->run();
