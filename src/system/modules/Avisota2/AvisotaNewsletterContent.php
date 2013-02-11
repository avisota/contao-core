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
 * Class AvisotaNewsletterContent
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaNewsletterContent extends Controller
{
	/**
	 * Singleton instance.
	 *
	 * @var AvisotaNewsletterContent
	 */
	private static $objInstance = null;


	/**
	 * Get singleton instance.
	 */
	public static function getInstance()
	{
		if (self::$objInstance === null) {
			self::$objInstance = new AvisotaNewsletterContent();
		}
		return self::$objInstance;
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
	public function prepareBeforeSending($strContent)
	{
		$strContent = str_replace('{{env::request}}', '{{newsletter::href}}', $strContent);
		$strContent = preg_replace('#\{\{env::.*\}\}#U', '', $strContent);

		return $strContent;
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
		if (preg_match('#\@charset\s+[\'"]([\w\-]+)[\'"]\;#Ui', $css, $arrMatch)) {
			// convert character encoding to utf-8
			if (strtoupper($arrMatch[1]) != 'UTF-8') {
				$css = iconv(strtoupper($arrMatch[1]), 'UTF-8', $css);
			}
			// remove @charset tag
			$css = str_replace($arrMatch[0], '', $css);
		}

		// extends css urls
		if (preg_match_all('#url\((.+)\)#U', $css, $arrMatches, PREG_SET_ORDER)) {
			foreach ($arrMatches as $arrMatch) {
				$path = $source;

				$strUrl = $arrMatch[1];
				if (preg_match('#^".*"$#', $strUrl) || preg_match("#^'.*'$#", $strUrl)) {
					$strUrl = substr($strUrl, 1, -1);
				}
				while (preg_match('#^\.\./#', $strUrl)) {
					$path = dirname($path);
					$strUrl = substr($strUrl, 3);
				}
				if (!preg_match('#^\w+:#', $strUrl) && $strUrl[0] != '/') {
					$strUrl = ($path ? $path . '/' : '') . $strUrl;
				}

				$css = str_replace($arrMatch[0], sprintf('url("%s")', $this->Base->extendURL($strUrl)), $css);
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
	public function getNewsletterElement($intId, $mode = NL_HTML)
	{
		if (!strlen($intId) || $intId < 1) {
			return '';
		}

		$objElement = $this->Database
			->prepare(
			"
				SELECT
					*
				FROM
					tl_avisota_newsletter_content
				WHERE
					id=?"
		)
			->limit(1)
			->execute($intId);

		if ($objElement->numRows < 1) {
			return '';
		}

		$objNewsletter = $this->Database
			->prepare(
			"
				SELECT
					*
				FROM
					tl_avisota_newsletter
				WHERE
					id=?"
		)
			->execute($objElement->pid);

		$objCategory = $this->Database
			->prepare(
			"
				SELECT
					*
				FROM
					tl_avisota_newsletter_category
				WHERE
					id=?"
		)
			->execute($objNewsletter->pid);

		$this->Static->setRecipient($this->Base->getPreviewRecipient($objElement->personalize));

		$strBuffer = $this->generateNewsletterElement($objElement, $mode, $objElement->personalize);
		$strBuffer = $this->replaceInsertTags($strBuffer);

		$this->Static->resetRecipient();

		return $strBuffer;
	}


	/**
	 * Generate a content element return it as plain text string
	 *
	 * @param integer
	 *
	 * @return string
	 */
	public function generateNewsletterElement($arrElement, $mode = NL_HTML)
	{
		/*
		if ($arrElement['personalize'] == 'private' && $personalized != 'private')
		{
			return '';
		}
		 */

		$strClass = $this->findNewsletterElement($arrElement['type']);

		// Return if the class does not exist
		if (!$this->classFileExists($strClass)) {
			$this->log(
				'Newsletter content element class "' . $strClass . '" (newsletter content element "' . $arrElement['type'] . '") does not exist',
				'Avisota getNewsletterElement()',
				TL_ERROR
			);
			return '';
		}

		$arrElement['typePrefix'] = 'nle_';
		$objElement               = new $strClass($arrElement);
		switch ($mode) {
			case NL_HTML:
				$strBuffer = $objElement->generateHTML();
				break;

			case NL_PLAIN:
				$strBuffer = $objElement->generatePlain();
				break;
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getNewsletterElement']) && is_array(
			$GLOBALS['TL_HOOKS']['getNewsletterElement']
		)
		) {
			foreach ($GLOBALS['TL_HOOKS']['getNewsletterElement'] as $callback) {
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($objElement, $strBuffer, $mode);
			}
		}

		return $strBuffer;
	}


	/**
	 * Find a newsletter content element in the TL_NLE array and return its value
	 *
	 * @param string
	 *
	 * @return mixed
	 */
	public function findNewsletterElement($strName)
	{
		foreach ($GLOBALS['TL_NLE'] as $v) {
			foreach ($v as $kk => $vv) {
				if ($kk == $strName) {
					return $vv;
				}
			}
		}

		return '';
	}


	/**
	 * Extend image src and a href.
	 */
	public function replaceAndExtendURLs($strHtml)
	{
		return preg_replace_callback('#(href|src)=(".*"|\'.*\')#U', array($this, 'callbackReplaceAndExtend'), $strHtml);
	}


	/**
	 * Callback function for replaceAndExtendURLs(..)
	 */
	public function callbackReplaceAndExtend($m)
	{
		$strUrl = substr($m[2], 1, -1);
		return $m[1] . '="' . $this->Base->extendURL($strUrl) . '"';
	}


	/**
	 * Get a list of areas.
	 *
	 * @param Database_Result $objCategory
	 */
	protected function getNewsletterAreas(Database_Result $objCategory)
	{
		return array_unique(array_filter(array_merge(array('body'), trimsplit(',', $objCategory->areas))));
	}

	/**
	 * Extend the url to an absolute url.
	 */
	public function extendURL($strUrl)
	{
		$this->import('DomainLink');

		$arrRow = null;

		// get the newsletter category jump to page
		$objCategory = $this->Database
			->prepare(
			"
				SELECT
					c.*
				FROM
					`tl_avisota_newsletter_category` c
				INNER JOIN
					`tl_avisota_newsletter` n
				ON
					c.`id`=n.`pid`
				WHERE
					n.`id`=?"
		)
			->execute($this->pid);
		if ($objCategory->next() && $objCategory->viewOnlinePage) {
			$objPage = $this->getPageDetails($objCategory->viewOnlinePage);
		}
		else {
			$objPage = null;
		}

		return $this->DomainLink->absolutizeUrl($strUrl, $objPage);
	}


	/**
	 * Callback function for replaceAndExtendURLs(..)
	 */
	public function callbackReplaceAndExtendHref($m)
	{
		$strUrl = substr($m[1], 1, -1);
		return 'href="' . $this->extendURL($strUrl) . '"';
	}


	/**
	 * Replace an image tag.
	 *
	 * @param array $arrMatch
	 */
	public function replaceImage($arrMatch)
	{
		// insert alt or title text
		return sprintf(
			'%s<%s>',
			$arrMatch[3] ? $arrMatch[3] . ': ' : ($arrMatch[2] ? $arrMatch[2] . ': ' : ''),
			$this->extendURL($arrMatch[1])
		);
	}


	/**
	 * Replace an link tag.
	 *
	 * @param array $arrMatch
	 */
	public function replaceLink($arrMatch)
	{
		// insert title text
		return sprintf(
			'%s%s <%s>',
			$arrMatch[3],
			$arrMatch[2] ? ' (' . $arrMatch[2] . ')' : '',
			$this->extendURL($arrMatch[1])
		);
	}


	/**
	 * Generate a plain text from html.
	 */
	public function getPlainFromHTML($strText)
	{
		// remove line breaks
		$strText = str_replace
		(
			array("\r", "\n"),
			'',
			$strText
		);

		// replace bold, italic and underlined text
		$strText = preg_replace
		(
			array('#</?(b|strong)>#', '#</?(i|em)>#', '#</?u>#'),
			array('*', '_', '+'),
			$strText
		);

		// replace images
		$strText = preg_replace_callback
		(
			'#<img[^>]+src="([^"]+)"[^>]*(?:alt="([^"])")?[^>]*(?:title="([^"])")?[^>]*>#U',
			array(&$this, 'replaceImage'),
			$strText
		);

		// replace links
		$strText = preg_replace_callback
		(
			'#<a[^>]+href="([^"]+)"[^>]*(?:title="([^"])")?[^>]*>(.*?)</a>#',
			array(&$this, 'replaceLink'),
			$strText
		);

		// replace line breaks and paragraphs
		$strText = str_replace
		(
			array('</div>', '</p>', '<br/>', '<br>'),
			array("\n", "\n\n", "\n", "\n"),
			$strText
		);

		// strip all remeaning tags
		$strText = strip_tags($strText);

		// decode html entities
		$strText = html_entity_decode($strText);

		// wrap the lines
		$strText = wordwrap($strText);

		return $strText;
	}
}
