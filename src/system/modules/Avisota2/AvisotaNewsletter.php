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
 * Class AvisotaNewsletter
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaNewsletter
{
	public static function load($varId)
	{
		if (!$varId) {
			return null;
		}

		$objResult = Database::getInstance()
			->prepare('SELECT *
					   FROM tl_avisota_newsletter
					   WHERE id=? OR alias=?')
			->execute($varId, $varId);

		if (!$objResult->next())
		{
			return null;
		}

		return new AvisotaNewsletter($objResult);
	}

	/**
	 * Subject of this newsletter.
	 *
	 * @var string
	 */
	protected $subject;

	/**
	 * Meta description of this newsletter.
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Meta keywords of this newsletter.
	 *
	 * @var string
	 */
	protected $keywords;

	/**
	 * The theme of thins newsletter.
	 *
	 * @var AvisotaNewsletterTheme
	 */
	protected $theme;

	/**
	 * File attachments.
	 *
	 * @var array
	 */
	protected $attachments;

	/**
	 * The content array contains the database rows of content, grouped by area.
	 *
	 * for example:
	 * <pre>
	 * array(
	 *     'body' => array(
	 *         array(
	 *             'id' => 1,
	 *             'type' => 'text',
	 *             ...
	 *         ),
	 *         array(
	 *             'id' => 2,
	 *             'type' => 'image',
	 *             ...
	 *         ),
	 *         ...
	 *     ),
	 *     'right' => array(
	 *         array(
	 *             'id' => 6,
	 *             ...
	 *         ),
	 *         ...
	 *     ),
	 *     ...
	 * )
	 * </pre>
	 *
	 * @var array
	 */
	protected $contentArray;

	/**
	 * @var array
	 */
	protected $data;

	function __construct(Database_Result $objResult = null)
	{
		foreach ($objResult->row() as $name => $value) {
			$this->$name = $value;
		}
	}

	function __set($name, $value)
	{
		switch ($name) {
			case 'subject':
				$this->setSubject($value);
				break;

			case 'description':
				$this->setDescription($value);
				break;

			case 'keywords':
				$this->setKeywords($value);
				break;

			case 'theme':
				$this->setTheme($value);
				break;

			case 'attachments':
				$this->setAttachments($value);
				break;

			default:
				$this->data[$name] = $value;
		}
	}

	function __get($name)
	{
		switch ($name) {
			case 'subject':
				return $this->getSubject();

			case 'description':
				return $this->getDescription();

			case 'keywords':
				return $this->getKeywords();

			case 'theme':
				return $this->getTheme();

			case 'attachments':
				return $this->getAttachments();

			default:
				return $this->data[$name];
		}
	}

	/**
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	/**
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $keywords
	 */
	public function setKeywords($keywords)
	{
		$this->keywords = $keywords;
	}

	/**
	 * @return string
	 */
	public function getKeywords()
	{
		return $this->keywords;
	}

	/**
	 * @param \AvisotaNewsletterTheme $theme
	 */
	public function setTheme($theme)
	{
		$this->theme = $theme;
	}

	/**
	 * @abstract
	 * @return AvisotaNewsletterTheme
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * Add an attachment file.
	 *
	 * @param $file string
	 */
	public function addAttachment($file, $name = null)
	{
		if (!$name) {
			$name = basename($file);
		}
		$this->attachments[$name] = $file;
	}

	/**
	 * Add an attachment file.
	 *
	 * @param array $attachments
	 */
	public function addAttachments($attachments)
	{
		if (is_string($attachments)) {
			$temp = deserialize($attachments);

			if (is_array($temp)) {
				$attachments = $temp;
			}
			else {
				$attachments = array($attachments);
			}
		}

		foreach ($attachments as $name => $file) {
			$this->addAttachment($file, is_numeric($name) ? null : $name);
		}
	}

	/**
	 * @param array $attachments
	 */
	public function setAttachments($attachments)
	{
		if (is_string($attachments)) {
			$temp = deserialize($attachments);

			if (is_array($temp)) {
				$attachments = $temp;
			}
			else {
				$attachments = array($attachments);
			}
		}

		$this->attachments = array();
		$this->addAttachments($attachments);
	}

	/**
	 * @return array
	 */
	public function getAttachments()
	{
		return $this->attachments;
	}

	/**
	 * @param array $contentArray
	 */
	public function setContentArray($contentArray)
	{
		$this->contentArray = $contentArray;
	}

	/**
	 * @return array
	 */
	public function getContentArray()
	{
		return $this->contentArray;
	}

	/**
	 * @param array $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Generate the newsletter html.
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function generateHtml()
	{
		if (!($this->theme instanceof AvisotaNewsletterTheme)) {
			throw new Exception('A newsletter need a theme!');
		}

		AvisotaStatic::pushNewsletter($this);

		$head = '';

		// Add meta information
		if ($this->description) {
			$head .= '<meta name="description" content="' . $this->description . '" />';
		}
		if ($this->keywords) {
			$head .= '<meta name="keywords" content="' . $this->keywords . '" />';
		}

		// Add style sheet newsletter.css
		$strStylesheets = '';
		foreach ($this->theme->getStylesheets() as $strStylesheet) {
			$blnContinue = true;

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['avisotaGetCss']) && is_array($GLOBALS['TL_HOOKS']['avisotaGetCss'])) {
				foreach ($GLOBALS['TL_HOOKS']['avisotaGetCss'] as $callback) {
					$this->import($callback[0]);
					$strTemp = $this->$callback[0]->$callback[1]($strStylesheet);

					if ($strTemp !== false) {
						// add content from file
						if (file_exists(TL_ROOT . '/' . $strTemp)) {
							$strStylesheet = $strTemp;
							break;
						}
						// add content directly as css
						else {
							$strStylesheets .= trim($strTemp);
							$blnContinue = false;
						}
					}
				}
			}

			if ($blnContinue && file_exists(TL_ROOT . '/' . $strStylesheet)) {
				$objFile = new File($strStylesheet);
				$strStylesheets .= AvisotaNewsletterContent::getInstance()->cleanCSS($objFile->getContent(), $strStylesheet);
				$objFile->close();
			}
		}

		if ($strStylesheets) {
			$head .= '<style>' . "\n" . $strStylesheets . "\n" . '</style>';
		}

		$objTemplate        = new AvisotaNewsletterTemplate($this->theme->getHtmlTemplate());
		$objTemplate->title = $this->subject;
		$objTemplate->head  = $head;
		foreach ($this->theme->getAreas() as $strArea) {
			$objTemplate->$strArea = $this->generateContentHtml($strArea);
		}
		$objTemplate->newsletter = $this;

		$strBuffer =  $this->replaceAndExtendURLs($objTemplate->parse());

		// reset static information
		AvisotaStatic::popNewsletter();

		return $strBuffer;
	}

	/**
	 * Generate the content html for this newsletter.
	 *
	 * @return string
	 */
	public function generateContentHtml($area)
	{
		if (!($this->theme instanceof AvisotaNewsletterTheme)) {
			throw new Exception('A newsletter need a theme!');
		}

		AvisotaStatic::pushNewsletter($this);

		$strContent = '';
		if (isset($this->contentArray[$area])) {
			foreach ($this->contentArray[$area] as $element) {
				$strContent .= AvisotaNewsletterContent::getInstance()->generateNewsletterElement($element, NL_HTML);
			}
		}

		// reset static information
		AvisotaStatic::popNewsletter();

		return $strContent;
	}

	/**
	 * Generate the newsletter text.
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function generateText()
	{
		if (!($this->theme instanceof AvisotaNewsletterTheme)) {
			throw new Exception('A newsletter need a theme!');
		}

		AvisotaStatic::pushNewsletter($this);

		$objTemplate        = new AvisotaNewsletterTemplate($this->theme->getHtmlTemplate());
		$objTemplate->title = $this->subject;
		foreach ($this->theme->getAreas() as $strArea) {
			$objTemplate->$strArea = $this->generateContentText($strArea);
		}
		$objTemplate->newsletter = $this;

		$strBuffer = $objTemplate->parse();

		// reset static information
		AvisotaStatic::popNewsletter();

		return $strBuffer;
	}

	/**
	 * Generate the content text for this newsletter.
	 *
	 * @return string
	 */
	public function generateContentText($area)
	{
		if (!($this->theme instanceof AvisotaNewsletterTheme)) {
			throw new Exception('A newsletter need a theme!');
		}

		AvisotaStatic::pushNewsletter($this);

		$strContent = '';
		if (isset($this->contentArray[$area])) {
			foreach ($this->contentArray[$area] as $element) {
				$strContent .= AvisotaNewsletterContent::getInstance()->generateNewsletterElement($element, NL_PLAIN);
			}
		}

		// reset static information
		AvisotaStatic::popNewsletter();

		return $strContent;
	}
}
