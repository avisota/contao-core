<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['source']    = array(
	'Import-Quelle',
	'Wählen Sie hier die Datei aus, die Sie importieren möchten.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['upload']    = array(
	'Import-Upload',
	'Laden Sie eine Datei hoch, die Sie importieren möchten.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['delimiter'] = array(
	'Feldtrenner',
	'Wählen Sie hier das Zeichen aus, nach dem die einzelnen Felder getrennt sind.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['enclosure'] = array(
	'Texttrenner',
	'Wählen Sie hier das Zeichen aus, nach dem der Text getrennt ist.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['columns']   = array(
	'Spaltenzuordnung',
	'Wählen Sie hier, wie die Spalten zugeordnet werden sollen.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['overwrite'] = array(
	'Bestehende Einträge überschreiben',
	'Wählen Sie diese Option, um bestehende Einträge zu überschreiben/aktualisieren.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['force']     = array(
	'Import erzwingen',
	'Warnung: Sie sollten den Wunsch eines Abonnenten Ihren Newsletter nicht mehr erhalten zu wollen respektieren, es besteht die Möglichkeit dass Sie rechtliche Konsequenzen zu befürchten haben, lassen Sie sich diesbezüglich von einem Anwalt beraten! Nutzen Sie diese Option mit Bedacht und nur dann, wenn Sie wissen was Sie tun! Wählen Sie diese Option, wird die interne Blacklist ignoriert. Die Blacklist sorgt dafür, dass Abonnenten die sich abgemeldet haben nicht durch den Import wieder hinzugefügt werden.'
);


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['double']      = 'Doppelte Anführungszeichen "';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['single']      = 'Einfache Anführungszeichen \'';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['colnum']      = 'Spaltennummer';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['field']       = 'Feld';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['confirmed']   = '%s neue Abonnenten wurden importiert.';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['overwritten'] = '%s Abonnenten wurden überschrieben.';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['skipped']     = '%s Einträge wurden übersprungen.';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['invalid']     = '%s ungültige Einträge wurden übersprungen.';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['edit']        = 'CSV-Import';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['import_legend']    = 'Import';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['format_legend']    = 'CSV Format';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['personals_legend'] = 'Persönliche Daten';


/**
 * Errors
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['emailMissing'] = 'Das Feld E-Mail ist obligatorisch!';
$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['doubles']      = 'Jede Spalte und jedes Feld darf nur ein mal ausgewählt werden!';
