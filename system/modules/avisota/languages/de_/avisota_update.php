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
 * Avisota update
 */
$GLOBALS['TL_LANG']['avisota_update']['headline']           = 'Avisota Update';
$GLOBALS['TL_LANG']['avisota_update']['previousVersion']    = 'vorherige Version';
$GLOBALS['TL_LANG']['avisota_update']['unknownVersion']     = 'unbekannt';
$GLOBALS['TL_LANG']['avisota_update']['installedVersion']   = 'installierte Version';
$GLOBALS['TL_LANG']['avisota_update']['moreVersionUpdates'] = 'Alle Informationen zum Update';
$GLOBALS['TL_LANG']['avisota_update']['doUpdate']           = 'Aktualisierung durchführen';
$GLOBALS['TL_LANG']['avisota_update']['doDatabaseUpdate']   = 'Datenbank aktualisieren';
$GLOBALS['TL_LANG']['avisota_update']['updateSuccess']      = 'Aktualisierung erfolgreich';
$GLOBALS['TL_LANG']['avisota_update']['updateFailed']       = 'Aktualisierung nicht erfolgreich, prüfen Sie das Systemlog für weitere Details';
$GLOBALS['TL_LANG']['avisota_update']['moreUpdates']        = 'Es sind weitere Aktualisierungen durchzuführen, klicken Sie <strong>Aktualisierung durchführen</strong> um fortzufahren.';

/**
 * Update 0.4.5
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['0.4.5'] = array
(
	'Update Version 0.4.5',
	'<h3>Update Hinweis</h3>
<ul>
	<li class="update">Datenbank für Newsletter Bereiche aktualisieren.</li>
</ul>'
);

/**
 * Update 1.5.0
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['1.5.0'] = array
(
	'Update Version 1.5.0',
	'<h3>Update Hinweis</h3>
<ul>
	<li class="update">Datenbank aktualisieren, um Leistung des Postausgangs zu verbessern.</li>
</ul>'
);

/**
 * Update 1.5.1
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['1.5.1'] = array
(
	'Update Version 1.5.1',
	'<h3>Update Hinweis</h3>
<ul>
	<li class="update">Statistik-Datenbank aufräumen und Links mit E-Mail Adresse zusammen fassen.</li>
	<li class="update">Statistik-Datenbank aufräumen und HTML kodierte URLs säubern.</li>
</ul>'
);

/**
 * Update 1.6.0
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['1.6.0'] = array
(
	'Update Version 1.6.0',
	'<h3>Die wichtigsten Neuerungen</h3>
<ul>
	<li class="feature">Neues Modul zur Auflistung der Newsletter (Modul: Newsletter-Liste), ähnlich einer News-Liste.</li>
	<li class="feature">Neues Modul zum Anzeigen eines Newlsetters in der Website (Modul: Newsletter-Reader), ähnlich einem News-Reader.</li>
</ul>'
);

/**
 * Update 2.0.0 u1 - reorganise recipient data
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['2.0.0-u1'] = array
(
	'Update Version 2.0.0 - Abonnenten und Verteiler trennen',
	'<p>Die Abonnenten und Verteiler werden von einander getrennt.
	Die personenbezogenen Daten werden in einen Datensatz zusammengeführt.
	Dabei werden befüllte Felder immer unbefüllten Feldern vorgezogen.
	Bei Konflikten werden die Daten des jüngeren Datensatzes verwendet.</p>'
);

/**
 * Update 2.0.0 u1 - create transport modules
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['2.0.0-u2'] = array
(
	'Update Version 2.0.0 - Transportmodule anlegen',
	'<p>Aus den vorhandenen Einstellungen wird für jede Kategorie ein Transportmodul angelegt.
	 Sie können dieses Update überspringen, aber beachten Sie dass Sie die Transportmodule dann von Hand anlegen müssen!</p>'
);

/**
 * Update 2.0.0 u2 - create recipient sources
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['2.0.0-u3'] = array
(
	'Update Version 2.0.0 - Empfängerquellen anlegen',
	'<p>Aus den vorhandenen Einstellungen wird werden entsprechende Abonnentenquellen angelegt.
	Sie können dieses Update überspringen, aber beachten Sie dass Sie die Abonnentenquellen dann von Hand anlegen müssen!</p>'
);
