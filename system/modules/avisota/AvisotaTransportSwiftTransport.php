<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Class AvisotaTransportSwiftTransport
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
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
