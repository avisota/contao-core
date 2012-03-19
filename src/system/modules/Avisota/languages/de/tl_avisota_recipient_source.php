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
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['type']    = array('Abonnentenquelle', 'Wählen Sie hier die Abonnentenquelle aus.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['title']   = array('Titel', 'Hier können Sie einen Titel für die Abonnentenquelle angeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['filter']  = array('Filter aktivieren', 'Erlaubt die Abonnentenliste zu filtern. Die Filtermöglichkeiten hängen von der verwendeten Abonnentenquelle ab.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['disable'] = array('Deaktiviert', 'Hier können Sie die Abonnentenquelle deaktivieren, sie kann als Empfänger ausgewählt werden, jedoch wird sie beim Versand ignoriert.');
// integrated recipients
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedBy']                        = array('Abonnenten auswählen&hellip;', 'Wählen Sie hier aus, wie die Abonnenten ausgewählt werden sollen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedMailingLists']              = array('Verteilerlisten', 'Hier können Sie die Verteilerlisten auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedMailingListsRecipients']    = array('Verteilerlisten', 'Wählen Sie einige Verteilerlisten, wenn Sie nur Abonnenten aus diesen Listen zur Auswahl geben möchten.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedAllowSingleListSelection']  = array('Einzelauswahl erlauben', 'Erlaubt dem Redakteur die Verteiler einzeln auszuwählen, sonst wird die Abonnentenquelle nur als ganzes angezeigt und ist nur als ganzes auswählbar.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedAllowSingleSelection']      = array('Einzelauswahl erlauben', 'Erlaubt dem Redakteur die Abonnenten einzeln auszuwählen, sonst wird die Abonnentenquelle nur als ganzes angezeigt und ist nur als ganzes auswählbar.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedDetails']                   = array('Details beziehen von', 'Hier können Sie auswählen, ob die Abonnentendetails aus der integrierten Abonnententabelle oder der Mitgliedertabelle gelesen werden sollen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumns']           = array('Spalten-Filter', 'Filtern der Abonnentenliste nach Spalteninhalten.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsField']      = array('Spalte');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsComparator'] = array('Vergleich');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsValue']      = array('Wert');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsNoEscape']   = array('SQL', 'Wert als nativen SQL Code betrachten (&rarr; nicht escapen).');
// members
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberBy']                              = array('Mitglieder auswählen&hellip;', 'Wählen Sie hier aus, wie die Mitglieder ausgewählt werden sollen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberMailingLists']                    = array('Verteilerlisten', 'Hier können Sie die Verteilerlisten auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberAllowSingleMailingListSelection'] = array('Einzelauswahl erlauben', 'Erlaubt dem Redakteur, die ausgewählten Verteiler einzeln als Empfänger auszuwählen, sonst wird die Abonnentenquelle nur als ganzes angezeigt und ist nur als ganzes auswählbar.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberGroups']                          = array('Mitgliedergruppen', 'Hier können Sie die Mitgliedergruppen auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberAllowSingleGroupSelection']       = array('Einzelauswahl erlauben', 'Erlaubt dem Redakteur die ausgewählten Mitgliedergruppen einzeln als Empfänger auszuwählen, sonst wird die Abonnentenquelle nur als ganzes angezeigt und ist nur als ganzes auswählbar.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberAllowSingleSelection']            = array('Einzelauswahl erlauben', 'Erlaubt dem Redakteur die Mitglieder einzeln als Empfänger auszuwählen, sonst wird die Abonnentenquelle nur als ganzes angezeigt und ist nur als ganzes auswählbar.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumns']                 = array('Spalten-Filter', 'Filtern der Mitgliederliste nach Spalteninhalten.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsField']            = array('Spalte');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsComparator']       = array('Vergleich');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsValue']            = array('Wert');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsNoEscape']         = array('SQL', 'Wert als nativen SQL Code betrachten (&rarr; nicht escapen).');
// csv file
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvFileSrc']                = array('CSV Datei', 'Hier können Sie die CSV Datei auswählen, aus der die Abonnenten bezogen werden sollen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvColumnAssignment']       = array('Spaltenzuweisung', 'Hier können Sie die Spalten den Feldern zuweisen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvColumnAssignmentColumn'] = array('Spalte');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvColumnAssignmentField']  = array('Zuweisung');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['source_legend']     = 'Abonnentenquelle';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['filter_legend']     = 'Filtereinstellungen';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['expert_legend']     = 'Experteneinstellungen';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integrated_legend'] = 'Integrierte Abonnenten';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['member_legend']     = 'Mitglieder';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvFile_legend']    = 'CSV Datei';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integrated']                  = 'Integrierte Abonnenten';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['member']                      = 'Mitglieder';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csv_file']                    = 'Abonnenten aus CSV Datei';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integrated_details']          = 'Integrierte Abonnenten';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['member_details']              = 'Mitglieder';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integrated_member_details']   = 'Integrierte Abonnenten und Mitglieder';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedByMailingLists']    = 'nach ausgewählten Mailingliste';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedByAllMailingLists'] = 'nach allen Mailingliste';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedByRecipients']      = 'nach Abonnenten aus ausgewählten Mailinglisten';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedByAllRecipients']   = 'nach allen Abonnenten';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberByMailingLists']        = 'nach ausgewählten Mailingliste';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberByAllMailingLists']     = 'nach Mailingliste';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberByGroups']              = 'nach ausgewählten Mitgliedergruppen';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberByAllGroups']           = 'nach allen Mitgliedergruppen';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberByAllMembers']          = 'nach allen Mitgliedern';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['duplicated_column']           = 'Spalten und Felder dürfen nicht doppelt verwendet werden!';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['missing_email_column']        = 'Sie müssen eine Spalte dem Feld E-Mail zuweisen!';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['new']    = array('Neue Abonnentenquelle', 'Eine neue Abonnentenquelle erstellen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['show']   = array('Abonnentenquelledetails', 'Details der Abonnentenquelle ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['toggle'] = array('Abonnentenquelle aktivieren/deaktiveren', 'Abonnentenquelle ID %s aktivieren/deaktiveren');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['delete'] = array('Abonnentenquelle löschen', 'Abonnentenquelle ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['edit']   = array('Abonnentenquelle bearbeiten', 'Abonnentenquelle ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['up']     = array('Priorität erhöhen', 'Priorität der Abonnentenquelle ID %s erhöhen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['down']   = array('Priorität verringern', 'Priorität der Abonnentenquelle ID %s verringern');
