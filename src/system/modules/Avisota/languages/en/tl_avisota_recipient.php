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
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['email']       = array('E-Mail', 'Specify email address.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['confirmed']   = array('Activate','Un-Check to deactivate this subscriber.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['token']       = array('Token ','The auth token is the double opt-in method used.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedOn']     = array('Joined ', 'The date of the subscription.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['recipient_legend'] = 'Subscriber';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['subscribed'] = 'registered on %s';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['manually']   = 'added manually';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['confirm']    = '%s new recipients have been imported.';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['invalid']    = '%s invalid entries have been skipped.';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['new']         = array('New Subscriber ','Create a new subscriber');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['show']        = array('Subscriber Details ',' Details of the subscriber ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['copy']        = array('Copy Subscriber ','Copy subscriber ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['delete']      = array('Delete Subscriber', 'Delete subscriber ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['edit']        = array('Edit Subscriber', 'Edit details of subscriber ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['import']      = array('CSV Import', 'Import recipients from a CSV file');
?>