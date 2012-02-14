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
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['title']              = array('Titel', 'Hier können Sie den Titel des Verteilers angeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['alias']              = array('Alias', 'Der Alias ist eine eindeutige Referenz, die anstelle der numerischen Id aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['viewOnlinePage']     = array('Online-Ansehen Seite', 'Bitte wählen Sie die Newsletterleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Newsletter online ansehen wollen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['subscriptionPage']   = array('Abonnement-Verwalten Seite', 'Bitte wählen Sie die Abonnement-Verwalten Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Newsletter kündigen wollen.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['list_legend'] = 'Verteiler';
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['expert_legend']   = 'Experten-Einstellungen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['new']         = array('Neuer Verteiler', 'Einen neuen Verteiler erstellen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['show']        = array('Verteilerdetails', 'Details der Verteilers ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['copy']        = array('Verteiler duplizieren', 'Verteiler ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['delete']      = array('Verteiler löschen', 'Verteiler ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['edit']        = array('Verteiler bearbeiten', 'Verteiler ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['editheader']  = array('Verteilereinstellungen bearbeiten', 'Einstellungen der Verteilers ID %s bearbeiten');

?>