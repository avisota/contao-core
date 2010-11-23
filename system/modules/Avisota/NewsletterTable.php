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
 * Class NewsletterTable
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterTable extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_table_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_table_plain';
	

	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$rows = deserialize($this->tableitems);

		$this->Template->id = 'table_' . $this->id;
		$this->Template->summary = specialchars($this->summary);
		$this->Template->useHeader = $this->thead ? true : false;
		$this->Template->useFooter = $this->tfoot ? true : false;
		$this->Template->thousandsSeparator = $GLOBALS['TL_LANG']['MSC']['thousandsSeparator'];
		$this->Template->decimalSeparator = $GLOBALS['TL_LANG']['MSC']['decimalSeparator'];

		$arrHeader = array();
		$arrBody = array();
		$arrFooter = array();

		// Table header
		if ($this->thead)
		{
			foreach ($rows[0] as $i=>$v)
			{
				// Add cell
				$arrHeader[] = array
				(
					'class' => 'head_'.$i . (($i == 0) ? ' col_first' : '') . (($i == (count($rows[0]) - 1)) ? ' col_last' : ''),
					'content' => (strlen($v) ? nl2br($v) : '&nbsp;')
				);
			}

			array_shift($rows);
		}

		$this->Template->header = $arrHeader;
		$limit = $this->tfoot ? (count($rows)-1) : count($rows);

		// Table body
		for ($j=0; $j<$limit; $j++)
		{
			$class_tr = '';

			if ($j == 0)
			{
				$class_tr = ' row_first';
			}

			if ($j == ($limit - 1))
			{
				$class_tr = ' row_last';
			}

			$class_eo = (($j % 2) == 0) ? ' even' : ' odd';

			foreach ($rows[$j] as $i=>$v)
			{
				$class_td = '';

				if ($i == 0)
				{
					$class_td = ' col_first';
				}

				if ($i == (count($rows[$j]) - 1))
				{
					$class_td = ' col_last';
				}

				$arrBody['row_' . $j . $class_tr . $class_eo][] = array
				(
					'class' => 'col_'.$i . $class_td,
					'content' => (strlen($v) ? preg_replace('/[\n\r]+/i', '<br />', $v) : '&nbsp;')
				);
			}
		}

		$this->Template->body = $arrBody;

		// Table footer
		if ($this->tfoot)
		{
			foreach ($rows[(count($rows)-1)] as $i=>$v)
			{
				$arrFooter[] = array
				(
					'class' => 'foot_'.$i . (($i == 0) ? ' col_first' : '') . (($i == (count($rows[(count($rows)-1)]) - 1)) ? ' col_last' : ''),
					'content' => (strlen($v) ? preg_replace('/[\n\r]+/i', '<br />', $v) : '&nbsp;')
				);
			}
		}

		$this->Template->footer = $arrFooter;
	}
}

?>