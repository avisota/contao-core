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
 * Class AvisotaTransportModule
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
abstract class AvisotaTransport extends Controller
{
	protected static $arrTransportModules = array();

	/**
	 * @static
	 * @param int $intTransportModule
	 * @return AvisotaTransportModule
	 * @throws AvisotaTransportException
	 */
	public static function getTransportModule($intTransportModule = 0)
	{
		if ($intTransportModule == 0) {
			$intTransportModule = $GLOBALS['TL_CONFIG']['avisota_default_transport'];
		}

		if (isset(self::$arrTransportModules[$intTransportModule])) {
			return self::$arrTransportModules[$intTransportModule];
		}

		$objTransportModule = Database::getInstance()
			->prepare("SELECT * FROM tl_avisota_transport WHERE id=?")
			->execute($intTransportModule);
		if ($objTransportModule->next()) {
			$strType = $objTransportModule->type;

			if (isset($GLOBALS['TL_AVISOTA_TRANSPORT'][$strType])) {
				$strClass = $GLOBALS['TL_AVISOTA_TRANSPORT'][$strType];
				return self::$arrTransportModules[$intTransportModule] = new $strClass($objTransportModule);
			}

			$this->log('Unsupported transport module TYPE ' . $strType, 'AvisotaTransmission::getTransportModule' . '!', TL_ERROR);
			throw new AvisotaTransportException('Unsupported transport module TYPE ' . $strType . '!');
		}

		$this->log('Unknown transport module ID ' . $intTransportModule . '!', 'AvisotaTransmission::getTransportModule', TL_ERROR);
		throw new AvisotaTransportException('Unknown transport module ID ' . $intTransportModule . '!');
	}

	protected $config;

	public function __construct(Database_Result $objRow)
	{
		parent::__construct();

		$this->config = (object) $objRow->row();
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
	 * @param AvisotaRecipient $objRecipient
	 * @param AvisotaNewsletter $objNewsletter
	 *
	 * @return void
	 * @throws AvisotaTransportException
	 */
	public function transportNewsletter(AvisotaRecipient $objRecipient, AvisotaNewsletter $objNewsletter)
	{
		// TODO
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
	public abstract function transportEmail($strRecipientEmail, Mail $objEmail);

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
