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

class tl_member_avisota extends Backend
{
	public function onload_callback()
	{
		// Hack, because ModulePersonalData does not call the load_callback for the avisota_lists field
		// uncomment when https://github.com/contao/core/pull/4018 is merged
		// if (TL_MODE == 'FE' && version_compare(VERSION . '.' . BUILD, '2.11.0', '<=')) {
		$this->import('FrontendUser', 'User');
		$this->User->avisota_lists = explode(',', $this->User->avisota_lists);
		// }
	}

	public function onsubmit_callback()
	{
		if (TL_MODE == 'FE') {
			list($user, $formData, $modulePersonalData) = func_get_args();
			$listIds = deserialize($formData['avisota_lists'], true);
			$userId    = $user->id;
		}
		else {
			list($dc) = func_get_args();
			$listIds = deserialize($dc->activeRecord->avisota_lists, true);
			$userId    = $dc->id;
		}

		if (empty($listIds)) {
			$this->Database
				->prepare("UPDATE tl_member SET avisota_subscribe=? WHERE id=?")
				->execute('', $userId);
		}
	}
}