<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_migrate']['source'] = array(
	'Newsletter',
	'Wählen Sie hier die den Newsletter aus, aus dem Sie Abonnenten migrieren möchten.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_migrate']['personals'] = array(
	'Persönliche Daten übernehmen',
	'Übernimmt vorhandene persönliche Daten aus der Mitgliedertabelle.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_migrate']['force'] = array(
	'Migration erzwingen',
	'Warnung: Sie sollten den Wunsch eines Abonnenten Ihren Newsletter nicht mehr erhalten zu wollen respektieren, es besteht die Möglichkeit dass Sie rechtliche Konsequenzen zu befürchten haben, lassen Sie sich diesbezüglich von einem Anwalt beraten! Nutzen Sie diese Option mit Bedacht und nur dann, wenn Sie wissen was Sie tun! Wählen Sie diese Option, wird die interne Blacklist ignoriert. Die Blacklist sorgt dafür, dass Abonnenten die sich abgemeldet haben nicht durch den Import wieder hinzugefügt werden.'
);


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_migrate']['migrated'] = '%s Abonnenten wurden migriert.';
$GLOBALS['TL_LANG']['orm_avisota_recipient_migrate']['edit']     = 'Abonnenten migrieren';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_migrate']['migrate_legend'] = 'Abonnenten migrieren';
