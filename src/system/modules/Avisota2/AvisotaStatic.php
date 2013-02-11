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
 * Class AvisotaStatic
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaStatic extends Frontend
{
	/**
	 * The current category.
	 *
	 * @var array
	 */
	private static $objCategory = array();


	/**
	 * The current newsletter.
	 *
	 * @var array
	 */
	private static $objNewsletter = array();


	/**
	 * The current recipient.
	 *
	 * @var array
	 */
	private static $arrRecipient = array();

	/**
	 * Reset all data.
	 */
	public static function reset()
	{
		self::$objCategory   = array();
		self::$objNewsletter = array();
		self::$arrRecipient  = array();
	}


	/**
	 * Reset the current category.
	 */
	public static function popCategory()
	{
		return array_shift(self::$objCategory);
	}


	/**
	 * Set the current category.
	 *
	 * @param Database_Result $objCategory
	 */
	public static function pushCategory($objCategory)
	{
		array_unshift(self::$objCategory, $objCategory);
	}


	/**
	 * Get the current category.
	 *
	 * @return Database_Result
	 */
	public static function getCategory()
	{
		return self::$objCategory[0];
	}


	/**
	 * Reset the current newsletter.
	 */
	public static function popNewsletter()
	{
		return array_shift(self::$objNewsletter);
	}


	/**
	 * Set the current newsletter.
	 *
	 * @param AvisotaNewsletter $objNewsletter
	 */
	public static function pushNewsletter(AvisotaNewsletter $objNewsletter)
	{
		array_unshift(self::$objNewsletter, $objNewsletter);
	}


	/**
	 * Get the current newsletter.
	 *
	 * @return AvisotaNewsletter
	 */
	public static function getNewsletter()
	{
		return self::$objNewsletter[0];
	}


	/**
	 * Reset the current recipient.
	 */
	public static function popRecipient()
	{
		return array_shift(self::$arrRecipient);
	}


	/**
	 * Set the current recipient.
	 *
	 * @param AvisotaRecipient $arrRecipient
	 */
	public static function pushRecipient(AvisotaRecipient $arrRecipient)
	{
		array_unshift(self::$arrRecipient, $arrRecipient);
	}


	/**
	 * Get the current recipient.
	 *
	 * @return AvisotaRecipient
	 */
	public static function getRecipient()
	{
		return self::$arrRecipient[0];
	}

	/**
	 * Singleton
	 */
	protected function __construct()
	{
	}
}
