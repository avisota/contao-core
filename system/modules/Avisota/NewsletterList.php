<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class NewsletterList
 *
 * 
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterList extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_list_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_list_plain';
	

	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$arrItems = array();
		$items = deserialize($this->listitems);

		if ($mode == NL_HTML)
		{
			$limit = count($items) - 1;
		
			for ($i=0; $i<count($items); $i++)
			{
				$arrItems[] = array
				(
					'class' => (($i == 0) ? 'first' : (($i == $limit) ? 'last' : '')),
					'content' => $items[$i]
				);
			}
	
			$this->Template->items = $arrItems;
			$this->Template->tag = ($this->listtype == 'ordered') ? 'ol' : 'ul';
		}
		else
		{
			$this->Template->items = $items;
		}
	}
}

?>