<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
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
			\Database::getInstance()
				->prepare("UPDATE tl_member SET avisota_subscribe=? WHERE id=?")
				->execute('', $userId);
		}
	}
}
