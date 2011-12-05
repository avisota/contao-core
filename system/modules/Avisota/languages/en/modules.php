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
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['avisota']             = 'Newsletter';
$GLOBALS['TL_LANG']['MOD']['avisota_recipients']  = array('Subscribers', 'Manage Newsletter Subscribers');
$GLOBALS['TL_LANG']['MOD']['avisota_newsletter']  = array('Newsletter', 'Manage and send newsletters to outbox.');
$GLOBALS['TL_LANG']['MOD']['avisota_outbox']      = array('Outbox', 'View outbox and send newsletters to recipients');
$GLOBALS['TL_LANG']['MOD']['avisota_translation'] = array('Language Variables', 'Change the language variables for the newsletter.');
$GLOBALS['TL_LANG']['MOD']['avisota_update']      = array('Update', 'Update the Avisota newsletter system.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['avisota']              = 'Newsletter';
$GLOBALS['TL_LANG']['FMD']['avisota_list']         = array('Newsletter list', 'Navigation list of all sended newsletters.');
$GLOBALS['TL_LANG']['FMD']['avisota_reader']       = array('Newsletter reader', 'Show a newsletter in a regular page.');
$GLOBALS['TL_LANG']['FMD']['avisota_subscription'] = array('Manage Subscriptions', 'Login and Logout of the Newsletter System.');
