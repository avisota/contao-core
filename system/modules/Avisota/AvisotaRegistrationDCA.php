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
 * @author     Oliver Hoff <oliver@hofff.com>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


class AvisotaRegistrationDCA extends Controller {

	private $blnMemberActivation;

	public function setMemberActivation($blnMemberActivation)
	{
		$this->blnMemberActivation = $blnMemberActivation;
	}

	public function hookCreateNewUser($intMemberID, $arrMemberData)
	{
		if(strlen($arrMemberData['email']) < 5)
			return;
		if(!$this->blnMemberActivation)
			$this->subscribe($arrMemberData['email'], deserialize($arrMemberData['avisota_registration_lists'], true));
	}

	public function hookActivateAccount($objMember)
	{
		if(strlen($objMember->email) < 5)
			return;
		$this->subscribe($objMember->email, deserialize($objMember->avisota_registration_lists, true));
	}

	protected function subscribe($strEmail, $arrLists)
	{
		if(!$arrLists)
			return;

		$objPrepared = $this->Database->prepare('
			SELECT	r.confirmed, r.id AS rid
			FROM	tl_avisota_recipient_list AS l
			LEFT JOIN (
				SELECT	r1.confirmed, r1.id, r1.pid
				FROM	tl_avisota_recipient AS r1
				WHERE	r1.email = ?
			) AS r ON l.id = r.pid
			WHERE l.id = ?
		');
		$intTime = time();

		foreach($arrLists as $intListID) {
			$objAlreadySubscribed = $objPrepared->execute($strEmail, $intListID);

			if(!$objAlreadySubscribed->numRows) // list doesnt exist
				continue;

			$arrData = array(
				'email' => $strEmail,
				'confirmed' => 1,
				'tstamp' => $intTime
			);

			if(!$objAlreadySubscribed->rid) { // no existing subscription
				$arrData['pid'] = $intListID;
				$arrData['addedOn'] = $intTime;
				$this->Database->prepare(
					'INSERT INTO tl_avisota_recipient %s'
				)->set($arrData)->execute();

			} elseif(!$objAlreadySubscribed->confirmed) { // unconfirmed subscription found
				$arrData['token'] = '';
				$this->Database->prepare(
					'UPDATE tl_avisota_recipient %s WHERE id = ?'
				)->set($arrData)->execute($objAlreadySubscribed->rid);
			}
		}
	}

	private $arrSelectableLists;

	public function setSelectableLists($arrSelectableLists)
	{
		$this->arrSelectableLists = deserialize($arrSelectableLists);
	}

	public function getSelectableLists()
	{
		if(!$this->arrSelectableLists)
			return;

		$objLists = $this->Database->execute('
			SELECT		id, title
			FROM		tl_avisota_recipient_list
			WHERE		id IN (' . implode(',', array_map('intval', $this->arrSelectableLists)) . ')
			ORDER BY	title
		');

		$arrOptions = array();
		while($objLists->next())
			$arrOptions[$objLists->id] = $objLists->title;

		return $arrOptions;
	}

	public function getLists() {
		$objLists = $this->Database->execute(
			'SELECT id, title FROM tl_avisota_recipient_list ORDER BY title'
		);

		$arrOptions = array();
		while($objLists->next())
		{
			$arrOptions[$objLists->id] = $objLists->title;
		}

		return $arrOptions;
	}

	public function hookLoadDataContainer($strTable)
	{
		if(!$strTable == 'tl_module') return;
		$arrPalettes = &$GLOBALS['TL_DCA']['tl_module']['palettes'];
		if(isset($arrPalettes['avisota_registration'])) return;
		$arrPalettes['avisota_registration'] = $arrPalettes['registration'] . $arrPalettes['__avisota_registration'];
	}

	protected function __construct()
	{
		$this->import('Database');
	}

	private static $objInstance;

	public static function getInstance()
	{
		if(isset(self::$objInstance))
			return self::$objInstance;

		return self::$objInstance = new self();
	}

}
