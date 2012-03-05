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
	protected static $objInstance = null;

	public static function getInstance()
	{
		if (self::$objInstance == null) {
			self::$objInstance = new AvisotaDCA();
		}

		return self::$objInstance;
	}

	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

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

	public function getSelectableLists($varContainer)
	{
		$strSql = 'SELECT * FROM tl_avisota_mailing_list';
		if ($varContainer instanceof ModuleRegistration) {
			$arrLists = array_filter(
				array_map('intval',
					deserialize($varContainer->avisota_selectable_lists, true)
				)
			);
			$strSql .= ' WHERE id IN (' . (count($arrLists) ? implode(',', $arrLists) : '0') . ')';
		}
		$strSql .= ' ORDER BY title';

		$objLists = $this->Database->execute($strSql);

		$arrOptions = array();
		while ($objLists->next()) {
			$arrOptions[$objLists->id] = $objLists->title;
		}

		return $arrOptions;
	}

	public function filterByMailingLists(DataContainer $dc)
	{
		switch ($dc->table) {
			case 'tl_member':
				$varId = $this->Input->get('avisota_showlist');
				break;
			case 'tl_avisota_recipient':
				$varId = $this->Input->get('showlist');
				break;
		}
		if ($varId) {
			$objList = $this->Database
				->prepare("SELECT * FROM tl_avisota_mailing_list WHERE id=?")
				->execute($varId);
			if ($objList->next()) {
				switch ($dc->table) {
					case 'tl_member':
						$GLOBALS['TL_DCA']['tl_member']['list']['sorting']['filter'][] = array(
							'FIND_IN_SET(?, avisota_lists)',
							$varId
						);
						break;
					case 'tl_avisota_recipient':
						$GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['sorting']['filter'][] = array(
							'id IN (SELECT recipient FROM tl_avisota_recipient_to_mailing_list WHERE list=?)',
							$varId
						);
						break;
				}
				$this->loadLanguageFile('avisota_dca');
				$_SESSION['TL_INFO'][] = sprintf(
					$GLOBALS['TL_LANG']['avisota_dca']['filteredByMailingList'],
					$objList->title,
					preg_replace('#[&\?](avisota_)?showlist=\d+#', '', $this->Environment->request)
				);
			}
		}
	}

	public function hookCreateNewUser($insertId, $arrData, $objModuleRegistration)
	{
		if ($arrData['avisota_subscribe']) {
			// TODO rework to send confirmation mail
			$this->Database
				->prepare("UPDATE tl_member SET avisota_lists = ? WHERE id = ?")
				->execute(implode(',', deserialize($objModuleRegistration->avisota_selectable_lists, true)), $insertId);
		}
	}

	public function hookActivateAccount($objMember, $objModuleRegistration)
	{
		if ($objModuleRegistration->avisota_confirm_on_activate) {
			$arrLists = array_filter(array_map('intval', deserialize($objModuleRegistration->avisota_selectable_lists, true)));

		}
	}

	public function hookUpdatePersonalData($objUser, $arrFormData, $objModulePersonalData)
	{
		// Hack, because ModulePersonalData does not call the onsubmit_callback
		if (version_compare(VERSION . '.' . BUILD, '2.11.0', '<=') && isset($arrFormData['avisota_lists'])) {
			$arrLists = deserialize($arrFormData['avisota_lists'], true);
			if (empty($arrLists)) {
				$this->import('Database');
				$this->Database
					->prepare("UPDATE tl_member SET avisota_subscribe=? WHERE id=?")
					->execute('', $objUser->id);
			}
		}

		if (isset($arrFormData['avisota_subscribe'])) {
			if ($arrFormData['avisota_subscribe']) {
				$arrLists = array_unique(array_merge(
					array_filter(array_map('intval', is_array($objUser->avisota_lists) ? $objUser->avisota_lists : explode(',', $objUser->avisota_lists))),
					array_filter(array_map('intval', deserialize($objModulePersonalData->avisota_selectable_lists, true)))
				));
			} else {
				$arrLists = array_diff(
					array_filter(array_map('intval', is_array($objUser->avisota_lists) ? $objUser->avisota_lists : explode(',', $objUser->avisota_lists))),
					array_filter(array_map('intval', deserialize($objModulePersonalData->avisota_selectable_lists, true)))
				);
			}

			// TODO rework to send confirmation mail
			$this->Database
				->prepare("UPDATE tl_member SET avisota_lists = ? WHERE id = ?")
				->execute(implode(',', $arrLists), $objUser->id);
		}
	}
}
