<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
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
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['type']                  = array('Abonnentenquelle', 'Wählen Sie hier die Abonnentenquelle aus.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['title']                 = array('Titel', 'Hier können Sie einen Titel für die Abonnentenquelle angeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['detail_source']         = array('Details beziehen von', 'Hier können Sie auswählen, ob die Abonnentendetails aus der integrierten Abonnententabelle oder der Mitgliedertabelle gelesen werden sollen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csv_file_src']          = array('CSV Datei', 'Hier können Sie die CSV Datei auswählen, aus der die Abonnenten bezogen werden sollen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csv_column_assignment'] = array('Spaltenzuweisung', 'Hier können Sie die Spalten den Feldern zuweisen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['disable']               = array('Deaktiviert', 'Hier können Sie die Abonnentenquelle deaktivieren, sie kann als Empfänger ausgewählt werden, jedoch wird sie beim Versand ignoriert.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['source_legend']    = 'Abonnentenquelle';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['label_column']     = 'Spalte';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['label_assignment'] = 'Zuweisung';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integrated']                = 'Integrierte Abonnenten';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['member_groups']             = 'Mitgliedergruppen';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csv_file']                  = 'Abonnenten aus CSV Datei';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integrated_details']        = 'Integrierte Abonnenten';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['member_details']            = 'Mitglieder';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integrated_member_details'] = 'Integrierte Abonnenten und Mitglieder';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['duplicated_column']         = 'Spalten und Felder dürfen nicht doppelt verwendet werden!';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['missing_email_column']      = 'Sie müssen eine Spalte dem Feld E-Mail zuweisen!';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['new']         = array('Neue Abonnentenquelle', 'Eine neue Abonnentenquelle erstellen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['show']        = array('Abonnentenquelledetails', 'Details der Abonnentenquelle ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['copy']        = array('Abonnentenquelle duplizieren', 'Abonnentenquelle ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['delete']      = array('Abonnentenquelle löschen', 'Abonnentenquelle ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['edit']        = array('Abonnentenquelle bearbeiten', 'Abonnentenquelle ID %s bearbeiten');

?>