<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['title']                = array(
	'Name',
	'Bitte geben Sie einen Namen für die Vorlage ein.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['alias']                = array(
	'Alias',
	'Der Alias ist eine eindeutige Referenz, die anstelle der numerischen Id aufgerufen werden kann.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['description']          = array(
	'Beschreibung',
	'Geben Sie hier eine Beschreibung für die Vorlage an.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['addFile']              = array(
	'Dateien anhängen',
	'Dem Newsletter eine oder mehrere Dateien anhängen.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['files']                = array(
	'Dateianhänge',
	'Bitte wählen Sie die anzuhängenden Dateien aus der Dateiübersicht.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['tstamp']               = array(
	'Änderungsdatum',
	'Datum und Uhrzeit der letzten Änderung'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['sendPreviewTo']        = array(
	'Testsendung an',
	'Die Testsendung des Newsletters an diese E-Mail-Adresse versenden.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['preview_mode']         = array(
	'Vorschaumodus',
	'Den Vorschaumodus wechseln.',
	'HTML Vorschau',
	'Plain Text Vorschau'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['preview_personalized'] = array(
	'Personalisieren',
	'Die Vorschau personalisieren.',
	'Keine',
	'Anonym',
	'Persönlich'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['newsletter_legend'] = 'Vorlage';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['attachment_legend'] = 'Dateianhänge';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['template_legend']   = 'Template-Einstellungen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['new']        = array('Neue Vorlage', 'Eine neue Vorlage erstellen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['show']       = array(
	'Vorlagedetails',
	'Details der Vorlage ID %s anzeigen'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['copy']       = array(
	'Vorlage duplizieren',
	'Vorlage ID %s duplizieren'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['delete']     = array('Vorlage löschen', 'Vorlage ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['edit']       = array(
	'Vorlage bearbeiten',
	'Vorlage ID %s bearbeiten'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['editheader'] = array(
	'Vorlageeinstellungen bearbeiten',
	'Einstellungen der Vorlage ID %s bearbeiten'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['preview']    = array(
	'Vorlage ansehen',
	'Die Vorlage ID %s ansehen'
);


/**
 * Messages
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['missing_template'] = 'Eine Vorlage kann nur angezeigt werden, wenn ein Template ausgewählt wurde.';
