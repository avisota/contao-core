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
$GLOBALS['TL_LANG']['orm_avisota_mailing']['subject']       = array(
	'Subject',
	'Please enter the subject of this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['alias']         = array(
	'Alias',
	'The newsletter alias is a unique reference to the newsletter which can be called instead of its numeric ID.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['description']   = array(
	'Description',
	'Please enter a short description for this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['keywords']      = array(
	'Keywords',
	'Please enter the keywords for this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['addFile']       = array(
	'Attach files',
	'Attach additional files to the newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['files']         = array(
	'Attachments',
	'Please chose the attachments.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['setRecipients'] = array(
	'Select recipients',
	'Select the recipients for this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['recipients']    = array(
	'Recipients',
	'Please chose the recipients for this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['setTheme']      = array(
	'Assign theme',
	'Assign a theme to this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['theme']         = array(
	'Theme',
	'Please chose the newsletter theme.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['setTransport']  = array(
	'Assign transport module',
	'Assign a transport module to this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['transport']     = array(
	'Transport module',
	'Please chose the transport module.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['tstamp']        = array(
	'Revision date',
	'Date and time of the latest revision'
);

// TODO
/*
$GLOBALS['TL_LANG']['orm_avisota_mailing']['sendPreviewToUser']    = array(
	'Testsendung an Benutzer',
	'Die Testsendung des Newsletters an diesen Benutzer versenden.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['sendPreviewToEmail']   = array(
	'Testsendung an E-Mail',
	'Die Testsendung des Newsletters an diese E-Mail-Adresse versenden. Geben Sie hier eine E-Mail Adresse an, wird der Versand an die Benutzerauswahl ignoriert.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['preview_mode']         = array(
	'Vorschaumodus',
	'Den Vorschaumodus wechseln.',
	'HTML Vorschau',
	'Plain Text Vorschau'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['preview_personalized'] = array(
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
$GLOBALS['TL_LANG']['orm_avisota_mailing']['newsletter_legend'] = 'Newsletter';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['meta_legend']       = 'Details';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['recipient_legend']  = 'Recipient';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['theme_legend']      = 'Theme settings';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['transport_legend']  = 'Transport settings';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['attachment_legend'] = 'Attachments';

/*
$GLOBALS['TL_LANG']['orm_avisota_mailing']['headline']          = 'Newsletter ansehen und versenden';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['from']              = 'Absender';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['live']              = 'Vorschau aktualisieren';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['preview']           = 'Testsendung';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['unsubscribe']       = 'vom Newsletter abmelden';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['salutation']        = 'Sehr geehrte/-r {fullname}';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['salutation_male']   = 'Sehr geehrter Herr {fullname}';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['salutation_female'] = 'Sehr geehrte Frau {fullname}';
*/

/**
 * Reference
 */
/*
$GLOBALS['TL_LANG']['orm_avisota_mailing']['created_from_draft']  = 'Newsletter wurde erstellt.';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['view']                = array(
	'Ansehen und Versenden',
	'Den Newsletter ansehen und versenden.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['view_only']           = array('Ansehen', 'Den Newsletter ansehen.');
$GLOBALS['TL_LANG']['orm_avisota_mailing']['send']                = array('Versenden', 'Den Newsletter versenden.');
$GLOBALS['TL_LANG']['orm_avisota_mailing']['sended']              = 'versendet am %s';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['confirm']             = 'Der Newsletter wurde an alle Empfänger versendet.';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['confirmPreview']      = 'Die Testsendung wurde an %s versendet.';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['online']              = 'Probleme mit der Darstellung? Den Newsletter Online ansehen.';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['list']                = 'Verteiler';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['member']              = 'Mitglieder';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['mgroup']              = 'Mitgliedergruppe';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['notSend']             = 'noch nicht versendet';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['inheritFromCategory'] = '- von Kategorie übernehmen -';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['fallback']            = '(fallback)';
*/

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing']['new']               = array(
	'New newsletter',
	'Add a new newsletter'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['create_from_draft'] = array(
	'New newsletter from boilerplate',
	'Add a new newsletter from boilerplate'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['show']              = array(
	'Newsletter details',
	'Show the details of newsletter ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['copy']              = array(
	'Duplicate newsletter',
	'Duplicate newsletter ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['delete']            = array(
	'Delete newsletter',
	'Delete newsletter ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['edit']              = array(
	'Edit newsletter',
	'Edit newsletter ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['editheader']        = array(
	'Edit newsletter settings',
	'Edit newsletter settings ID %s'
);


/**
 * Personalisation
 */
/*
$GLOBALS['TL_LANG']['orm_avisota_mailing']['anonymous']['salutation'] = 'Sehr geehrte/-r {fullname}';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['anonymous']['name']       = 'Abonnent/-in';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['anonymous']['fullname']   = 'Abonnent/-in';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['anonymous']['shortname']  = 'Abonnent/-in';
*/


/**
 * Errors
 */
/*
$GLOBALS['TL_LANG']['orm_avisota_mailing']['transport_error'] = 'Beim Versand ist ein Fehler aufgetreten, der noch nicht weiter analysiert wurde.<br>
Bitte übermitteln Sie folgende Meldung an den Entwickler.<br/>
&mdash; via <a href="http://contao-forge.org/projects/avisota/issues" onclick="window.open(this.href); return false;">Contao Forge</a><br/>
&mdash; via <a href="http://www.contao-community.de/forumdisplay.php?121-Avisota" onclick="window.open(this.href); return false;">Contao Community Forum</a><br/>
&mdash; via <a href="mailto:info@infinitysoft.de">E-Mail</a>';
*/
