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

namespace Avisota\Contao\Send;

use Avisota\Contao\Entity\Message;
use Avisota\RecipientSource\RecipientSourceInterface;

class SendImmediateModule implements SendModuleInterface
{
	public function run(Message $message)
	{
		global $container;

		$recipientSourceData = $message->getRecipients();
		$serviceName         = sprintf('avisota.recipientSource.%s', $recipientSourceData->getId());
		/** @var RecipientSourceInterface $recipientSource */
		$recipientSource     = $container[$serviceName];

		$template = new \TwigTemplate('avisota/send/send_immediate', 'html5');
		return $template->parse(
			array(
				 'message' => $message,
				 'count'   => $recipientSource->countRecipients(),
			)
		);
	}
}
