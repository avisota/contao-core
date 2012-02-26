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
 * Class AvisotaDCA
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaDCA extends Controller
{
	/**
	 * Convert a string list into an array.
	 *
	 * @param $strLists
	 *
	 * @return array
	 */
	public function convertFromStringList($strLists)
	{
		return explode(',', $strLists);
	}


	/**
	 * Convert an array into a string list.
	 *
	 * @param $arrLists
	 *
	 * @return string
	 */
	public function convertToStringList($arrLists)
	{
		$arrLists = deserialize($arrLists);
		return is_array($arrLists) ? implode(',', $arrLists) : '';
	}

	public function getSelectableLists(DataContainer $dc)
	{
		$strSql = 'SELECT * FROM tl_avisota_mailing_list';
		if (false) {
			$strSql .= ' WHERE id IN (0,' .
				implode(',',
					array_filter(
						array_map('intval',
							deserialize($arrIDs, true)
						)
					)
				) .
				')';
		}
		$strSql .= ' ORDER BY title';

		$this->import('Database');
		$objLists = $this->Database->execute($strSql);

		$arrOptions = array();
		while ($objLists->next()) {
			$arrOptions[$objLists->id] = $objLists->title;
		}

		return $arrOptions;
	}
}
