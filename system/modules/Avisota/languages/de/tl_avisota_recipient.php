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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['email']       = array('E-Mail', 'Hier können Sie die E-Mail Adresse des Abonnenten angeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['lists']       = array('Verteiler', 'Wählen Sie hier die zu abonnierenden Verteiler aus.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['salutation']  = array('Anrede', 'Hier können Sie die Anrede auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['title']       = array('Titel', 'Hier können Sie den Titel eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['firstname']   = array('Vorname', 'Hier können Sie den Vornamen eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['lastname']    = array('Nachname', 'Hier können Sie den Nachnamen eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['gender']      = array('Geschlecht', 'Bitte wählen Sie das Geschlecht.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['confirmed']   = array('Bestätigt', 'Hier können Sie die E-Mail Adresse als bestätigt markieren.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['token']       = array('Token', 'Der Auth-Token wird für das Double-Opt-In Verfahren genutzt.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedOn']     = array('Eintragungszeitpunkt', 'Der Zeitpunkt, an dem das Abonnement hinzugefügt wurde.', 'hinzugefügt am %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedBy']     = array('Hinzugefügt von', 'Der Benutzer, der den Abonnenten hinzugefügt hat, falls dieser sich nicht selbst eingetragen hat.', ' von %s', 'einen gelöschten Benutzer');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['recipient_legend'] = 'Abonnent';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['confirm']    = '%s neue Abonnenten wurden importiert.';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['invalid']    = '%s ungültige Einträge wurden übersprungen.';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['subscribed'] = 'registriert am %s';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['manually']   = 'manuell hinzugefügt';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['new']                 = array('Neuer Abonnent', 'Einen neuen Abonnent erstellen');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['show']                = array('Abonnentendetails', 'Details des Abonnenten ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['copy']                = array('Abonnent duplizieren', 'Abonnent ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['delete']              = array('Abonnent löschen', 'Abonnent ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['delete_no_blacklist'] = array('Abonnent löschen ohne Blacklist Eintrag', 'Abonnent ID %s löschen ohne Ihn in die Blacklist einzutragen');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['edit']                = array('Abonnent bearbeiten', 'Abonnent ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['tools']	           = array('Tools','Abonnenten in Massen verarbeiten.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['migrate']	           = array('Migrieren','Abonnenten aus dem Contao Newslettersystem migrieren.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['import']	           = array('CSV-Import','Import von Abbonements aus einer CSV-Datei.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['export']	           = array('CSV-Export','Export von Abbonements in eine CSV-Datei.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['remove']	           = array('CSV-Löschen','Löschen von Abbonements aus einer CSV-Datei.');

?>