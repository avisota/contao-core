<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class AvisotaStatic
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaStatic extends Frontend
{
	/**
	 * Singelton instance.
	 *
	 * @var AvisotaStatic
	 */
	private static $objInstance = null;

	/**
	 * Get the singleton instance.
	 */
	public static function getInstance()
	{
		if (self::$objInstance === null)
		{
			self::$objInstance = new AvisotaStatic();
		}
		return self::$objInstance;
	}


	/**
	 * The current category.
	 *
	 * @var Database_Result
	 */
	private static $objCategory;


	/**
	 * The current newsletter.
	 *
	 * @var Database_Result
	 */
	private static $objNewsletter;


	/**
	 * The current recipient.
	 *
	 * @var Database_Result
	 */
	private static $arrRecipient;


	/**
	 * Set complete current data.
	 *
	 * @param Database_Result $objCategory
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objRecipient
	 */
	public static function set($objCategory, $objNewsletter, $objRecipient)
	{
		self::$objCategory = $objCategory;
		self::$objNewsletter = $objNewsletter;
		self::$arrRecipient = $objRecipient;
	}


	/**
	 * Reset all data.
	 */
	public static function reset()
	{
		self::$objCategory = null;
		self::$objNewsletter = null;
		self::$arrRecipient = null;
	}


	/**
	 * Reset the current category.
	 */
	public static function resetCategory()
	{
		self::$objCategory = null;
	}


	/**
	 * Set the current category.
	 *
	 * @param Database_Result $objCategory
	 */
	public static function setCategory($objCategory)
	{
		self::$objCategory = $objCategory;
	}


	/**
	 * Get the current category.
	 *
	 * @return Database_Result
	 */
	public static function getCategory()
	{
		return self::$objCategory;
	}


	/**
	 * Reset the current newsletter.
	 */
	public static function resetNewsletter()
	{
		self::$objNewsletter = null;
	}


	/**
	 * Set the current newsletter.
	 *
	 * @param Database_Result $objNewsletter
	 */
	public static function setNewsletter($objNewsletter)
	{
		self::$objNewsletter = $objNewsletter;
	}


	/**
	 * Get the current newsletter.
	 *
	 * @return Database_Result
	 */
	public static function getNewsletter()
	{
		return self::$objNewsletter;
	}


	/**
	 * Reset the current recipient.
	 */
	public static function resetRecipient()
	{
		self::$arrRecipient = null;
	}


	/**
	 * Set the current recipient.
	 *
	 * @param Database_Result $arrRecipient
	 */
	public static function setRecipient($arrRecipient)
	{
		self::$arrRecipient = $arrRecipient;
	}


	/**
	 * Get the current recipient.
	 *
	 * @return Database_Result
	 */
	public static function getRecipient()
	{
		return self::$arrRecipient;
	}


	/**
	 * Singleton
	 */
	protected function __construct() {}
}
