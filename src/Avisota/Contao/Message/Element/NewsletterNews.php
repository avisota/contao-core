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


class NewsletterNews extends Element
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $templateHTML = 'nle_news_html';

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $templatePlain = 'nle_news_plain';

	/**
	 * Caching var for jumpTo-pages
	 *
	 * @var mixed tl_page-rows
	 */
	protected $jumpToPages = array();


	/**
	 * Generate content element
	 *
	 * @param str $mode Compile either html or plaintext part
	 */
	protected function compile($mode)
	{
		$this->import('DomainLink');

		$newsIDs = unserialize($this->news);
		if (!is_array($newsIDs)) {
			$this->Template->events = array();
			return;
		}

		$news = $this->Database
			->prepare(
			'SELECT n.*,a.jumpTo,a.title AS section
				FROM tl_news as n
				LEFT JOIN tl_news_archive as a ON (n.pid = a.id)
				WHERE n.id IN (' . implode(',', $newsIDs) . ')
				ORDER BY n.time'
		)
			->execute();

		$this->Template->news = $news->fetchAllAssoc();
	}
}
