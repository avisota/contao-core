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
$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['recipient']     = array('Abonnent', 'Wählen Sie hier den Abonnenten aus.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['confirmations'] = array('Bestätigungen versenden', 'Noch nicht versendete Bestätiungsmails versenden.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['notifications'] = array('Erinnerungen versenden', 'Erinnerungen für noch nicht bestätigte Abonnements versenden.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['overdue']       = array('Überfällig', 'Überfällige Abonnements, für die eine Bestätigung- und alle Erinnerungen versendet wurden.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['edit']             = 'Abonnent benachrichtigen';
$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['confirmationSent'] = 'Bestätigungsmail gesendet am %s';
$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['reminderSent']     = '%d. Erinnerung gesendet am %s';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_notify']['notify_legend'] = 'Benachrichtung';
