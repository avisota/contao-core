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
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['title']              = array('Titel', 'Hier können Sie den Titel der Kategorie angeben.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['alias']              = array('Kategoriealias', 'Der Kategoriealias ist eine eindeutige Referenz, die anstelle der numerischen Kategoriealias-Id aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['viewOnlinePage']     = array('Online-Ansehen Seite für Mitglieder', 'Bitte wählen Sie die Newsletterleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Newsletter online ansehen wollen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['subscriptionPage']   = array('Abonnement-Verwalten Seite für Mitglieder', 'Bitte wählen Sie die Abonnement-Verwalten Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Newsletter kündigen wollen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['senderName']         = array('Absendername', 'Hier können Sie den Namen des Absenders eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['sender']             = array('Absenderadresse', 'Hier können Sie eine individuelle Absenderadresse eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['useSMTP']            = array('Eigener SMTP-Server', 'Einen eigenen SMTP-Server für den Newsletter-Versand verwenden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtpHost']           = array('SMTP-Hostname', 'Bitte geben Sie den Hostnamen des SMTP-Servers ein.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtpUser']           = array('SMTP-Benutzername', 'Hier können Sie den SMTP-Benutzernamen eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtpPass']           = array('SMTP-Passwort', 'Hier können Sie das SMTP-Passwort eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtpEnc']            = array('SMTP-Verschlüsselung', 'Hier können Sie eine Verschlüsselungsmethode auswählen (SSL oder TLS).');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtpPort']           = array('SMTP-Portnummer', 'Bitte geben Sie die Portnummer des SMTP-Servers ein.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['tstamp']             = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['areas']              = array('Bereiche', 'Komma-getrennte Liste von zusätzlichen Newsletterbereichen (z.B. header,left,right,footer).');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_html']      = array('HTML E-Mail-Template', 'Hier können Sie das HTML E-Mail-Template auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_plain']     = array('Plain Text E-Mail-Template', 'Hier können Sie das Plain Text E-Mail-Template auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['stylesheets']        = array('Stylesheets', 'Stylesheets, die in den Newsletter eingebunden werden sollen.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['category_legend'] = 'Kategorie';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtp_legend']     = 'SMTP-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['expert_legend']   = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_legend'] = 'Template-Einstellungen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['new']         = array('Neue Kategorie', 'Eine neue Liste erstellen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['show']        = array('Kategoriedetails', 'Details der Kategorie ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['copy']        = array('Kategorie duplizieren', 'Kategorie ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['delete']      = array('Kategorie löschen', 'Kategorie ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['edit']        = array('Kategorie bearbeiten', 'Kategorie ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['editheader']  = array('Kategorieeinstellungen bearbeiten', 'Einstellungen der Kategorie ID %s bearbeiten');

?>