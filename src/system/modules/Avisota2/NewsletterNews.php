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
 * @copyright  4ward.media 2011
 * @copyright  InfinitySoft 2011
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


class NewsletterNews extends NewsletterElement
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
