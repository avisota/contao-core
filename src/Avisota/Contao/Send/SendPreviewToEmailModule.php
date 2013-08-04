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
use Doctrine\DBAL\Connection;

class SendPreviewToEmailModule implements SendModuleInterface
{
	public function run(Message $message)
	{
		$emailMissing = isset($_SESSION['AVISOTA_SEND_PREVIEW_TO_EMAIL_EMPTY'])
			? $_SESSION['AVISOTA_SEND_PREVIEW_TO_EMAIL_EMPTY']
			: false;
		unset($_SESSION['AVISOTA_SEND_PREVIEW_TO_EMAIL_EMPTY']);

		$template = new \TwigTemplate('avisota/send/send_preview_to_email', 'html5');
		return $template->parse(
			array(
				 'message'      => $message,
				 'emailMissing' => $emailMissing,
			)
		);
	}
}
