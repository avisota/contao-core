<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['title']          = array('Titel', 'Hier können Sie den Titel der Kategorie angeben.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['alias']          = array('Kategoriealias', 'Der Kategoriealias ist eine eindeutige Referenz, die anstelle der numerischen Kategoriealias-Id aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['viewOnlinePage'] = array('Online-Ansehen Seite für Mitglieder', 'Bitte wählen Sie die Newsletterleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Newsletter online ansehen wollen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipientsMode'] = array('Empfänger Auswahl', 'Wählen Sie hier wie die Auswahl der Empfänger möglich sein soll.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipients']     = array('Empfänger', 'Wählen Sie hier die Empfänger aus.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['themeMode']      = array('Layout Auswahl', 'Wählen Sie hier wie die Auswahl des Layouts möglich sein soll.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['theme']          = array('Layout', 'Wählen Sie hier das Layouts aus.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['transportMode']  = array('Transportmodul Auswahl', 'Wählen Sie hier wie die Auswahl des Transportmoduls möglich sein soll.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['transport']      = array('Transportmodul', 'Wählen Sie hier das Transportmodul aus.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['tstamp']         = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['category_legend']   = 'Kategorie';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipients_legend'] = 'Empfänger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['theme_legend']      = 'Layout';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['expert_legend']     = 'Experten-Einstellungen';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['byCategory']             = 'Nur über die Kategorie';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['byNewsletterOrCategory'] = 'Im Newsletter optional, mit Rückfalloption auf die Kategorieeinstellung';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['byNewsletter']           = 'Nur über den Newsletter';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['new']        = array('Neue Kategorie', 'Eine neue Liste erstellen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['show']       = array('Kategoriedetails', 'Details der Kategorie ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['copy']       = array('Kategorie duplizieren', 'Kategorie ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['delete']     = array('Kategorie löschen', 'Kategorie ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['edit']       = array('Kategorie bearbeiten', 'Kategorie ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['editheader'] = array('Kategorieeinstellungen bearbeiten', 'Einstellungen der Kategorie ID %s bearbeiten');
