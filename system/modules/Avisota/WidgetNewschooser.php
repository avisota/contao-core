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
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 *
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey) {
			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$this->import('Database');
		$this->import('BackendUser', 'User');

		$strClass = 'newschooser';

		if (!is_array($this->value)) $this->value = array();

		$objNews = $this->Database
			->prepare('SELECT n.id, n.headline, n.time, a.title AS archive
					   FROM tl_news AS n
					   LEFT JOIN tl_news_archive AS a ON(n.pid = a.id)
					   WHERE n.published="1" ' . ($this->User->isAdmin ? '' : ' AND a.id IN (?)') . '
					   ORDER BY a.title, n.time DESC')
			->execute(count($this->User->news) ? implode(",", $this->User->news) : '0');


		if ($objNews->numRows < 1) {
			return '<p class="tl_noopt">' . $GLOBALS['TL_LANG']['MSC']['noResult'] . '</p>';
		}

		$strBuffer = '';
		$header    = "";
		while ($objNews->next()) {
			if ($objNews->archive != $header) {
				$header = $objNews->archive;
				$strBuffer .= '<br/><h1 class="main_headline">' . $header . '</h1>';
			}

			$strBuffer .= '<div class="tl_content">';
			$strBuffer .= '<input type="checkbox" id="news' . $objNews->id . '" class="tl_checkbox" name="news[]" value="' . $objNews->id . '"';
			if (in_array($objNews->id, $this->value)) $strBuffer .= ' CHECKED';
			$strBuffer .= '/>';
			$strBuffer .= '<label for="news' . $objNews->id . '"> ';
			$strBuffer .= $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objNews->time) . ' - ';
			$strBuffer .= '<strong>' . $objNews->headline . '</strong></label>';
			$strBuffer .= '</div>';
		}

		return $strBuffer;
	}

}

?>
