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
