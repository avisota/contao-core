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
 * Class AvisotaNewsletterContent
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class AvisotaNewsletterContent extends Controller
{
	/**
	 * Singleton instance.
	 *
	 * @var AvisotaNewsletterContent
	 */
	private static $instance = null;


	/**
	 * Get singleton instance.
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new AvisotaNewsletterContent();
		}
		return self::$instance;
	}


	/**
	 * Singleton
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->import('AvisotaBase', 'Base');
		$this->import('AvisotaStatic', 'Static');
	}


	/**
	 * Prepare the html body code before sending.
	 *
	 * @param string
	 *
	 * @return string
	 */
	public function prepareBeforeSending($content)
	{
		$content = str_replace('{{env::request}}', '{{newsletter::href}}', $content);
		$content = preg_replace('#\{\{env::.*\}\}#U', '', $content);

		return $content;
	}

	/**
	 * Clean up CSS Code.
	 */
	public function cleanCSS($css, $source = '')
	{
		if ($source) {
			$source = dirname($source);
		}

		// remove comments
		$css = trim(preg_replace('@/\*\*.*\*/@Us', '', $css));

		// handle @charset
		if (preg_match('#\@charset\s+[\'"]([\w\-]+)[\'"]\;#Ui', $css, $matches)) {
			// convert character encoding to utf-8
			if (strtoupper($matches[1]) != 'UTF-8') {
				$css = iconv(strtoupper($matches[1]), 'UTF-8', $css);
			}
			// remove @charset tag
			$css = str_replace($matches[0], '', $css);
		}

		// extends css urls
		if (preg_match_all('#url\((.+)\)#U', $css, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $matches) {
				$path = $source;

				$url = $matches[1];
				if (preg_match('#^".*"$#', $url) || preg_match("#^'.*'$#", $url)) {
					$url = substr($url, 1, -1);
				}
				while (preg_match('#^\.\./#', $url)) {
					$path = dirname($path);
					$url = substr($url, 3);
				}
				if (!preg_match('#^\w+:#', $url) && $url[0] != '/') {
					$url = ($path ? $path . '/' : '') . $url;
				}

				$css = str_replace($matches[0], sprintf('url("%s")', $this->Base->extendURL($url)), $css);
			}
		}

		return trim($css);
	}


	/**
	 * Generate a content element return it as plain text string
	 *
	 * @param integer
	 *
	 * @return string
	 */
	public function getNewsletterElement($elementId, $mode = NL_HTML)
	{
		if (!strlen($elementId) || $elementId < 1) {
			return '';
		}

		$element = $this->Database
			->prepare(
			"
				SELECT
					*
				FROM
					orm_avisota_newsletter_content
				WHERE
					id=?"
		)
			->limit(1)
			->execute($elementId);

		if ($element->numRows < 1) {
			return '';
		}

		$newsletter = $this->Database
			->prepare(
			"
				SELECT
					*
				FROM
					orm_avisota_newsletter
				WHERE
					id=?"
		)
			->execute($element->pid);

		$category = $this->Database
			->prepare(
			"
				SELECT
					*
				FROM
					orm_avisota_newsletter_category
				WHERE
					id=?"
		)
			->execute($newsletter->pid);

		$this->Static->setRecipient($this->Base->getPreviewRecipient($element->personalize));

		$buffer = $this->generateNewsletterElement($element, $mode, $element->personalize);
		$buffer = $this->replaceInsertTags($buffer);

		$this->Static->resetRecipient();

		return $buffer;
	}


	/**
	 * Generate a content element return it as plain text string
	 *
	 * @param integer
	 *
	 * @return string
	 */
	public function generateNewsletterElement($elementData, $mode = NL_HTML)
	{
		/*
		if ($arrElement['personalize'] == 'private' && $personalized != 'private')
		{
			return '';
		}
		 */

		$className = $this->findNewsletterElement($elementData['type']);

		// Return if the class does not exist
		if (!$this->classFileExists($className)) {
			$this->log(
				'Newsletter content element class "' . $className . '" (newsletter content element "' . $elementData['type'] . '") does not exist',
				'Avisota getNewsletterElement()',
				TL_ERROR
			);
			return '';
		}

		$elementData['typePrefix'] = 'nle_';
		$element               = new $className($elementData);
		switch ($mode) {
			case NL_HTML:
				$buffer = $element->generateHTML();
				break;

			case NL_PLAIN:
				$buffer = $element->generatePlain();
				break;
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getNewsletterElement']) && is_array(
			$GLOBALS['TL_HOOKS']['getNewsletterElement']
		)
		) {
			foreach ($GLOBALS['TL_HOOKS']['getNewsletterElement'] as $callback) {
				$this->import($callback[0]);
				$buffer = $this->$callback[0]->$callback[1]($element, $buffer, $mode);
			}
		}

		return $buffer;
	}


	/**
	 * Find a newsletter content element in the TL_NLE array and return its value
	 *
	 * @param string
	 *
	 * @return mixed
	 */
	public function findNewsletterElement($name)
	{
		foreach ($GLOBALS['TL_NLE'] as $v) {
			foreach ($v as $kk => $vv) {
				if ($kk == $name) {
					return $vv;
				}
			}
		}

		return '';
	}


	/**
	 * Extend image src and a href.
	 */
	public function replaceAndExtendURLs($htmlContent)
	{
		return preg_replace_callback('#(href|src)=(".*"|\'.*\')#U', array($this, 'callbackReplaceAndExtend'), $htmlContent);
	}


	/**
	 * Callback function for replaceAndExtendURLs(..)
	 */
	public function callbackReplaceAndExtend($m)
	{
		$url = substr($m[2], 1, -1);
		return $m[1] . '="' . $this->Base->extendURL($url) . '"';
	}


	/**
	 * Get a list of areas.
	 *
	 * @param Database_Result $category
	 */
	protected function getNewsletterAreas(Database_Result $category)
	{
		return array_unique(array_filter(array_merge(array('body'), trimsplit(',', $category->areas))));
	}

	/**
	 * Extend the url to an absolute url.
	 */
	public function extendURL($url)
	{
		$this->import('DomainLink');

		$row = null;

		// get the newsletter category jump to page
		$category = $this->Database
			->prepare(
			"
				SELECT
					c.*
				FROM
					`orm_avisota_newsletter_category` c
				INNER JOIN
					`orm_avisota_newsletter` n
				ON
					c.`id`=n.`pid`
				WHERE
					n.`id`=?"
		)
			->execute($this->pid);
		if ($category->next() && $category->viewOnlinePage) {
			$page = $this->getPageDetails($category->viewOnlinePage);
		}
		else {
			$page = null;
		}

		return $this->DomainLink->absolutizeUrl($url, $page);
	}


	/**
	 * Callback function for replaceAndExtendURLs(..)
	 */
	public function callbackReplaceAndExtendHref($m)
	{
		$url = substr($m[1], 1, -1);
		return 'href="' . $this->extendURL($url) . '"';
	}


	/**
	 * Replace an image tag.
	 *
	 * @param array $matches
	 */
	public function replaceImage($matches)
	{
		// insert alt or title text
		return sprintf(
			'%s<%s>',
			$matches[3] ? $matches[3] . ': ' : ($matches[2] ? $matches[2] . ': ' : ''),
			$this->extendURL($matches[1])
		);
	}


	/**
	 * Replace an link tag.
	 *
	 * @param array $matches
	 */
	public function replaceLink($matches)
	{
		// insert title text
		return sprintf(
			'%s%s <%s>',
			$matches[3],
			$matches[2] ? ' (' . $matches[2] . ')' : '',
			$this->extendURL($matches[1])
		);
	}


	/**
	 * Generate a plain text from html.
	 */
	public function getPlainFromHTML($textContent)
	{
		// remove line breaks
		$textContent = str_replace
		(
			array("\r", "\n"),
			'',
			$textContent
		);

		// replace bold, italic and underlined text
		$textContent = preg_replace
		(
			array('#</?(b|strong)>#', '#</?(i|em)>#', '#</?u>#'),
			array('*', '_', '+'),
			$textContent
		);

		// replace images
		$textContent = preg_replace_callback
		(
			'#<img[^>]+src="([^"]+)"[^>]*(?:alt="([^"])")?[^>]*(?:title="([^"])")?[^>]*>#U',
			array(&$this, 'replaceImage'),
			$textContent
		);

		// replace links
		$textContent = preg_replace_callback
		(
			'#<a[^>]+href="([^"]+)"[^>]*(?:title="([^"])")?[^>]*>(.*?)</a>#',
			array(&$this, 'replaceLink'),
			$textContent
		);

		// replace line breaks and paragraphs
		$textContent = str_replace
		(
			array('</div>', '</p>', '<br/>', '<br>'),
			array("\n", "\n\n", "\n", "\n"),
			$textContent
		);

		// strip all remeaning tags
		$textContent = strip_tags($textContent);

		// decode html entities
		$textContent = html_entity_decode($textContent);

		// wrap the lines
		$textContent = wordwrap($textContent);

		return $textContent;
	}
}
