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
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
 * @package    WidgetNewschooser
 * @license    LGPL
 * @filesource
 */

class WidgetNewschooser extends Widget
{

	/**
	 * Submit user input
	 *
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 *
	 * @param string
	 * @param mixed
	 */
	public function __set($key, $value)
	{
		switch ($key) {
			default:
				parent::__set($key, $value);
				break;
		}
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generate()
	{
		$this->import('Database');
		if (!is_array($this->value)) {
			$this->value = array();
		}

		$news = $this->Database
			->prepare(
			'SELECT n.id, n.headline, n.time, a.title AS archive
												FROM tl_news AS n
												LEFT JOIN tl_news_archive AS a ON(n.pid = a.id)
												WHERE n.published="1"
												ORDER BY a.title, n.time DESC'
		)
			->execute();


		if ($news->numRows < 1) {
			return '<p class="tl_noopt">' . $GLOBALS['TL_LANG']['MSC']['noResult'] . '</p>';
		}

		$buffer = '';
		$header    = "";
		while ($news->next()) {
			if ($news->archive != $header) {
				$header = $news->archive;
				$buffer .= '<br/><h1 class="main_headline">' . $header . '</h1>';
			}

			$buffer .= '<div class="tl_content">';
			$buffer .= '<input type="checkbox" id="news' . $news->id . '" class="tl_checkbox" name="news[]" value="' . $news->id . '"';
			if (in_array($news->id, $this->value)) {
				$buffer .= ' CHECKED';
			}
			$buffer .= '/>';
			$buffer .= '<label for="news' . $news->id . '"> ';
			$buffer .= $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $news->time) . ' - ';
			$buffer .= '<strong>' . $news->headline . '</strong></label>';
			$buffer .= '</div>';
		}

		return $buffer;
	}

}
