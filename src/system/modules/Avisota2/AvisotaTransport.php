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
 * Class AvisotaTransportModule
 *
 *
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
abstract class AvisotaTransport extends Controller
{
	protected static $transportModules = array();

	/**
	 * @static
	 *
	 * @param int $transportModuleId
	 *
	 * @return AvisotaTransportModule
	 * @throws AvisotaTransportException
	 */
	public static function getTransportModule($transportModuleId = 0)
	{
		if ($transportModuleId == 0) {
			$transportModuleId = $GLOBALS['TL_CONFIG']['avisota_default_transport'];
		}

		if (isset(self::$transportModules[$transportModuleId])) {
			return self::$transportModules[$transportModuleId];
		}

		$transportModule = Database::getInstance()
			->prepare("SELECT * FROM tl_avisota_transport WHERE id=?")
			->execute($transportModuleId);
		if ($transportModule->next()) {
			$type = $transportModule->type;

			if (isset($GLOBALS['TL_AVISOTA_TRANSPORT'][$type])) {
				$class = $GLOBALS['TL_AVISOTA_TRANSPORT'][$type];
				return self::$transportModules[$transportModuleId] = new $class($transportModule);
			}

			throw new AvisotaTransportException('Unsupported transport module TYPE ' . $type . '!');
		}

		throw new AvisotaTransportException('Unknown transport module ID ' . $transportModuleId . '!');
	}

	protected $config;

	public function __construct(Database_Result $resultSet)
	{
		parent::__construct();

		$this->config = (object) $resultSet->row();
	}

	/**
	 * Initialise the transport.
	 *
	 * @return void
	 * @throws AvisotaTransportInitialisationException
	 */
	public function initialiseTransport()
	{
	}

	/**
	 * Transport a specific newsletter.
	 *
	 * @abstract
	 *
	 * @param AvisotaRecipient  $recipient
	 * @param AvisotaNewsletter $newsletter
	 *
	 * @return void
	 * @throws AvisotaTransportException
	 */
	public function transportNewsletter(AvisotaRecipient $recipient, AvisotaNewsletter $newsletter)
	{
		// TODO
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
	public abstract function transportEmail($recipientEmail, Mail $email);

	/**
	 * Finalise the transport.
	 *
	 * @return void
	 * @throws AvisotaTransportFinalisationException
	 */
	public function finaliseTransport()
	{
	}
}
