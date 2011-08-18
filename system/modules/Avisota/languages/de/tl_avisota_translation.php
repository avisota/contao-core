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
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:latest_link']                  = array('Link zum aktuellen Newsletter (Insert-Tag)', 'Hier können Sie den Link zum aktuellen Newsletter ändern. Verwenden Sie %s um die URL einzufügen.');

$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscription__preamble']       = array('Anmeldung - Vorwort', 'Hier können Sie das Vorwort, dass vor der Anmeldemaske angezeigt wird verändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscription__lists']          = array('Anmeldung - Bezeichner für Verteiler', 'Hier können Sie den Bezeichner der Verteiler anpassen. (z.B. Listen, Interessensgebiete)');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscription__email']          = array('Anmeldung - Bezeichner für E-Mail', 'Hier können Sie den Bezeichner für das Feld E-Mail Adresse anpassen.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscription__empty']          = array('Anmeldung - Meldung für Doppelte Anmeldung', 'Hier können Sie die Meldung anpassen, die angezeigt wird wenn bereits ein Abonnement zu der E-Mail Adresse besteht.');

$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscribe__submit']            = array('Anmeldung - Bezeichner für Absenden Button', 'Hier können Sie den Bezeichner des Absenden Buttons ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscribe__mail__subject']     = array('Anmeldung - Betreff für Bestätigungsmail', 'Hier können Sie den Betreff der Bestätigungsmail ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscribe__mail__send']        = array('Anmeldung - Meldung für erste Anmeldung', 'Hier können Sie die Meldung anpassen, die im Anmeldeformular nach der ersten Anmeldung angezeigt wird.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscribe__mail__confirm']     = array('Anmeldung - Meldung für Bestätigung der Anmeldung', 'Hier können Sie die Meldung anpassen, die im Anmeldeformular nach der Bestätigung des Abonnements angezeigt wird.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscribe__mail__rejected']    = array('Anmeldung - Meldung bei falscher/ungültiger E-Mail Adresse', 'Hier können Sie die Meldung anpassen, die im Anmeldeformular angezeigt wird, wenn eine falsche oder ungültige E-Mail Adresse eingetragen wurde.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscribe__mail__html']        = array('Anmeldung - Inhalt der Bestätigungsmail (HTML)', 'Hier können Sie den Inhalt der Bestätigungsmail anpassen. Verwenden Sie <code>%1$s</code> für eine Liste der abonnierten Verteiler und <code>%2$s</code> für die Aktivierungs-URL.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:subscribe__mail__plain']       = array('Anmeldung - Inhalt der Bestätigungsmail (Text)', 'Hier können Sie den Inhalt der Bestätigungsmail anpassen. Verwenden Sie <code>%1$s</code> für eine Liste der abonnierten Verteiler und <code>%2$s</code> für die Aktivierungs-URL.');

$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:unsubscribe__empty']           = array('Abmelden - Meldung wenn kein Abonnement', 'Hier können Sie die Meldung anpassen, wenn man versucht ein nicht existierendes Abonnement zu kündigen.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:unsubscribe__submit']          = array('Abmelden - Bezeichner für Absenden Button', 'Hier können Sie den Bezeichner des Absenden Buttons ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:unsubscribe__mail__subject']   = array('Abmelden - Betreff für Bestätigungsmail', 'Hier können Sie den Betreff der Bestätigungsmail ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:unsubscribe__mail__confirm']   = array('Abmelden - Meldung für Bestätigung der Kündigung', 'Hier können Sie die Meldung anpassen, die im Anmeldeformular nach der Kündigung des Abonnements angezeigt wird.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:unsubscribe__mail__rejected']  = array('Abmelden - Meldung bei falscher/ungültiger E-Mail Adresse', 'Hier können Sie die Meldung anpassen, die im Anmeldeformular angezeigt wird, wenn eine falsche oder ungültige E-Mail Adresse eingetragen wurde.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:unsubscribe__mail__html']      = array('Abmelden - Inhalt der Bestätigungsmail (HTML)', 'Hier können Sie den Inhalt der ersten Bestätigungsmail anpassen. Verwenden Sie <code>%1$s</code> für eine Liste der gekündigten Verteiler und <code>%2$s</code> für die Abonnieren-URL.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:unsubscribe__mail__plain']     = array('Abmelden - Inhalt der Bestätigungsmail (Text)', 'Hier können Sie den Inhalt der ersten Bestätigungsmail anpassen. Verwenden Sie <code>%1$s</code> für eine Liste der gekündigten Verteiler und <code>%2$s</code> für die Abonnieren-URL.');

$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:notification__mail__subject']  = array('Benachrichtigung - Betreff für Benachrichtigungsmail', 'Hier können Sie den Betreff der Bestätigungsmail ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:notification__mail__html']     = array('Benachrichtigung - Inhalt der Benachrichtigungsmail (HTML)', 'Hier können Sie den Inhalt der Benachrichtigungsmail anpassen. Verwenden Sie <code>%1$s</code> für eine Liste der gekündigten Verteiler und <code>%2$s</code> für die Abonnieren-URL.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['avisota:notification__mail__plain']    = array('Benachrichtigung - Inhalt der Benachrichtigungsmail (Text)', 'Hier können Sie den Inhalt der Benachrichtigungsmail anpassen. Verwenden Sie <code>%1$s</code> für eine Liste der gekündigten Verteiler und <code>%2$s</code> für die Abonnieren-URL.');

$GLOBALS['TL_LANG']['tl_avisota_translation']['tl_avisota_recipient:email__0']        = array('Feld - E-Mail', 'Hier können Sie den Bezeichner für das Feld E-Mail ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['tl_avisota_recipient:lists__0']        = array('Feld - Verteiler', 'Hier können Sie den Bezeichner für das Feld Verteiler ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['tl_avisota_recipient:salutation__0']   = array('Feld - Anrede', 'Hier können Sie den Bezeichner für das Feld Anrede ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['tl_avisota_recipient:title__0']        = array('Feld - Titel', 'Hier können Sie den Bezeichner für das Feld Titel ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['tl_avisota_recipient:firstname__0']    = array('Feld - Vorname', 'Hier können Sie den Bezeichner für das Feld Vorname ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['tl_avisota_recipient:lastname__0']     = array('Feld - Nachname', 'Hier können Sie den Bezeichner für das Feld Nachname ändern.');
$GLOBALS['TL_LANG']['tl_avisota_translation']['tl_avisota_recipient:gender__0']       = array('Feld - Geschlecht', 'Hier können Sie den Bezeichner für das Feld Geschlecht ändern.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_translation']['translation_legend']  = 'Allgemein';
$GLOBALS['TL_LANG']['tl_avisota_translation']['subscription_legend'] = 'Abonnement';
$GLOBALS['TL_LANG']['tl_avisota_translation']['subscribe_legend']    = 'Anmeldung';
$GLOBALS['TL_LANG']['tl_avisota_translation']['unsubscribe_legend']  = 'Kündigen';
$GLOBALS['TL_LANG']['tl_avisota_translation']['notification_legend'] = 'Benachrichtigung';
$GLOBALS['TL_LANG']['tl_avisota_translation']['recipient_legend']    = 'Abonnentenfelder';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_translation']['edit']                = 'Sprachvariablen bearbeiten';

?>