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
 * @license    LGPL
 * @filesource
 */


/**
 * Class AvisotaDCA
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class AvisotaDCA extends Controller
{
	protected static $instance = null;

	public static function getInstance()
	{
		if (self::$instance == null) {
			self::$instance = new AvisotaDCA();
		}

		return self::$instance;
	}

	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	/**
	 * Convert a string list into an array.
	 *
	 * @param $lists
	 *
	 * @return array
	 */
	public function convertFromStringList($lists)
	{
		return explode(',', $lists);
	}


	/**
	 * Convert an array into a string list.
	 *
	 * @param $lists
	 *
	 * @return string
	 */
	public function convertToStringList($lists)
	{
		$lists = deserialize($lists);
		return is_array($lists) ? implode(',', $lists) : '';
	}

	public function getSelectableLists($container)
	{
		$sql = 'SELECT * FROM tl_avisota_mailing_list';
		if ($container instanceof ModuleRegistration) {
			$listIds = array_filter(
				array_map(
					'intval',
					deserialize($container->avisota_selectable_lists, true)
				)
			);
			$sql .= ' WHERE id IN (' . (count($listIds) ? implode(',', $listIds) : '0') . ')';
		}
		$sql .= ' ORDER BY title';

		$list = $this->Database->execute($sql);

		$options = array();
		while ($list->next()) {
			$options[$list->id] = $list->title;
		}

		return $options;
	}

	public function filterByMailingLists(DataContainer $dc = null)
	{
		if (TL_MODE == 'FE') {
			return;
		}

		switch ($dc->table) {
			case 'tl_member':
				$id = $this->Input->get('avisota_showlist');
				break;
			case 'tl_avisota_recipient':
				$id = $this->Input->get('showlist');
				break;
		}
		if ($id) {
			$list = $this->Database
				->prepare("SELECT * FROM tl_avisota_mailing_list WHERE id=?")
				->execute($id);
			if ($list->next()) {
				switch ($dc->table) {
					case 'tl_member':
						$GLOBALS['TL_DCA']['tl_member']['list']['sorting']['filter'][] = array(
							'FIND_IN_SET(?, avisota_lists)',
							$id
						);
						break;
					case 'tl_avisota_recipient':
						$GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['sorting']['filter'][] = array(
							'id IN (SELECT recipient FROM tl_avisota_recipient_to_mailing_list WHERE list=?)',
							$id
						);
						break;
				}
				$this->loadLanguageFile('avisota_dca');
				$_SESSION['TL_INFO'][] = sprintf(
					$GLOBALS['TL_LANG']['avisota_dca']['filteredByMailingList'],
					$list->title,
					preg_replace('#[&\?](avisota_)?showlist=\d+#', '', $this->Environment->request)
				);
			}
		}
	}

	public function hookCreateNewUser($insertId, $data, $moduleRegistration)
	{
		if ($data['avisota_subscribe']) {
			// TODO rework to send confirmation mail
			$this->Database
				->prepare("UPDATE tl_member SET avisota_lists = ? WHERE id = ?")
				->execute(implode(',', deserialize($moduleRegistration->avisota_selectable_lists, true)), $insertId);
		}
	}

	public function hookActivateAccount($member, $moduleRegistration)
	{
		if ($moduleRegistration->avisota_confirm_on_activate) {
			// TODO
			$lists = array_filter(
				array_map('intval', deserialize($moduleRegistration->avisota_selectable_lists, true))
			);

		}
	}

	public function hookUpdatePersonalData($user, $formData, $modulePersonalData)
	{
		// Hack, because ModulePersonalData does not call the onsubmit_callback
		// uncomment when https://github.com/contao/core/pull/4018 is merged
		// if (version_compare(VERSION . '.' . BUILD, '2.11.0', '<=') && isset($arrFormData['avisota_lists'])) {
		$lists = deserialize($formData['avisota_lists'], true);
		if (empty($lists)) {
			$this->import('Database');
			$this->Database
				->prepare("UPDATE tl_member SET avisota_subscribe=? WHERE id=?")
				->execute('', $user->id);
		}
		// }

		if (isset($formData['avisota_subscribe'])) {
			if ($formData['avisota_subscribe']) {
				$lists = array_unique(
					array_merge(
						array_filter(
							array_map(
								'intval',
								is_array($user->avisota_lists)
									? $user->avisota_lists
									: explode(
									',',
									$user->avisota_lists
								)
							)
						),
						array_filter(
							array_map('intval', deserialize($modulePersonalData->avisota_selectable_lists, true))
						)
					)
				);
			}
			else {
				$lists = array_diff(
					array_filter(
						array_map(
							'intval',
							is_array($user->avisota_lists)
								? $user->avisota_lists
								: explode(
								',',
								$user->avisota_lists
							)
						)
					),
					array_filter(
						array_map('intval', deserialize($modulePersonalData->avisota_selectable_lists, true))
					)
				);
			}

			// TODO rework to send confirmation mail
			$this->Database
				->prepare("UPDATE tl_member SET avisota_lists = ? WHERE id = ?")
				->execute(implode(',', $lists), $user->id);
		}
	}
}
