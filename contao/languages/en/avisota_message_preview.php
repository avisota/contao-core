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
 * @license    LGPL-3.0+
 * @filesource
 */


// TODO
/*
$GLOBALS['TL_LANG']['avisota_message_preview']['preview_mode']         = array(
	'Vorschaumodus',
	'Den Vorschaumodus wechseln.',
	'HTML Vorschau',
	'Plain Text Vorschau'
);
$GLOBALS['TL_LANG']['avisota_message_preview']['preview_personalized'] = array(
	'Personalisieren',
	'Die Vorschau personalisieren.',
	'Keine',
	'Anonym',
	'Persönlich'
);
*/

/**
 * Fields
 */
$GLOBALS['TL_LANG']['avisota_message_preview']['sendPreviewToUser']  = array(
	'Send to user',
	'Send an example to a user.'
);
$GLOBALS['TL_LANG']['avisota_message_preview']['sendPreviewToEmail'] = array(
	'Send to email',
	'Send an example to an email.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['avisota_message_preview']['headline']       = 'View and send message';
$GLOBALS['TL_LANG']['avisota_message_preview']['previewToUser']  = 'Send preview to user';
$GLOBALS['TL_LANG']['avisota_message_preview']['previewToEmail'] = 'Send preview to email';
$GLOBALS['TL_LANG']['avisota_message_preview']['sendNow']        = 'Send message now';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['avisota_message_preview']['previewInNewWindow'] = 'View in new window';
$GLOBALS['TL_LANG']['avisota_message_preview']['sendPreview']        = 'Send preview';
$GLOBALS['TL_LANG']['avisota_message_preview']['sendMessage']        = 'Send message to recipients now';


/**
 * Help
 */
$GLOBALS['TL_LANG']['avisota_message_preview']['helpSendNow']        = 'Send this message to %d recipients immediately.';


/**
 * Messages
 */
$GLOBALS['TL_LANG']['avisota_message_preview']['previewSend'] = 'Preview send to %s';
$GLOBALS['TL_LANG']['avisota_message_preview']['confirmSend'] = 'Are you sure you wan\'t to send this newsletter now? The sending process will start immediately!';
$GLOBALS['TL_LANG']['avisota_message_preview']['messagesEnqueued'] = '(turn %2$d) %1$d messages enqueued.';


/*
$GLOBALS['TL_LANG']['avisota_message_preview']['from']              = 'Absender';
$GLOBALS['TL_LANG']['avisota_message_preview']['live']              = 'Vorschau aktualisieren';
$GLOBALS['TL_LANG']['avisota_message_preview']['unsubscribe']       = 'vom Mailing abmelden';
$GLOBALS['TL_LANG']['avisota_message_preview']['salutation']        = 'Sehr geehrte/-r {fullname}';
$GLOBALS['TL_LANG']['avisota_message_preview']['salutation_male']   = 'Sehr geehrter Herr {fullname}';
$GLOBALS['TL_LANG']['avisota_message_preview']['salutation_female'] = 'Sehr geehrte Frau {fullname}';
*/
