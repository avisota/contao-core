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

if ($this->Input->get('avisota_showlist')) {
	$GLOBALS['TL_DCA']['tl_member']['list']['sorting']['filter'][] = array('FIND_IN_SET(?, avisota_lists)', $this->Input->get('avisota_showlist'));
}
