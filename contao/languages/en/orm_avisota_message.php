<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['subject']       = array(
	'Subject',
	'Please enter the subject of this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['alias']         = array(
	'Alias',
	'The newsletter alias is a unique reference to the newsletter which can be called instead of its numeric ID.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['description']   = array(
	'Description',
	'Please enter a short description for this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['keywords']      = array(
	'Keywords',
	'Please enter the keywords for this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['addFile']       = array(
	'Attach files',
	'Attach additional files to the newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['files']         = array(
	'Attachments',
	'Please chose the attachments.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['setRecipients'] = array(
	'Select recipients',
	'Select the recipients for this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['recipients']    = array(
	'Recipients',
	'Please chose the recipients for this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['setTheme']      = array(
	'Assign theme',
	'Assign a theme to this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['theme']         = array(
	'Theme',
	'Please chose the newsletter theme.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['setTransport']  = array(
	'Assign transport module',
	'Assign a transport module to this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['transport']     = array(
	'Transport module',
	'Please chose the transport module.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['tstamp']        = array(
	'Revision date',
	'Date and time of the latest revision'
);

// TODO
/*
$GLOBALS['TL_LANG']['orm_avisota_message']['sendPreviewToUser']    = array(
	'Testsendung an Benutzer',
	'Die Testsendung des Mailings an diesen Benutzer versenden.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['sendPreviewToEmail']   = array(
	'Testsendung an E-Mail',
	'Die Testsendung des Mailings an diese E-Mail-Adresse versenden. Geben Sie hier eine E-Mail Adresse an, wird der Versand an die Benutzerauswahl ignoriert.'
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
*/


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['newsletter_legend'] = 'Message';
$GLOBALS['TL_LANG']['orm_avisota_message']['meta_legend']       = 'Details';
$GLOBALS['TL_LANG']['orm_avisota_message']['recipient_legend']  = 'Recipient';
$GLOBALS['TL_LANG']['orm_avisota_message']['theme_legend']      = 'Theme settings';
$GLOBALS['TL_LANG']['orm_avisota_message']['transport_legend']  = 'Transport settings';
$GLOBALS['TL_LANG']['orm_avisota_message']['attachment_legend'] = 'Attachments';

/*
$GLOBALS['TL_LANG']['orm_avisota_message']['headline']          = 'Message ansehen und versenden';
$GLOBALS['TL_LANG']['orm_avisota_message']['from']              = 'Absender';
$GLOBALS['TL_LANG']['orm_avisota_message']['live']              = 'Vorschau aktualisieren';
$GLOBALS['TL_LANG']['orm_avisota_message']['preview']           = 'Testsendung';
$GLOBALS['TL_LANG']['orm_avisota_message']['unsubscribe']       = 'vom Mailing abmelden';
$GLOBALS['TL_LANG']['orm_avisota_message']['salutation']        = 'Sehr geehrte/-r {fullname}';
$GLOBALS['TL_LANG']['orm_avisota_message']['salutation_male']   = 'Sehr geehrter Herr {fullname}';
$GLOBALS['TL_LANG']['orm_avisota_message']['salutation_female'] = 'Sehr geehrte Frau {fullname}';
*/

/**
 * Reference
 */
/*
$GLOBALS['TL_LANG']['orm_avisota_message']['created_from_draft']  = 'Message wurde erstellt.';
$GLOBALS['TL_LANG']['orm_avisota_message']['view']                = array(
	'Ansehen und Versenden',
	'Den Message ansehen und versenden.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['view_only']           = array('Ansehen', 'Den Message ansehen.');
$GLOBALS['TL_LANG']['orm_avisota_message']['send']                = array('Versenden', 'Den Message versenden.');
$GLOBALS['TL_LANG']['orm_avisota_message']['sended']              = 'versendet am %s';
$GLOBALS['TL_LANG']['orm_avisota_message']['confirm']             = 'Der Message wurde an alle Empfänger versendet.';
$GLOBALS['TL_LANG']['orm_avisota_message']['confirmPreview']      = 'Die Testsendung wurde an %s versendet.';
$GLOBALS['TL_LANG']['orm_avisota_message']['online']              = 'Probleme mit der Darstellung? Den Message Online ansehen.';
$GLOBALS['TL_LANG']['orm_avisota_message']['list']                = 'Verteiler';
$GLOBALS['TL_LANG']['orm_avisota_message']['member']              = 'Mitglieder';
$GLOBALS['TL_LANG']['orm_avisota_message']['mgroup']              = 'Mitgliedergruppe';
$GLOBALS['TL_LANG']['orm_avisota_message']['notSend']             = 'noch nicht versendet';
$GLOBALS['TL_LANG']['orm_avisota_message']['inheritFromCategory'] = '- von Kategorie übernehmen -';
$GLOBALS['TL_LANG']['orm_avisota_message']['fallback']            = '(fallback)';
*/

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['new']               = array(
	'New newsletter',
	'Add a new newsletter'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['create_from_draft'] = array(
	'New newsletter from boilerplate',
	'Add a new newsletter from boilerplate'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['show']              = array(
	'Message details',
	'Show the details of newsletter ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['copy']              = array(
	'Duplicate newsletter',
	'Duplicate newsletter ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['delete']            = array(
	'Delete newsletter',
	'Delete newsletter ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['edit']              = array(
	'Edit newsletter',
	'Edit newsletter ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['editheader']        = array(
	'Edit newsletter settings',
	'Edit newsletter settings ID %s'
);


/**
 * Personalisation
 */
/*
$GLOBALS['TL_LANG']['orm_avisota_message']['anonymous']['salutation'] = 'Sehr geehrte/-r {fullname}';
$GLOBALS['TL_LANG']['orm_avisota_message']['anonymous']['name']       = 'Abonnent/-in';
$GLOBALS['TL_LANG']['orm_avisota_message']['anonymous']['fullname']   = 'Abonnent/-in';
$GLOBALS['TL_LANG']['orm_avisota_message']['anonymous']['shortname']  = 'Abonnent/-in';
*/


/**
 * Errors
 */
/*
$GLOBALS['TL_LANG']['orm_avisota_message']['transport_error'] = 'Beim Versand ist ein Fehler aufgetreten, der noch nicht weiter analysiert wurde.<br>
Bitte übermitteln Sie folgende Meldung an den Entwickler.<br/>
&mdash; via <a href="http://contao-forge.org/projects/avisota/issues" onclick="window.open(this.href); return false;">Contao Forge</a><br/>
&mdash; via <a href="http://www.contao-community.de/forumdisplay.php?121-Avisota" onclick="window.open(this.href); return false;">Contao Community Forum</a><br/>
&mdash; via <a href="mailto:info@infinitysoft.de">E-Mail</a>';
*/
