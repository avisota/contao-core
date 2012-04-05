<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


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
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sendPreviewToUser']    = array('Testsendung an Benutzer', 'Die Testsendung des Newsletters an diesen Benutzer versenden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sendPreviewToEmail']   = array('Testsendung an E-Mail', 'Die Testsendung des Newsletters an diese E-Mail-Adresse versenden. Geben Sie hier eine E-Mail Adresse an, wird der Versand an die Benutzerauswahl ignoriert.');
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
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview']            = 'Testsendung';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['unsubscribe']        = 'vom Newsletter abmelden';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation']         = 'Sehr geehrte/-r {fullname}';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_male']    = 'Sehr geehrter Herr {fullname}';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_female']  = 'Sehr geehrte Frau {fullname}';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['view']           = array('Ansehen und Versenden', 'Den Newsletter ansehen und versenden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['view_only']      = array('Ansehen', 'Den Newsletter ansehen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['send']           = array('Versenden', 'Den Newsletter versenden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sended']         = 'versendet am %s';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirm']        = 'Der Newsletter wurde an alle Empfänger versendet.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirmPreview'] = 'Die Testsendung wurde an %s versendet.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['online']         = 'Probleme mit der Darstellung? Den Newsletter Online ansehen.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['list']           = 'Verteiler';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['member']         = 'Mitglieder';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['mgroup']         = 'Mitgliedergruppe';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['notSend']        = 'noch nicht versendet';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['new']         = array('Neuer Newsletter', 'Einen neuen Newsletter erstellen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['show']        = array('Newsletterdetails', 'Details des Newsletter ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['copy']        = array('Newsletter duplizieren', 'Newsletter ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['delete']      = array('Newsletter löschen', 'Newsletter ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['edit']        = array('Newsletter bearbeiten', 'Newsletter ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['editheader']  = array('Newslettereinstellungen bearbeiten', 'Einstellungen des Newsletter ID %s bearbeiten');


/**
 * Personalisation
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['salutation'] = 'Sehr geehrte/-r {fullname}';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['name']       = 'Abonnent/-in';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['fullname']   = 'Abonnent/-in';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['shortname']  = 'Abonnent/-in';


/**
 * Errors
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['transport_error'] = 'Beim Versand ist ein Fehler aufgetreten, der noch nicht weiter analysiert wurde.<br>
Bitte übermitteln Sie folgende Meldung an den Entwickler.<br/>
&mdash; via <a href="http://contao-forge.org/projects/avisota/issues" onclick="window.open(this.href); return false;">Contao Forge</a><br/>
&mdash; via <a href="http://www.contao-community.de/forumdisplay.php?121-Avisota" onclick="window.open(this.href); return false;">Contao Community Forum</a><br/>
&mdash; via <a href="mailto:info@infinitysoft.de">E-Mail</a>';

?>