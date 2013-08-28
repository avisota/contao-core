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


/**
 * Class AvisotaTransportSwiftTransport
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class AvisotaTransportSwiftTransport extends AvisotaTransportMailerTransport
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
}
