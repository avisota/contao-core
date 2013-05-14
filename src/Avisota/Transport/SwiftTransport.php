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
 * @license    LGPL
 * @filesource
 */

namespace Avisota\Transport;

/**
 * Class AvisotaTransportSwiftTransport
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class SwiftTransport implements TransportInterface
{
	/**
	 * @var string
	 */
	protected $mailerImplementation = 'swift';

	protected function createMailerConfig()
	{
		$mailerConfig = parent::createMailerConfig();

		switch ($this->config->swiftUseSmtp) {
			case 'swiftSmtpOn':
				$mailerConfig->setUseSMTP(true);
				$mailerConfig->setSmtpHost($this->config->swiftSmtpHost);
				$mailerConfig->setSmtpPort($this->config->swiftSmtpPort);
				$mailerConfig->setSmtpUser($this->config->swiftSmtpUser);
				$mailerConfig->setSmtpPassword($this->config->swiftSmtpPass);
				$mailerConfig->setSmtpEncryption($this->config->swiftSmtpEnc);
				break;

			case 'swiftSmtpOff':
				$mailerConfig->setUseSMTP(false);
				break;
		}

		return $mailerConfig;
	}

	/**
	 * Initialise the transport.
	 *
	 * @return void
	 * @throws AvisotaTransportInitialisationException
	 */
	public function initialiseTransport()
	{
		// TODO: Implement initialiseTransport() method.
	}

	/**
	 * Transport a specific newsletter.
	 *
	 *
	 * @param AvisotaRecipient  $recipient
	 * @param AvisotaNewsletter $newsletter
	 *
	 * @return void
	 * @throws AvisotaTransportException
	 */
	public function transportNewsletter(AvisotaRecipient $recipient, AvisotaNewsletter $newsletter)
	{
		// TODO: Implement transportNewsletter() method.
	}

	/**
	 * Transport a mail.
	 *
	 * @param string $recipientEmail
	 * @param Mail   $email
	 *
	 * @return void
	 * @throws AvisotaTransportException
	 */
	public function transportEmail($recipientEmail, Mail $email)
	{
		// TODO: Implement transportEmail() method.
	}

	/**
	 * Finalise the transport.
	 *
	 * @return void
	 * @throws AvisotaTransportFinalisationException
	 */
	public function finaliseTransport()
	{
		// TODO: Implement finaliseTransport() method.
	}
}
