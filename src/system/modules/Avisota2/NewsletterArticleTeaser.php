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
 * Class NewsletterArticleTeaser
 *
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterArticleTeaser extends NewsletterElement
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_article_teaser_html';

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_article_teaser_plain';


	/**
	 * Parse the html template
	 *
	 * @return string
	 */
	public function generateHTML()
	{
		if ($this->loadArticle()) {
			$strBuffer = parent::generateHTML();
			unset($GLOBALS['objPage']);
			return $strBuffer;
		}
		return '';
	}


	/**
	 * Parse the plain text template
	 *
	 * @return string
	 */
	public function generatePlain()
	{
		if ($this->loadArticle()) {
			$strBuffer = parent::generatePlain();
			unset($GLOBALS['objPage']);
			return $strBuffer;
		}
		return '';
	}


	/**
	 * Load the article.
	 */
	protected function loadArticle()
	{
		$objArticle = $this->Database
			->prepare("SELECT * FROM tl_article WHERE id=?")
			->execute($this->articleAlias);
		if ($objArticle->next()) {
			$this->article = $objArticle;
			return true;
		}
		return false;
	}


	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$alias = strlen($this->article->alias) ? $this->article->alias : $this->article->title;

		if (in_array($alias, array('header', 'container', 'left', 'main', 'right', 'footer'))) {
			$alias .= '-' . $this->article->id;
		}

		$alias = standardize($alias);

		$this->Template->column = $this->article->inColumn;

		// Add modification date
		$this->Template->timestamp = $this->article->tstamp;
		$this->Template->date      = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $this->article->tstamp);
		$this->Template->author    = $this->article->author;

		// Override CSS ID and class
		$arrCss = deserialize($this->article->teaserCssID);

		if (is_array($arrCss) && count($arrCss) == 2) {
			if ($arrCss[0] == '') {
				$arrCss[0] = $alias;
			}
		}
		$this->cssID = $arrCss;

		$article = (!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($this->article->alias)) ? $this->article->alias
			: $this->article->id;
		$href    = 'articles=' . (($this->article->inColumn != 'main') ? $this->article->inColumn . ':'
			: '') . $article;

		$GLOBALS['objPage'] = $this->getPageDetails($this->article->pid);

		$this->Template->headline = $this->article->title;
		$this->Template->href     = $this->addToUrl($href, true);
		$this->Template->teaser   = ($mode == NL_PLAIN ? $this->getPlainFromHTML($this->article->teaser)
			: $this->article->teaser);
		$this->Template->readMore = specialchars(
			sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $this->article->headline),
			true
		);
		$this->Template->more     = $GLOBALS['TL_LANG']['MSC']['more'];
	}
}
