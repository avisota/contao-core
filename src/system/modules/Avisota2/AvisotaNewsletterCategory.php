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
 * Class AvisotaNewsletterCategory
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaNewsletterCategory
{
	public static function load($varId)
	{
		if (!$varId) {
			return null;
		}

		$objResult = Database::getInstance()
			->prepare('SELECT *
					   FROM tl_avisota_newsletter_category
					   WHERE id=? OR alias=?')
			->execute($varId, $varId);

		if (!$objResult->next())
		{
			return null;
		}

		return new AvisotaNewsletterCategory($objResult);
	}

	/**
	 * Title of this category.
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Meta description of this newsletter.
	 *
	 * @var array
	 */
	protected $recipients;

	/**
	 * Meta keywords of this newsletter.
	 *
	 * @var AvisotaNewsletterTheme
	 */
	protected $theme;

	/**
	 * The theme of thins newsletter.
	 *
	 * @var AvisotaTransport
	 */
	protected $transport;

	/**
	 * @var array
	 */
	protected $data;

	function __construct(Database_Result $objResult = null)
	{
		foreach ($objResult->row() as $name => $value) {
			$this->__set($name, $value);
		}
	}

	function __set($name, $value)
	{
		switch ($name) {
			case 'title':
				$this->setTitle($value);
				break;

			case 'recipients':
				$this->setRecipients(is_array($value)
					? $value
					: deserialize($value, true));
				break;

			case 'theme':
				$this->setTheme($value);
				break;

			case 'transport':
				$this->setTransport($value);
				break;

			default:
				$this->data[$name] = $value;
		}
	}

	function __get($name)
	{
		switch ($name) {
			case 'title':
				return $this->getTitle();

			case 'recipients':
				return $this->getRecipients();

			case 'theme':
				return $this->getTheme();

			case 'transport':
				return $this->getTransport();

			default:
				return $this->data[$name];
		}
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param array $recipients
	 */
	public function setRecipients($recipients)
	{
		$this->recipients = $recipients;
	}

	/**
	 * @return array
	 */
	public function getRecipients()
	{
		return $this->recipients;
	}

	/**
	 * @param \AvisotaNewsletterTheme $theme
	 */
	public function setTheme($theme)
	{
		$this->theme = $theme;
	}

	/**
	 * @return \AvisotaNewsletterTheme
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * @param \AvisotaTransport $transport
	 */
	public function setTransport($transport)
	{
		$this->transport = $transport;
	}

	/**
	 * @return \AvisotaTransport
	 */
	public function getTransport()
	{
		return $this->transport;
	}
}
