<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\RecipientSource;

/**
 * Class AvisotaRecipientSourceMemberGroup
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class MemberGroup implements RecipientSourceInterface
{
	/**
	 * @var array
	 */
	private $config;

	public function __construct($config)
	{
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
		$database = \Database::getInstance();

		switch ($this->config['memberBy']) {
			// members by mailing lists
			case 'memberByMailingLists':
				$ids = array_filter(array_map('intval', deserialize($this->config['memberMailingLists'], true)));
				if (!count($ids)) {
					$ids[] = 0;
				}
				// fetch all selected mailing lists
				$mailingList = $database
					->execute(
					'SELECT * FROM orm_avisota_mailing_list WHERE id IN (' . implode(',', $ids) . ') ORDER BY title'
				);
			// continue with same logic as for all mailing lists
			case 'memberByAllMailingLists':
				// fetch mailing lists, if there are no result
				if (!isset($mailingList)) {
					$mailingList = $database
						->execute('SELECT * FROM orm_avisota_mailing_list ORDER BY title');
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
				$group = $database
					->execute(
					'SELECT * FROM tl_member_group WHERE id IN (' . implode(',', $ids) . ') ORDER BY name'
				);
			// continue with same logic as for all mailing lists
			case 'memberByAllGroups':
				// fetch mailing lists, if there are no result
				if (!isset($group)) {
					$group = $database
						->execute('SELECT * FROM tl_member_group ORDER BY name');
				}

				$options = array();
				while ($group->next()) {
					$options[$group->id] = $group->name;
				}

				// if single selection is allowed, build an option for every mailing list
				if ($this->config['memberAllowSingleGroupSelection']) {
					return $options;
				}

				// build a wildcard option for non-single select
				else if (count($options)) {
					return array(
						'*' => implode(', ', $group->fetchEach('name'))
					);
				}
				return array();

			// members as single
			case 'memberByMailingListMembers':
				$ids = array_filter(array_map('intval', deserialize($this->config['memberMailingLists'], true)));
				if (!count($ids)) {
					$ids[] = 0;
				}
				$member = $database
					->execute(
					'SELECT m.* FROM tl_member m
							   INNER JOIN tl_member_to_mailing_list t ON t.member=m.id
							   WHERE t.list IN (' . implode(',', $ids) . ')
							   ORDER BY IF(forename, forename, IF(surname, surname, email)), surname'
				);

			case 'memberByGroupMembers':
				if (!isset($member)) {
					$ids = array_filter(array_map('intval', deserialize($this->config['memberGroups'], true)));
					if (!count($ids)) {
						$ids[] = 0;
					}
					$member = $database
						->execute(
						'SELECT m.* FROM tl_member m
								   INNER JOIN tl_member_to_group t ON t.member_id=m.id
								   WHERE t.group_id IN (' . implode(',', $ids) . ')
								   ORDER BY IF(forename, forename, IF(surname, surname, email)), surname'
					);
				}

			case 'memberByAllMembers':
				if (!isset($member)) {
					$member = $database
						->execute(
						"SELECT * FROM tl_member ORDER BY IF(forename, forename, IF(surname, surname, email)), surname"
					);
				}

				$time = time();
				$options = array();
				while ($member->next()) {
					$name = trim($member->forename . ' ' . $member->surname);
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
				else if (count($options)) {
					return array(
						'*' => implode(', ', $options)
					);
				}
				return array();

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
	 * {@inheritdoc}
	 */
	public function getRecipients($options)
	{
		throw new \Exception('Not implemented yet');
	}
}
