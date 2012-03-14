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
 * @author     Oliver Hoff <oliver@hofff.com>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */

MetaPalettes::appendBefore('tl_member', 'default', 'login', array('avisota' => array(':hide', 'avisota_lists')));

$GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][]   = array('AvisotaDCA', 'filterByMailingLists');
$GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][]   = array('tl_member_avisota', 'onload_callback');
$GLOBALS['TL_DCA']['tl_member']['config']['onsubmit_callback'][] = array('tl_member_avisota', 'onsubmit_callback');

$GLOBALS['TL_DCA']['tl_member']['fields']['avisota_lists'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_member']['avisota_lists'],
	'inputType'        => 'checkbox',
	'options_callback' => array('AvisotaDCA', 'getSelectableLists'),
	'load_callback'    => array(array('AvisotaDCA', 'convertFromStringList')),
	'save_callback'    => array(array('AvisotaDCA', 'convertToStringList')),
	'eval'             => array
	(
		'multiple'     => true,
		'feEditable'   => true,
		'feGroup'      => 'newsletter'
	)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['avisota_subscribe'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_member']['avisota_subscribe'],
	'inputType'        => 'checkbox',
	'eval'             => array
	(
		'feEditable'   => true,
		'feGroup'      => 'newsletter'
	)
);

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
			list($objUser, $arrFormData, $objModulePersonalData) = func_get_args();
			$arrLists = deserialize($arrFormData['avisota_lists'], true);
			$intId    = $objUser->id;
		}
		else {
			list($dc) = func_get_args();
			$arrLists = deserialize($dc->activeRecord->avisota_lists, true);
			$intId    = $dc->id;
		}

		if (empty($arrLists)) {
			$this->Database
				->prepare("UPDATE tl_member SET avisota_subscribe=? WHERE id=?")
				->execute('', $intId);
		}
	}
}