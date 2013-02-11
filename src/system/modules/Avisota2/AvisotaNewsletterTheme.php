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
 * Class AvisotaNewsletterTheme
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaNewsletterTheme
{
	protected static function load($id)
	{
		$objResult = Database::getInstance()
			->prepare('SELECT * FROM tl_avisota_newsletter_theme WHERE id=?')
			->execute($id);
		if ($objResult->next()) {
			$objTheme                    = new AvisotaNewsletterTheme();
			$objTheme->id                = $objResult->id;
			$objTheme->title             = $objResult->title;
			$objTheme->previewImage      = $objResult->preview;
			$objTheme->areas             = deserialize($objResult->areas, true);
			$objTheme->htmlTemplate      = $objResult->template_html;
			$objTheme->plainTemplate     = $objResult->template_plain;
			$objTheme->stylesheets       = deserialize($objResult->stylesheets, true);
			$objTheme->templateDirectory = $objResult->templateDirectory;
			return $objTheme;
		}
		return null;
	}

	/**
	 * @var int
	 */
	protected $id = 0;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $previewImage;

	/**
	 * @var array
	 */
	protected $areas;

	/**
	 * @var string
	 */
	protected $htmlTemplate;

	/**
	 * @var string
	 */
	protected $plainTemplate;

	/**
	 * @var array
	 */
	protected $stylesheets;

	/**
	 * @var string
	 */
	protected $templateDirectory;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
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
	 * @param  $preview
	 */
	public function setPreviewImage($preview)
	{
		$this->previewImage = $preview;
	}

	/**
	 * @return
	 */
	public function getPreviewImage()
	{
		return $this->previewImage;
	}

	/**
	 * @param array $areas
	 */
	public function setAreas($areas)
	{
		$this->areas = $areas;
	}

	/**
	 * @return array
	 */
	public function getAreas()
	{
		return $this->areas;
	}

	/**
	 * @param string $htmlTemplate
	 */
	public function setHtmlTemplate($htmlTemplate)
	{
		$this->htmlTemplate = $htmlTemplate;
	}

	/**
	 * @return string
	 */
	public function getHtmlTemplate()
	{
		return $this->htmlTemplate;
	}

	/**
	 * @param string $plainTemplate
	 */
	public function setPlainTemplate($plainTemplate)
	{
		$this->plainTemplate = $plainTemplate;
	}

	/**
	 * @return string
	 */
	public function getPlainTemplate()
	{
		return $this->plainTemplate;
	}

	/**
	 * @param array $stylesheets
	 */
	public function setStylesheets($stylesheets)
	{
		$this->stylesheets = $stylesheets;
	}

	/**
	 * @return array
	 */
	public function getStylesheets()
	{
		return $this->stylesheets;
	}

	/**
	 * @param string $templateDirectory
	 */
	public function setTemplateDirectory($templateDirectory)
	{
		$this->templateDirectory = $templateDirectory;
	}

	/**
	 * @return string
	 */
	public function getTemplateDirectory()
	{
		return $this->templateDirectory;
	}
}
