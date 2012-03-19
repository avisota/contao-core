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
 * Class MemberGroupRecipientSource
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class MemberGroupRecipientSource extends Controller implements AvisotaRecipientSource
{
	/**
	 * @var array
	 */
	private $config;
	
	public function __construct($arrConfig)
	{
		$this->import('Database');
		$this->config = $arrConfig;
	}

	/**
	 * Get all selectable recipient options for this source.
	 * Every option can be an individuell ID.
	 *
	 * @return array
	 */
	public function getRecipientOptions()
	{
		switch ($this->config['memberBy']) {
			// members by mailing lists
			case 'memberByMailingLists':
				$arrIDs = array_filter(array_map('intval', deserialize($this->config['memberMailingLists'], true)));
				if (!count($arrIDs)) {
					$arrIDs[] = 0;
				}
				// fetch all selected mailing lists
				$objMailingList = $this->Database
					->execute('SELECT * FROM tl_avisota_mailing_list WHERE id IN (' . implode(',', $arrIDs) . ') ORDER BY title');
				// continue with same logic as for all mailing lists
			case 'memberByAllMailingLists':
				// fetch mailing lists, if there are no result
				if (!isset($objMailingList)) {
					$objMailingList = $this->Database
						->execute('SELECT * FROM tl_avisota_mailing_list ORDER BY title');
				}

				// if single selection is allowed, build an option for every mailing list
				if ($this->config['memberAllowSingleMailingListSelection']) {
					$arrOptions = array();
					while ($objMailingList->next()) {
						$arrOptions[$objMailingList->id] = $objMailingList->title;
					}
					return $arrOptions;
				}

				// build a wildcard option for non-single select
				else {
					return array(
						'*' => implode(', ', $objMailingList->fetchEach('title'))
					);
				}
				break;

			// members by groups
			case 'memberByGroups':
				$arrIDs = array_filter(array_map('intval', deserialize($this->config['memberGroups'], true)));
				if (!count($arrIDs)) {
					$arrIDs[] = 0;
				}
				// fetch all selected mailing lists
				$objGroup = $this->Database
					->execute('SELECT * FROM tl_member_group WHERE id IN (' . implode(',', $arrIDs) . ') ORDER BY name');
				// continue with same logic as for all mailing lists
			case 'memberByAllGroups':
				// fetch mailing lists, if there are no result
				if (!isset($objGroup)) {
					$objGroup = $this->Database
						->execute('SELECT * FROM tl_member_group ORDER BY name');
				}

				// if single selection is allowed, build an option for every mailing list
				if ($this->config['memberAllowSingleGroupSelection']) {
					$arrOptions = array();
					while ($objGroup->next()) {
						$arrOptions[$objGroup->id] = $objGroup->name;
					}
					return $arrOptions;
				}

				// build a wildcard option for non-single select
				else {
					return array(
						'*' => implode(', ', $objGroup->fetchEach('name'))
					);
				}
				break;

			// members as single
			case 'memberByMailingListMembers':
				$arrIDs = array_filter(array_map('intval', deserialize($this->config['memberMailingLists'], true)));
				if (!count($arrIDs)) {
					$arrIDs[] = 0;
				}
				$objMember = $this->Database
					->execute('SELECT m.* FROM tl_member m
							   INNER JOIN tl_member_to_mailing_list t ON t.member=m.id
							   WHERE t.list IN (' . implode(',', $arrIDs) . ')
							   ORDER BY IF(firstname, firstname, IF(lastname, lastname, email)), lastname');

			case 'memberByGroupMembers':
				if (!isset($objMember)) {
					$arrIDs = array_filter(array_map('intval', deserialize($this->config['memberGroups'], true)));
					if (!count($arrIDs)) {
						$arrIDs[] = 0;
					}
					$objMember = $this->Database
						->execute('SELECT m.* FROM tl_member m
								   INNER JOIN tl_member_to_group t ON t.member_id=m.id
								   WHERE t.group_id IN (' . implode(',', $arrIDs) . ')
								   ORDER BY IF(firstname, firstname, IF(lastname, lastname, email)), lastname');
				}

			case 'memberByAllMembers':
				if (!isset($objMember)) {
					$objMember = $this->Database
						->execute("SELECT * FROM tl_member ORDER BY IF(firstname, firstname, IF(lastname, lastname, email)), lastname");
				}

				$time = time();
				$arrOptions = array();
				while ($objMember->next()) {
					$strName = trim($objMember->firstname . ' ' . $objMember->lastname);
					if (!$strName && $objMember->login) {
						$strName = $objMember->login;
					}
					$strEmail = $objMember->email ? $objMember->email : '?';
					if ($strName) {
						$strMember = sprintf('%s &lt;%s&gt;', $strName, $strEmail);
					} else {
						$strMember = $strEmail;
					}
					if ($objMember->disable ||
						$objMember->start != '' && $objMember->start > $time ||
						$objMember->stop != '' && $objMember->stop < $time) {
						$strMember = '<span style="color:#A6A6A6">' . $strMember . '</span>';
					}
					$arrOptions[$objMember->id] = $strMember;
				}

				if ($this->config['memberAllowSingleSelection']) {
					return $arrOptions;
				} else {
					return array(
						'*' => implode(', ', $arrOptions)
					);
				}
				break;

			default:
				$this->log('The member recipient source ID ' . $this->config['id'] . ' is not fully configured!', 'IntegratedAvisotaRecipientSource::getRecipientOptions()', TL_ERROR);
				return array();
		}
	}

	/**
	 * Get recipient IDs of a list of options.
	 *
	 * @abstract
	 * @param array $varOption
	 * @return array
	 */
	public function getRecipients($arrOptions)
	{

	}

	/**
	 * Get the recipient details.
	 *
	 * @param string $varId
	 * @return array
	 */
	public function getRecipientDetails($varId)
	{

	}
}
