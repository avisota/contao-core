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
$GLOBALS['TL_LANG']['orm_avisota_message']['subject']              = array(
	'Betreff',
	'Bitte geben Sie den Betreff des Newsletters ein.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['alias']                = array(
	'Newsletteralias',
	'Der Newsletteralias ist eine eindeutige Referenz, die anstelle der numerischen Newsletter-Id aufgerufen werden kann.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['addFile']              = array(
	'Dateien anhängen',
	'Dem Newsletter eine oder mehrere Dateien anhängen.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['files']                = array(
	'Dateianhänge',
	'Bitte wählen Sie die anzuhängenden Dateien aus der Dateiübersicht.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['setRecipients']        = array(
	'Empfänger zuweisen',
	'Wählen Sie für diesen Newsletter eine neue Liste von Empfängern aus.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['recipients']           = array(
	'Empfänger',
	'Wählen Sie hier die Empfänger aus.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['setTheme']             = array(
	'Layout zuweisen',
	'Wählen Sie für diesen Newsletter ein neues Layout aus.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['theme']                = array(
	'Layout',
	'Wählen Sie hier das Layouts aus.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['setTransport']         = array(
	'Transportmodul zuweisen',
	'Wählen Sie für diesen Newsletter ein neues Transportmodul aus.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['transport']            = array(
	'Transportmodul',
	'Wählen Sie hier das Transportmodul aus.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['tstamp']               = array(
	'Änderungsdatum',
	'Datum und Uhrzeit der letzten Änderung'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['sendPreviewToUser']    = array(
	'Testsendung an Benutzer',
	'Die Testsendung des Newsletters an diesen Benutzer versenden.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['sendPreviewToEmail']   = array(
	'Testsendung an E-Mail',
	'Die Testsendung des Newsletters an diese E-Mail-Adresse versenden. Geben Sie hier eine E-Mail Adresse an, wird der Versand an die Benutzerauswahl ignoriert.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['preview_mode']         = array(
	'Vorschaumodus',
	'Den Vorschaumodus wechseln.',
	'HTML Vorschau',
	'Plain Text Vorschau'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['preview_personalized'] = array(
	'Personalisieren',
	'Die Vorschau personalisieren.',
	'Keine',
	'Anonym',
	'Persönlich'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['newsletter_legend'] = 'Newsletter';
$GLOBALS['TL_LANG']['orm_avisota_message']['recipient_legend']  = 'Empfänger';
$GLOBALS['TL_LANG']['orm_avisota_message']['theme_legend']      = 'Layout-Einstellungen';
$GLOBALS['TL_LANG']['orm_avisota_message']['transport_legend']  = 'Transport-Einstellungen';
$GLOBALS['TL_LANG']['orm_avisota_message']['attachment_legend'] = 'Dateianhänge';
$GLOBALS['TL_LANG']['orm_avisota_message']['headline']          = 'Newsletter ansehen und versenden';
$GLOBALS['TL_LANG']['orm_avisota_message']['from']              = 'Absender';
$GLOBALS['TL_LANG']['orm_avisota_message']['live']              = 'Vorschau aktualisieren';
$GLOBALS['TL_LANG']['orm_avisota_message']['preview']           = 'Testsendung';
$GLOBALS['TL_LANG']['orm_avisota_message']['unsubscribe']       = 'vom Newsletter abmelden';
$GLOBALS['TL_LANG']['orm_avisota_message']['salutation']        = 'Sehr geehrte/-r {fullname}';
$GLOBALS['TL_LANG']['orm_avisota_message']['salutation_male']   = 'Sehr geehrter Herr {fullname}';
$GLOBALS['TL_LANG']['orm_avisota_message']['salutation_female'] = 'Sehr geehrte Frau {fullname}';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['created_from_draft']  = 'Newsletter wurde erstellt.';
$GLOBALS['TL_LANG']['orm_avisota_message']['view']                = array(
	'Ansehen und Versenden',
	'Den Newsletter ansehen und versenden.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['view_only']           = array('Ansehen', 'Den Newsletter ansehen.');
$GLOBALS['TL_LANG']['orm_avisota_message']['send']                = array('Versenden', 'Den Newsletter versenden.');
$GLOBALS['TL_LANG']['orm_avisota_message']['sended']              = 'versendet am %s';
$GLOBALS['TL_LANG']['orm_avisota_message']['confirm']             = 'Der Newsletter wurde an alle Empfänger versendet.';
$GLOBALS['TL_LANG']['orm_avisota_message']['confirmPreview']      = 'Die Testsendung wurde an %s versendet.';
$GLOBALS['TL_LANG']['orm_avisota_message']['online']              = 'Probleme mit der Darstellung? Den Newsletter Online ansehen.';
$GLOBALS['TL_LANG']['orm_avisota_message']['list']                = 'Verteiler';
$GLOBALS['TL_LANG']['orm_avisota_message']['member']              = 'Mitglieder';
$GLOBALS['TL_LANG']['orm_avisota_message']['mgroup']              = 'Mitgliedergruppe';
$GLOBALS['TL_LANG']['orm_avisota_message']['notSend']             = 'noch nicht versendet';
$GLOBALS['TL_LANG']['orm_avisota_message']['inheritFromCategory'] = '- von Kategorie übernehmen -';
$GLOBALS['TL_LANG']['orm_avisota_message']['fallback']            = '(fallback)';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['new']               = array(
	'Neuer Newsletter',
	'Einen neuen Newsletter erstellen'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['create_from_draft'] = array(
	'Neuer Newsletter aus Vorlage',
	'Einen neuen Newsletter aus Vorlage erstellen'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['show']              = array(
	'Newsletterdetails',
	'Details des Newsletter ID %s anzeigen'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['copy']              = array(
	'Newsletter duplizieren',
	'Newsletter ID %s duplizieren'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['delete']            = array(
	'Newsletter löschen',
	'Newsletter ID %s löschen'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['edit']              = array(
	'Newsletter bearbeiten',
	'Newsletter ID %s bearbeiten'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['editheader']        = array(
	'Newslettereinstellungen bearbeiten',
	'Einstellungen des Newsletter ID %s bearbeiten'
);


/**
 * Personalisation
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['anonymous']['salutation'] = 'Sehr geehrte/-r {fullname}';
$GLOBALS['TL_LANG']['orm_avisota_message']['anonymous']['name']       = 'Abonnent/-in';
$GLOBALS['TL_LANG']['orm_avisota_message']['anonymous']['fullname']   = 'Abonnent/-in';
$GLOBALS['TL_LANG']['orm_avisota_message']['anonymous']['shortname']  = 'Abonnent/-in';


/**
 * Errors
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['transport_error'] = 'Beim Versand ist ein Fehler aufgetreten, der noch nicht weiter analysiert wurde.<br>
Bitte übermitteln Sie folgende Meldung an den Entwickler.<br/>
&mdash; via <a href="http://contao-forge.org/projects/avisota/issues" onclick="window.open(this.href); return false;">Contao Forge</a><br/>
&mdash; via <a href="http://www.contao-community.de/forumdisplay.php?121-Avisota" onclick="window.open(this.href); return false;">Contao Community Forum</a><br/>
&mdash; via <a href="mailto:info@infinitysoft.de">E-Mail</a>';
