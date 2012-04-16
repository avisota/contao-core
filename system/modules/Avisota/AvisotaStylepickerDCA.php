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
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */

/**
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 */
class AvisotaStylepickerDCA extends tl_stylepicker4ward
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}
	/**
	 * @param mixed $val
	 * @param DataContainer $dc
	 *
	 * @return string
	 */
	public function saveAvisotaNewsletterCEs($val, $dc)
	{
		// delete all records for this table/pid
		$this->truncateTargets($dc->id, 'tl_avisota_newsletter_content');

		$vals = unserialize($val);
		if (is_array($vals)) {
			// get sections
			$secs = $this->Input->post('_AvisotaNewsletterCE_Row');
			if (!is_array($secs) || !count($secs))
				return '';

			// save CEs foreach section
			foreach ($secs as $sec)
			{
				foreach ($vals as $val)
				{
					$this->saveTarget($dc->id, 'tl_avisota_newsletter_content', 'cssID', $sec, $val);
				}
			}
		}
		return null;
	}

	/**
	 * @param mixed $val
	 * @param DataContainer $dc
	 *
	 * @return string
	 */
	public function loadAvisotaNewsletterCEs($val, $dc)
	{
		$arrReturn  = array();
		$objTargets = $this->Database
			->prepare('SELECT DISTINCT(cond) FROM tl_stylepicker4ward_target WHERE pid=? AND tbl=?')
			->execute($dc->id, 'tl_avisota_newsletter_content');
		while ($objTargets->next())
		{
			$arrReturn[] = $objTargets->cond;
		}
		return serialize($arrReturn);
	}

	public function loadAvisotaNewsletterCE_Rows($val, $dc)
	{
		$arrReturn  = array();
		$objTargets = $this->Database
			->prepare('SELECT DISTINCT(sec) FROM tl_stylepicker4ward_target WHERE pid=? AND tbl=?')
			->execute($dc->id, 'tl_avisota_newsletter_content');
		while ($objTargets->next())
		{
			$arrReturn[] = $objTargets->sec;
		}
		return serialize($arrReturn);
	}

	/**
	 * Load newsletter content elements from $GLOBALS['TL_NLE']
	 *
	 * @return array
	 */
	public function getAvisotaNewsletterContentElements()
	{
		$arrCEs = array();
		foreach ($GLOBALS['TL_NLE'] as $key => $arr)
		{
			foreach ($arr as $elementName => $val)
			{
				array_push($arrCEs, $elementName);
			}
		}

		return $arrCEs;
	}

	/**
	 * Get newsletter sections
	 *
	 * @return array
	 */
	public function getAvisotaNewsletterSections()
	{
		$ret = array('body');

		$objCategory = $this->Database
			->query('SELECT * FROM tl_avisota_newsletter_category WHERE areas!=\'\'');
		while ($objCategory->next()) {
			$ret = array_merge(
				$ret,
				trimsplit(',', $objCategory->areas)
			);
		}

		return array_unique($ret);
	}
}