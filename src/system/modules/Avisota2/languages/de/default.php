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
 * Page types
 */
$GLOBALS['TL_LANG']['PTY']['avisota']   = array('Online Newsletter', 'Zeigt einen beliebigen Online-Newsletter aus dem Newslettersystem an.');


/**
 * Newsletter content elements
 */
$GLOBALS['TL_LANG']['NLE']['texts']     = 'Text-Elemente';
$GLOBALS['TL_LANG']['NLE']['headline']  = array('Überschrift', 'Erzeugt eine Überschrift (h1 - h6).');
$GLOBALS['TL_LANG']['NLE']['text']      = array('Text', 'Erzeugt ein Rich-Text-Element.');
$GLOBALS['TL_LANG']['NLE']['list']      = array('Aufzählung', 'Erzeugt eine geordnete oder ungeordnete Liste.');
$GLOBALS['TL_LANG']['NLE']['table']     = array('Tabelle', 'Erzeugt eine optional sortierbare Tabelle.');
$GLOBALS['TL_LANG']['NLE']['links']     = 'Link-Elemente';
$GLOBALS['TL_LANG']['NLE']['hyperlink'] = array('Hyperlink', 'Erzeugt einen Verweis auf eine andere Webseite.');
$GLOBALS['TL_LANG']['NLE']['images']    = 'Bild-Elemente';
$GLOBALS['TL_LANG']['NLE']['image']     = array('Bild', 'Erzeugt ein einzelnes Bild.');
$GLOBALS['TL_LANG']['NLE']['gallery']   = array('Galerie', 'Erzeugt eine Bildergalerie.');
$GLOBALS['TL_LANG']['NLE']['files']     = 'Datei Elemente';
$GLOBALS['TL_LANG']['NLE']['download']  = array('Download', 'Erzeugt einen Link zum Download einer Datei.');
$GLOBALS['TL_LANG']['NLE']['downloads'] = array('Downloads', 'Erzeugt mehrere Links zum Download von Dateien.');
$GLOBALS['TL_LANG']['NLE']['includes']  = 'Include-Elemente';
$GLOBALS['TL_LANG']['NLE']['news']      = array('Nachricht', 'Fügt einen Nachrichten-Teaser ein.');
$GLOBALS['TL_LANG']['NLE']['events']    = array('Events', 'Fügt Event-Teaser ein.');
$GLOBALS['TL_LANG']['NLE']['article']   = array('Artikel', 'Fügt einen Artikel-Teaser ein.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['delete_no_blacklist'] = 'Löschen ohne Blacklist-Eintrag';
$GLOBALS['TL_LANG']['MSC']['schedule']            = 'Versand planen';
$GLOBALS['TL_LANG']['MSC']['send']                = 'Versenden';
