<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class AvisotaTransportMailerTransport
 *
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
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
