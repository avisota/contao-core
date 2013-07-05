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
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['title']                = array(
	'Name',
	'Bitte geben Sie einen Namen für die Vorlage ein.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['alias']                = array(
	'Alias',
	'Der Alias ist eine eindeutige Referenz, die anstelle der numerischen Id aufgerufen werden kann.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['description']          = array(
	'Beschreibung',
	'Geben Sie hier eine Beschreibung für die Vorlage an.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['addFile']              = array(
	'Dateien anhängen',
	'Dem Newsletter eine oder mehrere Dateien anhängen.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['files']                = array(
	'Dateianhänge',
	'Bitte wählen Sie die anzuhängenden Dateien aus der Dateiübersicht.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['tstamp']               = array(
	'Änderungsdatum',
	'Datum und Uhrzeit der letzten Änderung'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['sendPreviewTo']        = array(
	'Testsendung an',
	'Die Testsendung des Newsletters an diese E-Mail-Adresse versenden.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['preview_mode']         = array(
	'Vorschaumodus',
	'Den Vorschaumodus wechseln.',
	'HTML Vorschau',
	'Plain Text Vorschau'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['preview_personalized'] = array(
	'Personalisieren',
	'Die Vorschau personalisieren.',
	'Keine',
	'Anonym',
	'Persönlich'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['newsletter_legend'] = 'Vorlage';
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['attachment_legend'] = 'Dateianhänge';
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['template_legend']   = 'Template-Einstellungen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['new']        = array('Neue Vorlage', 'Eine neue Vorlage erstellen');
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['show']       = array(
	'Vorlagedetails',
	'Details der Vorlage ID %s anzeigen'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['copy']       = array(
	'Vorlage duplizieren',
	'Vorlage ID %s duplizieren'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['delete']     = array('Vorlage löschen', 'Vorlage ID %s löschen');
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['edit']       = array(
	'Vorlage bearbeiten',
	'Vorlage ID %s bearbeiten'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['editheader'] = array(
	'Vorlageeinstellungen bearbeiten',
	'Einstellungen der Vorlage ID %s bearbeiten'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['preview']    = array(
	'Vorlage ansehen',
	'Die Vorlage ID %s ansehen'
);


/**
 * Messages
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_draft']['missing_template'] = 'Eine Vorlage kann nur angezeigt werden, wenn ein Template ausgewählt wurde.';
