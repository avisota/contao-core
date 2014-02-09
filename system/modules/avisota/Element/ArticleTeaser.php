<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Class ArticleTeaser
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 */
class ArticleTeaser extends Element
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $templateHTML = 'nle_article_teaser_html';

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $templatePlain = 'nle_article_teaser_plain';


	/**
	 * Parse the html template
	 *
	 * @return string
	 */
	public function generateHTML()
	{
		if ($this->loadArticle()) {
			$buffer = parent::generateHTML();
			unset($GLOBALS['objPage']);
			return $buffer;
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
			$buffer = parent::generatePlain();
			unset($GLOBALS['objPage']);
			return $buffer;
		}
		return '';
	}


	/**
	 * Load the article.
	 */
	protected function loadArticle()
	{
		$article = $this->Database
			->prepare("SELECT * FROM tl_article WHERE id=?")
			->execute($this->articleAlias);
		if ($article->next()) {
			$this->article = $article;
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
		$cssIdClass = deserialize($this->article->teaserCssID);

		if (is_array($cssIdClass) && count($cssIdClass) == 2) {
			if ($cssIdClass[0] == '') {
				$cssIdClass[0] = $alias;
			}
		}
		$this->cssID = $cssIdClass;

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
