<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class AvisotaNewsletter
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class AvisotaNewsletter
{
	public static function load($id)
	{
		if (!$id) {
			return null;
		}

		$resultSet = Database::getInstance()
			->prepare(
			'SELECT *
					   FROM orm_avisota_mailing
					   WHERE id=? OR alias=?'
		)
			->execute($id, $id);

		if (!$resultSet->next()) {
			return null;
		}

		return new AvisotaNewsletter($resultSet);
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
	 * The content array contains the database rows of content, grouped by cell.
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

	function __construct(Database_Result $resultSet = null)
	{
		foreach ($resultSet->row() as $name => $value) {
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
		$stylesheetCode = '';
		foreach ($this->theme->getStylesheets() as $stylesheetPathname) {
			$continue = true;

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['avisotaGetCss']) && is_array($GLOBALS['TL_HOOKS']['avisotaGetCss'])) {
				foreach ($GLOBALS['TL_HOOKS']['avisotaGetCss'] as $callback) {
					$this->import($callback[0]);
					$temp = $this->$callback[0]->$callback[1]($stylesheetPathname);

					if ($temp !== false) {
						// add content from file
						if (file_exists(TL_ROOT . '/' . $temp)) {
							$stylesheetPathname = $temp;
							break;
						}
						// add content directly as css
						else {
							$stylesheetCode .= trim($temp);
							$continue = false;
						}
					}
				}
			}

			if ($continue && file_exists(TL_ROOT . '/' . $stylesheetPathname)) {
				$file = new File($stylesheetPathname);
				$stylesheetCode .= AvisotaNewsletterContent::getInstance()
					->cleanCSS($file->getContent(), $stylesheetPathname);
				$file->close();
			}
		}

		if ($stylesheetCode) {
			$head .= '<style>' . "\n" . $stylesheetCode . "\n" . '</style>';
		}

		$template        = new AvisotaNewsletterTemplate($this->theme->getHtmlTemplate());
		$template->title = $this->subject;
		$template->head  = $head;
		foreach ($this->theme->getAreas() as $areaName) {
			$template->$areaName = $this->generateContentHtml($areaName);
		}
		$template->newsletter = $this;

		$buffer = $this->replaceAndExtendURLs($template->parse());

		// reset static information
		AvisotaStatic::popNewsletter();

		return $buffer;
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

		$content = '';
		if (isset($this->contentArray[$area])) {
			foreach ($this->contentArray[$area] as $element) {
				$content .= AvisotaNewsletterContent::getInstance()
					->generateNewsletterElement($element, NL_HTML);
			}
		}

		// reset static information
		AvisotaStatic::popNewsletter();

		return $content;
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

		$template        = new AvisotaNewsletterTemplate($this->theme->getHtmlTemplate());
		$template->title = $this->subject;
		foreach ($this->theme->getAreas() as $areaName) {
			$template->$areaName = $this->generateContentText($areaName);
		}
		$template->newsletter = $this;

		$buffer = $template->parse();

		// reset static information
		AvisotaStatic::popNewsletter();

		return $buffer;
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

		$content = '';
		if (isset($this->contentArray[$area])) {
			foreach ($this->contentArray[$area] as $element) {
				$content .= AvisotaNewsletterContent::getInstance()
					->generateNewsletterElement($element, NL_PLAIN);
			}
		}

		// reset static information
		AvisotaStatic::popNewsletter();

		return $content;
	}
}
