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


/**
 * Page types
 */
$GLOBALS['TL_LANG']['PTY']['avisota'] = array(
	'Online Newsletter',
	'Zeigt einen beliebigen Online-Newsletter aus dem Newslettersystem an.'
);


/**
 * Newsletter content elements
 */
$GLOBALS['TL_LANG']['MCE']['texts']     = 'Text-Elemente';
$GLOBALS['TL_LANG']['MCE']['headline']  = array('Überschrift', 'Erzeugt eine Überschrift (h1 - h6).');
$GLOBALS['TL_LANG']['MCE']['text']      = array('Text', 'Erzeugt ein Rich-Text-Element.');
$GLOBALS['TL_LANG']['MCE']['list']      = array('Aufzählung', 'Erzeugt eine geordnete oder ungeordnete Liste.');
$GLOBALS['TL_LANG']['MCE']['table']     = array('Tabelle', 'Erzeugt eine optional sortierbare Tabelle.');
$GLOBALS['TL_LANG']['MCE']['links']     = 'Link-Elemente';
$GLOBALS['TL_LANG']['MCE']['hyperlink'] = array('Hyperlink', 'Erzeugt einen Verweis auf eine andere Webseite.');
$GLOBALS['TL_LANG']['MCE']['images']    = 'Bild-Elemente';
$GLOBALS['TL_LANG']['MCE']['image']     = array('Bild', 'Erzeugt ein einzelnes Bild.');
$GLOBALS['TL_LANG']['MCE']['gallery']   = array('Galerie', 'Erzeugt eine Bildergalerie.');
$GLOBALS['TL_LANG']['MCE']['files']     = 'Datei Elemente';
$GLOBALS['TL_LANG']['MCE']['download']  = array('Download', 'Erzeugt einen Link zum Download einer Datei.');
$GLOBALS['TL_LANG']['MCE']['downloads'] = array('Downloads', 'Erzeugt mehrere Links zum Download von Dateien.');
$GLOBALS['TL_LANG']['MCE']['includes']  = 'Include-Elemente';
$GLOBALS['TL_LANG']['MCE']['news']      = array('Nachricht', 'Fügt einen Nachrichten-Teaser ein.');
$GLOBALS['TL_LANG']['MCE']['events']    = array('Events', 'Fügt Event-Teaser ein.');
$GLOBALS['TL_LANG']['MCE']['article']   = array('Artikel', 'Fügt einen Artikel-Teaser ein.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['delete_no_blacklist'] = 'Löschen ohne Blacklist-Eintrag';
$GLOBALS['TL_LANG']['MSC']['schedule']            = 'Versand planen';
$GLOBALS['TL_LANG']['MSC']['send']                = 'Versenden';
