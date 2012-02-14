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
$GLOBALS['TL_LANG']['tl_avisota_recipient']['email']       = array('E-Mail', 'Specificer emailaddresse.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['confirmed']   = array('Aktiver','Tjek-fra for at deaktivere denne modtager.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['token']       = array('Token ','Den auth token er dobbelt opt-in-metoden.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedOn']     = array('Joined ', 'Datoen for tilmelding.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['recipient_legend'] = 'Modtager';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['subscribed'] = 'registreret på %s';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['manually']   = 'tilføjes manuelt';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['confirm']    = '%s nye modtagere er importeret.';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['invalid']    = '%s ugyldige emner er sprunget over.';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['new']         = array('Ny modtager ','Opret en ny modtager');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['show']        = array('Modtagerens Detaljer ',' Nærmere oplysninger om modtagerens ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['copy']        = array('Kopier Modtager ','Kopier modtager ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['delete']      = array('Slet Modtager', 'Slet Modtagere ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['edit']        = array('Rediger Modtagere', 'Rediger oplysninger for modtageren ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['import']      = array('CSV Import', 'Importer modtagere fra en CSV-fil');
?>