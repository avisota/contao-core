<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class MailerTransport
 *
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
abstract class MailerTransport extends AvisotaAbstractTransportModule
{
	/**
	 * @var string
	 */
	protected $mailerImplementation = '';

	/**
	 * @var Mailer
	 */
	protected $mailer;

	public function __construct(Database_Result $objRow)
	{
		parent::__construct($objRow);

		$objMailerConfig = $this->createMailerConfig();

		$this->mailer = $this->createMailer($objMailerConfig);
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

	protected function createMailer(MailerConfig $objMailerConfig)
	{
		return Mailer::getMailer($objMailerConfig);
	}

	/**
	 * Transport a mail.
	 *
	 * @param string $strRecipientEmail
	 * @param Mail $objEmail
	 *
	 * @return void
	 * @throws AvisotaTransportException
	 */
	public function transportEmail($varRecipient, Mail $objEmail)
	{
		global $objPage;

		try
		{
			// set sender email
			if ($this->config->sender) {
				$objEmail->setSender($this->config->sender);
			} else if (isset($objPage) && strlen($objPage->adminEmail)) {
				$objEmail->setSender($objPage->adminEmail);
			} else {
				$objEmail->setSender($GLOBALS['TL_CONFIG']['adminEmail']);
			}

			// set sender name
			if (strlen($this->config->senderName)) {
				$objEmail->setSenderName($this->config->senderName);
			}

			// set reply email
			if ($this->config->replyTo) {
				$objEmail->setReplyTo($this->config->replyTo);
			}

			// set reply name
			if ($this->config->replyToName) {
				$objEmail->setReplyToName($this->config->replyToName);
			}

			$this->mailer->send($objEmail, $varRecipient);
		}
		catch (Swift_RfcComplianceException $e)
		{
			throw new AvisotaTransportEmailException($varRecipient, $objEmail, $e->getMessage(), $e->getCode(), $e);
		}
	}
}
