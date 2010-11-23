<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
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
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @filesource
 */


/**
 * Class NewsletterText
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterText extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_text_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_text_plain';
	

	/**
	 * Replace an image tag.
	 * @param array $arrMatch
	 */
	public function replaceImage($arrMatch)
	{
		// insert alt or title text
		return sprintf('[%s]', $this->extendURL($arrMatch[1]));
	}
	
	
	/**
	 * Replace an link tag.
	 * @param array $arrMatch
	 */
	public function replaceLink($arrMatch)
	{
		// insert title text
		return sprintf('%s [%s]', $arrMatch[3], $this->extendURL($arrMatch[1]));
	}
	
	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$this->import('String');

		switch ($mode)
		{
		case NL_HTML:
			// Clean RTE output
			$this->Template->text = str_ireplace
			(
				array('<u>', '</u>', '</p>', '<br /><br />', ' target="_self"'),
				array('<span style="text-decoration:underline;">', '</span>', "</p>\n", "<br /><br />\n", ''),
				$this->String->encodeEmail($this->text)
			);
			break;
			
		case NL_PLAIN:
			if ($this->plain)
			{
				$this->Template->text = $this->plain;
			}
			else
			{
				$strText = $this->text;
				
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
				
				$this->Template->text = $strText;
			}
		}

		$this->Template->addImage = false;

		// Add image
		if ($this->addImage && strlen($this->singleSRC) && is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			$this->addImageToTemplate($this->Template, $this->arrData);
			
			$this->Template->src = $this->extendURL($this->Template->src);
			if ($this->Template->href)
			{
				$this->Template->href = $this->extendURL($this->Template->href);
			}
		}
	}
}

?>