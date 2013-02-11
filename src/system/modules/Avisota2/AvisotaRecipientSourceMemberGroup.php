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
 * This program is distributed in the hope that iCSVFileRecipientSourcet will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICUAvisotaRecipientSourceCSVFileLAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class AvisotaRecipientSourceMemberGroup
 *
 *
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaRecipientSourceMemberGroup extends Controller implements AvisotaRecipientSource
{
	/**
	 * @var array
	 */
	private $config;

	public function __construct($config)
	{
		$this->import('Database');
		$this->config = $config;
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
				$ids = array_filter(array_map('intval', deserialize($this->config['memberMailingLists'], true)));
				if (!count($ids)) {
					$ids[] = 0;
				}
				// fetch all selected mailing lists
				$mailingList = $this->Database
					->execute(
					'SELECT * FROM tl_avisota_mailing_list WHERE id IN (' . implode(',', $ids) . ') ORDER BY title'
				);
			// continue with same logic as for all mailing lists
			case 'memberByAllMailingLists':
				// fetch mailing lists, if there are no result
				if (!isset($mailingList)) {
					$mailingList = $this->Database
						->execute('SELECT * FROM tl_avisota_mailing_list ORDER BY title');
				}

				// if single selection is allowed, build an option for every mailing list
				if ($this->config['memberAllowSingleMailingListSelection']) {
					$options = array();
					while ($mailingList->next()) {
						$options[$mailingList->id] = $mailingList->title;
					}
					return $options;
				}

				// build a wildcard option for non-single select
				else {
					return array(
						'*' => implode(', ', $mailingList->fetchEach('title'))
					);
				}
				break;

			// members by groups
			case 'memberByGroups':
				$ids = array_filter(array_map('intval', deserialize($this->config['memberGroups'], true)));
				if (!count($ids)) {
					$ids[] = 0;
				}
				// fetch all selected mailing lists
				$group = $this->Database
					->execute(
					'SELECT * FROM tl_member_group WHERE id IN (' . implode(',', $ids) . ') ORDER BY name'
				);
			// continue with same logic as for all mailing lists
			case 'memberByAllGroups':
				// fetch mailing lists, if there are no result
				if (!isset($group)) {
					$group = $this->Database
						->execute('SELECT * FROM tl_member_group ORDER BY name');
				}

				// if single selection is allowed, build an option for every mailing list
				if ($this->config['memberAllowSingleGroupSelection']) {
					$options = array();
					while ($group->next()) {
						$options[$group->id] = $group->name;
					}
					return $options;
				}

				// build a wildcard option for non-single select
				else {
					return array(
						'*' => implode(', ', $group->fetchEach('name'))
					);
				}
				break;

			// members as single
			case 'memberByMailingListMembers':
				$ids = array_filter(array_map('intval', deserialize($this->config['memberMailingLists'], true)));
				if (!count($ids)) {
					$ids[] = 0;
				}
				$member = $this->Database
					->execute(
					'SELECT m.* FROM tl_member m
							   INNER JOIN tl_member_to_mailing_list t ON t.member=m.id
							   WHERE t.list IN (' . implode(',', $ids) . ')
							   ORDER BY IF(firstname, firstname, IF(lastname, lastname, email)), lastname'
				);

			case 'memberByGroupMembers':
				if (!isset($member)) {
					$ids = array_filter(array_map('intval', deserialize($this->config['memberGroups'], true)));
					if (!count($ids)) {
						$ids[] = 0;
					}
					$member = $this->Database
						->execute(
						'SELECT m.* FROM tl_member m
								   INNER JOIN tl_member_to_group t ON t.member_id=m.id
								   WHERE t.group_id IN (' . implode(',', $ids) . ')
								   ORDER BY IF(firstname, firstname, IF(lastname, lastname, email)), lastname'
					);
				}

			case 'memberByAllMembers':
				if (!isset($member)) {
					$member = $this->Database
						->execute(
						"SELECT * FROM tl_member ORDER BY IF(firstname, firstname, IF(lastname, lastname, email)), lastname"
					);
				}

				$time = time();
				$options = array();
				while ($member->next()) {
					$name = trim($member->firstname . ' ' . $member->lastname);
					if (!$name && $member->login) {
						$name = $member->login;
					}
					$email = $member->email ? $member->email : '?';
					if ($name) {
						$memberName = sprintf('%s &lt;%s&gt;', $name, $email);
					}
					else {
						$memberName = $email;
					}
					if ($member->disable ||
						$member->start != '' && $member->start > $time ||
						$member->stop != '' && $member->stop < $time
					) {
						$memberName = '<span style="color:#A6A6A6">' . $memberName . '</span>';
					}
					$options[$member->id] = $memberName;
				}

				if ($this->config['memberAllowSingleSelection']) {
					return $options;
				}
				else {
					return array(
						'*' => implode(', ', $options)
					);
				}
				break;

			default:
				$this->log(
					'The member recipient source ID ' . $this->config['id'] . ' is not fully configured!',
					'AvisotaRecipientSourceIntegratedRecipients::getRecipientOptions()',
					TL_ERROR
				);
				return array();
		}
	}

	/**
	 * Get recipient IDs of a list of options.
	 *
	 * @abstract
	 *
	 * @param array $varOption
	 *
	 * @return array
	 */
	public function getRecipients($options)
	{

	}

	/**
	 * Get the recipient details.
	 *
	 * @param string $id
	 *
	 * @return array
	 */
	public function getRecipientDetails($id)
	{

	}
}
