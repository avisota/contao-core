<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * Extend default palette
 */
$GLOBALS['TL_DCA']['tl_user']['palettes']['extend'] = str_replace('formp;', 'formp;{avisota_legend},avisota_recipient_lists,avisota_recipient_list_permissions,avisota_recipient_permissions,avisota_newsletter_categories,avisota_newsletter_category_permissions,avisota_newsletter_permissions;', $GLOBALS['TL_DCA']['tl_user']['palettes']['extend']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['custom'] = str_replace('formp;', 'formp;{avisota_legend},avisota_recipient_lists,avisota_recipient_list_permissions,avisota_recipient_permissions,avisota_newsletter_categories,avisota_newsletter_category_permissions,avisota_newsletter_permissions;', $GLOBALS['TL_DCA']['tl_user']['palettes']['custom']);


/**
 * Add fields to tl_user
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['avisota_recipient_lists'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['avisota_recipient_lists'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_avisota_recipient_list.title',
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_user']['fields']['avisota_recipient_list_permissions'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['avisota_recipient_list_permissions'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('create', 'delete'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_user']['fields']['avisota_recipient_permissions'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['avisota_recipient_permissions'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('create', 'delete', 'delete_no_blacklist'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_user']['fields']['avisota_newsletter_categories'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['avisota_newsletter_categories'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_avisota_newsletter_category.title',
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_user']['fields']['avisota_newsletter_category_permissions'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['avisota_newsletter_category_permissions'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('create', 'delete'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_user']['fields']['avisota_newsletter_permissions'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['avisota_newsletter_permissions'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('create', 'delete', 'send'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true)
);

?>