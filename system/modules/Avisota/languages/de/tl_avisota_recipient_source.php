<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['type']                  = array('Abonnentenquelle', 'Wählen Sie hier die Abonnentenquelle aus.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['title']                 = array('Titel', 'Hier können Sie einen Titel für die Abonnentenquelle angeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['detail_source']         = array('Details beziehen von', 'Hier können Sie auswählen, ob die Abonnentendetails aus der integrierten Abonnententabelle oder der Mitgliedertabelle gelesen werden sollen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['lists']                 = array('Verteilerlisten', 'Hier können Sie die zur Auswahl stehenden Verteilerlisten auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csv_file_src']          = array('CSV Datei', 'Hier können Sie die CSV Datei auswählen, aus der die Abonnenten bezogen werden sollen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csv_column_assignment'] = array('Spaltenzuweisung', 'Hier können Sie die Spalten den Feldern zuweisen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['disable']               = array('Deaktiviert', 'Hier können Sie die Abonnentenquelle deaktivieren, sie kann als Empfänger ausgewählt werden, jedoch wird sie beim Versand ignoriert.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['source_legend']     = 'Abonnentenquelle';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['expert_legend']     = 'Experteneinstellungen';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integrated_legend'] = 'Integrierte Abonnenten';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csv_file_legend']   = 'CSV Datei';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['label_column']      = 'Spalte';
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['label_assignment']  = 'Zuweisung';


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
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['toggle']      = array('Abonnentenquelle aktivieren/deaktiveren', 'Abonnentenquelle ID %s aktivieren/deaktiveren');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['delete']      = array('Abonnentenquelle löschen', 'Abonnentenquelle ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['edit']        = array('Abonnentenquelle bearbeiten', 'Abonnentenquelle ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['up']          = array('Priorität erhöhen', 'Priorität der Abonnentenquelle ID %s erhöhen');
$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['down']        = array('Priorität verringern', 'Priorität der Abonnentenquelle ID %s verringern');

?>