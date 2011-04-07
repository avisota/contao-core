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
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['invisible']       = array('Unsichtbar', 'Das Element auf der Webseite nicht anzeigen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['type']            = array('Elementtyp', 'Bitte wählen Sie den Typ des Inhaltselements.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['area']            = array('Bereich', 'Bitte wählen Sie den Bereich in dem das Inhaltselement angezeigt werden soll.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['headline']        = array('Überschrift', 'Hier können Sie dem Inhaltselement eine Überschrift hinzufügen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['text']            = array('Text', 'Sie können HTML-Tags verwenden, um den Text zu formatieren.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['definePlain']     = array('Plain-Text vorgeben', 'Den Plain-Text angeben, anstatt ihn aus dem HTML Text automatisch erstellen zu lassen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['plain']           = array('Plain-Text', 'Hier können Sie den Plain-Text angeben.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['personalize']     = array('Personalisieren', 'Hier können Sie auswählen, ob dieses Element personalisiert werden soll.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['addImage']        = array('Ein Bild hinzufügen', 'Dem Inhaltselement ein Bild hinzufügen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['singleSRC']       = array('Quelldatei', 'Bitte wählen Sie eine Datei oder einen Ordner aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['alt']             = array('Alternativer Text', 'Eine barrierefreie Webseite sollte immer einen alternativen Text für Bilder und Filme mit einer kurzen Beschreibung deren Inhalts enthalten.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['size']            = array('Bildbreite und Bildhöhe', 'Hier können Sie die Abmessungen des Bildes und den Skalierungsmodus festlegen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['imagemargin']     = array('Bildabstand', 'Hier können Sie den oberen, rechten, unteren und linken Bildabstand eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['imageUrl']        = array('Bildlink-Adresse', 'Eine eigene Bildlink-Adresse überschreibt den Lightbox-Link, so dass das Bild nicht mehr in der Großansicht dargestellt werden kann.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['floating']        = array('Bildausrichtung', 'Bitte legen Sie fest, wie das Bild ausgerichtet werden soll.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['caption']         = array('Bildunterschrift', 'Hier können Sie einen kurzen Text eingeben, der unterhalb des Bildes angezeigt wird.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['listtype']        = array('Listentyp', 'Bitte wählen Sie den Typ der Liste.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['listitems']       = array('Listeneinträge', 'Wenn JavaScript deaktiviert ist, speichern Sie unbedingt Ihre Änderungen bevor Sie die Reihenfolge ändern.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['tableitems']      = array('Tabelleneinträge', 'Wenn JavaScript deaktiviert ist, speichern Sie unbedingt Ihre Änderungen bevor Sie die Reihenfolge ändern.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['summary']         = array('Zusammenfassung', 'Bitte geben Sie eine kurze Zusammenfassung des Inhalts und der Struktur der Tabelle ein.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['thead']           = array('Kopfzeile hinzufügen', 'Die erste Reihe der Tabelle als Kopfzeile verwenden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['tfoot']           = array('Fußzeile hinzufügen', 'Die letzte Reihe der Tabelle als Fußzeile verwenden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['linkTitle']       = array('Link-Text', 'Der Link-Text wird anstelle der Link-Adresse angezeigt.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['embed']           = array('Den Link einbetten', 'Verwenden Sie den Platzhalter "%s", um den Link in einen Text einzubetten (z.B. <em>Für mehr Informationen besuchen Sie %s</em>).');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['multiSRC']        = array('Quelldateien', 'Bitte wählen Sie eine oder mehr Dateien oder Ordner aus der Dateiübersicht. Wenn Sie einen Ordner auswählen, werden die darin enthaltenen Dateien automatisch eingefügt.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['perRow']          = array('Vorschaubilder pro Reihe', 'Die Anzahl an Bildern pro Reihe.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['sortBy']          = array('Sortieren nach', 'Bitte wählen Sie eine Sortierreihenfolge aus.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['galleryHtmlTpl']  = array('HTML Galerietemplate', 'Hier können Sie das HTML Galerietemplate auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['galleryPlainTpl'] = array('Plain Galerietemplate', 'Hier können Sie das Plain Galerietemplate auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['protected']       = array('Element schützen', 'Das Inhaltselement nur bestimmten Gruppen anzeigen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['groups']          = array('Erlaubte Mitgliedergruppen', 'Diese Gruppen können das Inhaltselement sehen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['guests']          = array('Nur Gästen anzeigen', 'Das Inhaltselement verstecken sobald ein Mitglied angemeldet ist.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['cssID']           = array('CSS-Id/Klasse', 'Hier können Sie eine Id und beliebig viele Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['space']           = array('Abstand davor und dahinter', 'Hier können Sie den Abstand vor und nach dem Inhaltselement in Pixeln eingeben. Sie sollten Inline-Styles jedoch nach Möglichkeit vermeiden und den Abstand in einem Stylesheet definieren.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['source']          = array('Quelldateien', 'Bitte wählen Sie die zu importierenden CSV-Dateien aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['events']   		= array('Events', 'Hier können die Events gewählt werden, welche in den Newsletter eingebunden werden sollen.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['type_legend']      = 'Elementtyp';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['text_legend']      = 'Text';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['image_legend']     = 'Bild-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['list_legend']      = 'Listeneinträge';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['table_legend']     = 'Tabelleneinträge';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['tconfig_legend']   = 'Tabellenkonfiguration';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['sortable_legend']  = 'Sortieroptionen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['link_legend']      = 'Hyperlink-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['imglink_legend']   = 'Bildlink-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['template_legend']  = 'Template-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['include_legend']   = 'Include-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['protected_legend'] = 'Zugriffsschutz';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['expert_legend']    = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['events_legend']    = 'Events';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['anonymous']      = 'Anonym personalisieren, falls keine persönlichen Daten verfügbar sind';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['private']        = 'Persönlich personalisieren, blendet das Element aus, wenn keine persönlichen Daten verfügbar sind';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['area']['body']   = 'Inhalt';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['area']['header'] = 'Kopfzeile';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['area']['footer'] = 'Fußzeile';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['area']['left']   = 'Linke Spalte';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['area']['right']  = 'Rechte Spalte';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['new']         = array('Neues Element', 'Ein neues Element erstellen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['show']        = array('Elementdetails', 'Details des Inhaltselements ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['cut']         = array('Element verschieben', 'Inhaltselement ID %s verschieben');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['copy']        = array('Element duplizieren', 'Inhaltselement ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['delete']      = array('Element löschen', 'Inhaltselement ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['edit']        = array('Element bearbeiten', 'Inhaltselement ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['pasteafter']  = array('Oben einfügen', 'Nach dem Inhaltselement ID %s einfügen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['pastenew']    = array('Neues Element oben erstellen', 'Neues Element nach dem Inhaltselement ID %s erstellen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['toggle']      = array('Sichtbarkeit ändern', 'Die Sichtbarkeit des Inhaltselements ID %s ändern');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['editalias']   = array('Quellelement bearbeiten', 'Das Quellelement ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['editarticle'] = array('Artikel bearbeiten', 'Artikel ID %s bearbeiten');

?>