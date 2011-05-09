<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['subject']              = array('Betreff', 'Bitte geben Sie den Betreff des Newsletters ein.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['alias']                = array('Newsletteralias', 'Der Newsletteralias ist eine eindeutige Referenz, die anstelle der numerischen Newsletter-Id aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['addFile']              = array('Dateien anhängen', 'Dem Newsletter eine oder mehrere Dateien anhängen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['files']                = array('Dateianhänge', 'Bitte wählen Sie die anzuhängenden Dateien aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_html']        = array('HTML E-Mail-Template', 'Hier können Sie das HTML E-Mail-Template auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_plain']       = array('Plain Text E-Mail-Template', 'Hier können Sie das Plain Text E-Mail-Template auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipients']           = array('Empfänger', 'Wählen Sie hier die Empfänger aus.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['tstamp']               = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sendPreviewTo']        = array('Testsendung an', 'Die Testsendung des Newsletters an diese E-Mail-Adresse versenden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_mode']         = array('Vorschaumodus', 'Den Vorschaumodus wechseln.', 'HTML Vorschau', 'Plain Text Vorschau');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_personalized'] = array('Personalisieren', 'Die Vorschau personalisieren.', 'Keine', 'Anonym', 'Persönlich');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['newsletter_legend']  = 'Newsletter';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipient_legend']   = 'Empfänger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['attachment_legend']  = 'Dateianhänge';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_legend']    = 'Template-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['headline']           = 'Newsletter ansehen und versenden';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['from']               = 'Absender';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['live']               = 'Vorschau aktualisieren';
#$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview']            = 'Testsendung';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sendConfirm']        = 'Soll der Newsletter wirklich verschickt werden?';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['unsubscribe']        = 'vom Newsletter abmelden';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation']         = 'Sehr geehrte/-r';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_male']    = 'Sehr geehrter Herr';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_female']  = 'Sehr geehrte Frau';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['created_from_draft'] = 'Newsletter wurde erstellt.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['view']               = array('Ansehen und Versenden', 'Den Newsletter ansehen und versenden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['send']               = array('Versenden', 'Den Newsletter versenden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sended']             = 'versendet am %s';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirm']            = 'Der Newsletter wurde an alle Empfänger versendet.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['online']             = 'Probleme mit der Darstellung? Den Newsletter Online ansehen.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['list']               = 'Verteiler';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['member']             = 'Mitglieder';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['mgroup']             = 'Mitgliedergruppe';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['new']               = array('Neuer Newsletter', 'Einen neuen Newsletter erstellen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['create_from_draft'] = array('Neuer Newsletter aus Vorlage', 'Einen neuen Newsletter aus Vorlage erstellen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['show']              = array('Newsletterdetails', 'Details des Newsletter ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['copy']              = array('Newsletter duplizieren', 'Newsletter ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['delete']            = array('Newsletter löschen', 'Newsletter ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['edit']              = array('Newsletter bearbeiten', 'Newsletter ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['editheader']        = array('Newslettereinstellungen bearbeiten', 'Einstellungen des Newsletter ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview']           = array('Newsletter ansehen', 'Den Newsletter ID %s ansehen');


/**
 * Personalisation
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['salutation'] = 'Sehr geehrte/-r';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['name']       = 'Abonnent/-in';

?>