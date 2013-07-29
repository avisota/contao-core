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

namespace Avisota\Contao\Transport;

use Avisota\Contao\Entity\Transport;
use Avisota\Contao\Message\Renderer\FromOverwriteMessageRenderer;
use Avisota\Contao\Message\Renderer\ReplyToOverwriteMessageRenderer;
use Avisota\Contao\Message\Renderer\SenderOverwriteMessageRenderer;
use Avisota\Renderer\NativeMessageRenderer;
use Avisota\Transport\SwiftTransport;

class SwiftTransportFactory implements TransportFactoryInterface
{
	public function createTransport(Transport $transport)
	{
		global $container;

		switch ($transport->getSwiftUseSmtp()) {
			case 'swiftSmtpSystemSettings':
				if (!$GLOBALS['TL_CONFIG']['useSMTP']) {
					$swiftTransport = \Swift_SmtpTransport::newInstance(
						$GLOBALS['TL_CONFIG']['smtpHost'],
						$GLOBALS['TL_CONFIG']['smtpPort']
					);

					if ($GLOBALS['TL_CONFIG']['smtpEnc'] == 'ssl' ||
						$GLOBALS['TL_CONFIG']['smtpEnc'] == 'tls'
					) {
						$swiftTransport->setEncryption($GLOBALS['TL_CONFIG']['smtpEnc']);
					}

					if ($GLOBALS['TL_CONFIG']['smtpUser']) {
						$swiftTransport->setUsername($GLOBALS['TL_CONFIG']['smtpUser']);
						$swiftTransport->setPassword($GLOBALS['TL_CONFIG']['smtpPass']);
					}
					break;
				}

			case 'swiftSmtpOff':
				$swiftTransport = \Swift_MailTransport::newInstance();
				break;

			case 'swiftSmtpOn':
				$swiftTransport = \Swift_SmtpTransport::newInstance(
					$transport->getSwiftSmtpHost(),
					$transport->getSwiftSmtpPort()
				);

				if ($transport->getSwiftSmtpEnc()) {
					$swiftTransport->setEncryption($transport->getSwiftSmtpEnc());
				}

				if ($transport->getSwiftSmtpUser() && $transport->getSwiftSmtpPass()) {
					$swiftTransport->setUsername($transport->getSwiftSmtpUser());
					$swiftTransport->setPassword($transport->getSwiftSmtpPass());
				}
				break;
		}

		$swiftMailer = \Swift_Mailer::newInstance($swiftTransport);

		$renderer = $container['avisota.transport.renderer'];

		if ($transport->getSetReplyTo()) {
			$renderer = new ReplyToOverwriteMessageRenderer(
				$renderer,
				$transport->getReplyToAddress(),
				$transport->getReplyToName()
			);
		}

		if ($transport->getSetSender()) {
			$renderer = new SenderOverwriteMessageRenderer(
				$renderer,
				$transport->getSenderAddress(),
				$transport->getSenderName()
			);
		}

		$renderer = new FromOverwriteMessageRenderer(
			$renderer,
			$transport->getFromAddress(),
			$transport->getFromName()
		);

		return new SwiftTransport($swiftMailer, $renderer);
	}
}
