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
 * Class AvisotaTransportMailerTransport
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
abstract class AvisotaTransportMailerTransport extends AvisotaTransport
{
	/**
	 * @var string
	 */
	protected $mailerImplementation = '';

	/**
	 * @var Mailer
	 */
	protected $mailer;

	public function __construct(Database_Result $resultSet)
	{
		parent::__construct($resultSet);

		$mailerConfig = $this->createMailerConfig();

		$this->mailer = $this->createMailer($mailerConfig);
	}

	/**
	 * @return MailerConfig
	 */
	protected function createMailerConfig()
	{
		return MailerConfig::getDefault()
			->setImplementation($this->mailerImplementation)
			->setLogFile('avisota_mailer_transport_' . $this->config->id . '.log');
	}

	protected function createMailer(MailerConfig $mailerConfig)
	{
		return Mailer::getMailer($mailerConfig);
	}

	/**
	 * Transport a mail.
	 *
	 * @param string $strRecipientEmail
	 * @param Mail   $email
	 *
	 * @return void
	 * @throws AvisotaTransportException
	 */
	public function transportEmail($recipient, Mail $email)
	{
		global $page;

		try {
			// set sender email
			if ($this->config->sender) {
				$email->setSender($this->config->sender);
			}
			else if (isset($page) && strlen($page->adminEmail)) {
				$email->setSender($page->adminEmail);
			}
			else {
				$email->setSender($GLOBALS['TL_CONFIG']['adminEmail']);
			}

			// set sender name
			if (strlen($this->config->senderName)) {
				$email->setSenderName($this->config->senderName);
			}

			// set reply email
			if ($this->config->replyTo) {
				$email->setReplyTo($this->config->replyTo);
			}

			// set reply name
			if ($this->config->replyToName) {
				$email->setReplyToName($this->config->replyToName);
			}

			$this->mailer->send($email, $recipient);
		}
		catch (Swift_RfcComplianceException $e) {
			throw new AvisotaTransportEmailException($recipient, $email, $e->getMessage(), $e->getCode(), $e);
		}
	}
}
