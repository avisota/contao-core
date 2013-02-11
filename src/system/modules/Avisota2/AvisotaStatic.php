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
	private static $category = array();


	/**
	 * The current newsletter.
	 *
	 * @var array
	 */
	private static $newsletter = array();


	/**
	 * The current recipient.
	 *
	 * @var array
	 */
	private static $recipientData = array();

	/**
	 * Reset all data.
	 */
	public static function reset()
	{
		self::$category   = array();
		self::$newsletter = array();
		self::$recipientData  = array();
	}


	/**
	 * Reset the current category.
	 */
	public static function popCategory()
	{
		return array_shift(self::$category);
	}


	/**
	 * Set the current category.
	 *
	 * @param Database_Result $category
	 */
	public static function pushCategory($category)
	{
		array_unshift(self::$category, $category);
	}


	/**
	 * Get the current category.
	 *
	 * @return Database_Result
	 */
	public static function getCategory()
	{
		return self::$category[0];
	}


	/**
	 * Reset the current newsletter.
	 */
	public static function popNewsletter()
	{
		return array_shift(self::$newsletter);
	}


	/**
	 * Set the current newsletter.
	 *
	 * @param AvisotaNewsletter $newsletter
	 */
	public static function pushNewsletter(AvisotaNewsletter $newsletter)
	{
		array_unshift(self::$newsletter, $newsletter);
	}


	/**
	 * Get the current newsletter.
	 *
	 * @return AvisotaNewsletter
	 */
	public static function getNewsletter()
	{
		return self::$newsletter[0];
	}


	/**
	 * Reset the current recipient.
	 */
	public static function popRecipient()
	{
		return array_shift(self::$recipientData);
	}


	/**
	 * Set the current recipient.
	 *
	 * @param AvisotaRecipient $recipientData
	 */
	public static function pushRecipient(AvisotaRecipient $recipientData)
	{
		array_unshift(self::$recipientData, $recipientData);
	}


	/**
	 * Get the current recipient.
	 *
	 * @return AvisotaRecipient
	 */
	public static function getRecipient()
	{
		return self::$recipientData[0];
	}

	/**
	 * Singleton
	 */
	protected function __construct()
	{
	}
}
