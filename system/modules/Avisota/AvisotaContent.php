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
 * Class AvisotaContent
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaContent extends Controller
{
	/**
	 * Singleton instance.
	 *
	 * @var AvisotaContent
	 */
	private static $objInstance = null;


	/**
	 * Get singleton instance.
	 */
	public static function getInstance()
	{
		if (self::$objInstance === null)
		{
			self::$objInstance = new AvisotaContent();
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
	 * @return string
	 */
	public function prepareBeforeSending($strContent)
	{
		$strContent = str_replace('{{env::request}}', '{{newsletter::href}}', $strContent);
		$strContent = preg_replace('#\{\{env::.*\}\}#U', '', $strContent);

		return $strContent;
	}


	/**
	 * Generate an online view.
	 */
	public function generateOnlineNewsletter($strId)
	{
		// get the newsletter
		$objNewsletter = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter
				WHERE
						id=?
					OR  alias=?")
			->execute($strId, $strId);

		if (!$objNewsletter->next())
		{
			return false;
		}

		// get the newsletter category
		$objCategory = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter_category
				WHERE
					id=?")
			->execute($objNewsletter->pid);

		if (!$objCategory->next())
		{
			return false;
		}

		$this->Static->setCategory($objCategory);
		$this->Static->setNewsletter($objNewsletter);

		$this->Static->setRecipient($this->Base->getPreviewRecipient('anonymous'));

		$personalized = 'anonymous';

		return $this->replaceInsertTags($this->generateHtml($objNewsletter, $objCategory, $personalized));
	}


	/**
	 * Generate the newsletter content.
	 *
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objCategory
	 * @param array $arrRecipient
	 * @param string $personalized
	 * @param string $mode
	 * @return string
	 */
	public function generateContent(Database_Result &$objNewsletter, Database_Result &$objCategory, $personalized, $mode, $area = false)
	{
		$strContent = '';

		$objContent = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter_content
				WHERE
						pid=?
					AND invisible=''
					AND area=?
				ORDER BY
					sorting")
			->execute($objNewsletter->id, $area ? $area : 'body');

		while ($objContent->next())
		{
			$strContent .= $this->generateNewsletterElement($objContent, $mode, $personalized);
		}

		return $strContent;
	}


	/**
	 * Generate the html newsletter.
	 *
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objCategory
	 * @param array $arrRecipient
	 * @param string $personalized
	 * @return string
	 */
	public function generateHtml(Database_Result &$objNewsletter, Database_Result &$objCategory, $personalized)
	{
		$head = '';

		// Add style sheet newsletter.css
		if (file_exists(TL_ROOT . '/newsletter.css'))
		{
			$head .= '<style type="text/css">' . "\n" . $this->cleanCSS(file_get_contents(TL_ROOT . '/newsletter.css')) . "\n" . '</style>' . "\n";
		}

		if (in_array('layout_additional_sources', $this->Config->getActiveModules()))
		{
			$arrStylesheet = unserialize($objCategory->stylesheets);
			if (is_array($arrStylesheet) && count($arrStylesheet))
			{
				$this->import('LayoutAdditionalSources');
				$this->LayoutAdditionalSources->productive = true;
				$head .= implode("\n", $this->LayoutAdditionalSources->generateIncludeHtml($arrStylesheet, true, $this->Base->getViewOnlinePage($objCategory)));
			}
		}

		$objTemplate = new FrontendTemplate($objNewsletter->template_html ? $objNewsletter->template_html : $objCategory->template_html);
		$objTemplate->title = $objNewsletter->subject;
		$objTemplate->head = $head;
		foreach ($this->getNewsletterAreas($objCategory) as $strArea)
		{
			$objTemplate->$strArea = $this->generateContent($objNewsletter, $objCategory, $personalized, NL_HTML, $strArea);
		}
		$objTemplate->newsletter = $objNewsletter->row();
		$objTemplate->category = $objCategory->row();
		return $this->replaceAndExtendURLs($objTemplate->parse());
	}


	/**
	 * Generate the plain text newsletter.
	 *
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objCategory
	 * @param array $arrRecipient
	 * @param string $personalized
	 * @return string
	 */
	public function generatePlain(Database_Result &$objNewsletter, Database_Result &$objCategory, $personalized)
	{
		$objTemplate = new FrontendTemplate($objNewsletter->template_plain ? $objNewsletter->template_plain : $objCategory->template_plain);
		foreach ($this->getNewsletterAreas($objCategory) as $strArea)
		{
			$objTemplate->$strArea = $this->generateContent($objNewsletter, $objCategory, $personalized, NL_PLAIN, $strArea);
		}
		$objTemplate->newsletter = $objNewsletter->row();
		$objTemplate->category = $objCategory->row();
		return $objTemplate->parse();
	}


	/**
	 * Clean up CSS Code.
	 */
	public function cleanCSS($css, $source = '')
	{
		if ($source)
		{
			$source = dirname($source);
		}

		// remove comments
		$css = trim(preg_replace('@/\*\*.*\*/@Us', '', $css));

		// handle @charset
		if (preg_match('#\@charset\s+[\'"]([\w\-]+)[\'"]\;#Ui', $css, $arrMatch))
		{
			// convert character encoding to utf-8
			if (strtoupper($arrMatch[1]) != 'UTF-8')
			{
				$css = iconv(strtoupper($arrMatch[1]), 'UTF-8', $css);
			}
			// remove @charset tag
			$css = str_replace($arrMatch[0], '', $css);
		}

		// extends css urls
		if (preg_match_all('#url\((.+)\)#U', $css, $arrMatches, PREG_SET_ORDER))
		{
			foreach ($arrMatches as $arrMatch)
			{
				$path = $source;

				$strUrl = $arrMatch[1];
				if (preg_match('#^".*"$#', $strUrl) || preg_match("#^'.*'$#", $strUrl))
				{
					$strUrl = substr($strUrl, 1, -1);
				}
				while (preg_match('#^\.\./#', $strUrl))
				{
					$path = dirname($path);
					$strUrl = substr($strUrl, 3);
				}
				if (!preg_match('#^\w+://#', $strUrl) && $strUrl[0] != '/')
				{
					$strUrl = ($path ? $path . '/' : '') . $strUrl;
				}

				$css = str_replace($arrMatch[0], sprintf('url("%s")', $this->Base->extendURL($strUrl)), $css);
			}
		}

		return trim($css);
	}


	/**
	 * Generate a content element return it as plain text string
	 * @param integer
	 * @return string
	 */
	public function getNewsletterElement($intId, $mode = NL_HTML)
	{
		if (!strlen($intId) || $intId < 1)
		{
			return '';
		}

		$objElement = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter_content
				WHERE
					id=?")
			->limit(1)
			->execute($intId);

		if ($objElement->numRows < 1)
		{
			return '';
		}

		$objNewsletter = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter
				WHERE
					id=?")
			->execute($objElement->pid);

		$objCategory = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter_category
				WHERE
					id=?")
			->execute($objNewsletter->pid);

		$this->Static->setRecipient($this->Base->getPreviewRecipient($objElement->personalize));

		$strBuffer = $this->generateNewsletterElement($objElement, $mode, $objElement->personalize);
		$strBuffer = $this->replaceInsertTags($strBuffer);

		$this->Static->resetRecipient();

		return $strBuffer;
	}


	/**
	 * Generate a content element return it as plain text string
	 * @param integer
	 * @return string
	 */
	public function generateNewsletterElement($objElement, $mode = NL_HTML, $personalized = '')
	{
		if ($objElement->personalize == 'private' && $personalized != 'private')
		{
			return '';
		}

		$strClass = $this->findNewsletterElement($objElement->type);

		// Return if the class does not exist
		if (!$this->classFileExists($strClass))
		{
			$this->log('Newsletter content element class "'.$strClass.'" (newsletter content element "'.$objElement->type.'") does not exist', 'Avisota getNewsletterElement()', TL_ERROR);
			return '';
		}

		$objElement->typePrefix = 'nle_';
		$objElement = new $strClass($objElement);
		switch ($mode)
		{
		case NL_HTML:
			$strBuffer = $objElement->generateHTML();
			break;

		case NL_PLAIN:
			$strBuffer = $objElement->generatePlain();
			break;
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getNewsletterElement']) && is_array($GLOBALS['TL_HOOKS']['getNewsletterElement']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getNewsletterElement'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($objElement, $strBuffer, $mode);
			}
		}

		return $strBuffer;
	}


	/**
	 * Find a newsletter content element in the TL_NLE array and return its value
	 * @param string
	 * @return mixed
	 */
	public function findNewsletterElement($strName)
	{
		foreach ($GLOBALS['TL_NLE'] as $v)
		{
			foreach ($v as $kk=>$vv)
			{
				if ($kk == $strName)
				{
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
}
