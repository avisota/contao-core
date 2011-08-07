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
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'avisota_developer_mode';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{avisota_legend:hide},avisota_max_send_time,avisota_max_send_count,avisota_max_send_timeout,avisota_dont_disable_recipient_on_failure,avisota_dont_disable_member_on_failure,avisota_developer_mode';
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['avisota_developer_mode'] = 'avisota_developer_email';

$GLOBALS['TL_DCA']['tl_settings']['fields']['avisota_max_send_time'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['avisota_max_send_time'],
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['avisota_max_send_count'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['avisota_max_send_count'],
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['avisota_max_send_timeout'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['avisota_max_send_timeout'],
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['avisota_dont_disable_recipient_on_failure'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['avisota_dont_disable_recipient_on_failure'],
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 clr')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['avisota_dont_disable_member_on_failure'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['avisota_dont_disable_member_on_failure'],
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['avisota_developer_mode'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['avisota_developer_mode'],
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['avisota_developer_email'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['avisota_developer_email'],
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'email')
);
?>