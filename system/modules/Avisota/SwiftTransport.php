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
 * Class SwiftTransport
 *
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class SwiftTransport extends AvisotaAbstractTransportModule
{
	/**
	 * Transport a mail.
	 *
	 * @param string $strRecipientEmail
	 * @param Email $objEmail
	 *
	 * @return void
	 * @throws AvisotaTransportException
	 */
	public function transportEmail($varRecipient, Email $objEmail)
	{
		global $objPage;

		try
		{
			$objEmail->logFile = 'avisota_swift_transport_' . $this->config->id . '.log';

			// set sender email
			if ($this->sender) {
				$objEmail->from = $this->sender;
			} else if (isset($objPage) && strlen($objPage->adminEmail)) {
				$objEmail->from = $objPage->adminEmail;
			} else {
				$objEmail->from = $GLOBALS['TL_CONFIG']['adminEmail'];
			}

			// set sender name
			if (strlen($this->senderName)) {
				$objEmail->fromName = $this->senderName;
			}

			$arrTempSettings = array();
			if ($this->swiftUseSMTP)
			{
				$arrTempSettings = array(
					'useSMTP' => $GLOBALS['TL_CONFIG']['useSMTP'],
					'smtpHost' => $GLOBALS['TL_CONFIG']['smtpHost'],
					'smtpUser' => $GLOBALS['TL_CONFIG']['smtpUser'],
					'smtpPass' => $GLOBALS['TL_CONFIG']['smtpPass'],
					'smtpEnc' => $GLOBALS['TL_CONFIG']['smtpEnc'],
					'smtpPort' => $GLOBALS['TL_CONFIG']['smtpPort']
				);

				$GLOBALS['TL_CONFIG']['useSMTP'] = true;

				$GLOBALS['TL_CONFIG']['smtpHost'] = $this->swiftSmtpHost;
				$GLOBALS['TL_CONFIG']['smtpUser'] = $this->swiftSmtpUser;
				$GLOBALS['TL_CONFIG']['smtpPass'] = $this->swiftSmtpPass;
				$GLOBALS['TL_CONFIG']['smtpEnc']  = $this->swiftSmtpEnc;
				$GLOBALS['TL_CONFIG']['smtpPort'] = $this->swiftSmtpPort;
			}

			$objEmail->sendTo($varRecipient);

			foreach ($arrTempSettings as $k=>$v) {
				$GLOBALS['TL_CONFIG'][$k] = $v;
			}
		}
		catch (Swift_RfcComplianceException $e)
		{
			foreach ($arrTempSettings as $k=>$v) {
				$GLOBALS['TL_CONFIG'][$k] = $v;
			}
			throw new AvisotaTransportEmailException($varRecipient, $objEmail, $e->getMessage(), $e->getCode(), $e);
		}
	}


}
