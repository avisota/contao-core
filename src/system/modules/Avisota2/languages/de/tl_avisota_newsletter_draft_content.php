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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


$this->loadLanguageFile('tl_avisota_newsletter_content');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content'] = array_slice(
	$GLOBALS['TL_LANG']['tl_avisota_newsletter_content'],
	0
);

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['unmodifiable']    = array('Nicht veränderbar', 'Das Element als nicht veränderbar markieren. Das Element kann später im Newsletter nicht mehr verändert werden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['undeletable']     = array('Nicht löschbar', 'Das Element als nicht löschbar markieren. Das Element kann später nicht aus dem Newsletter gelöscht werden.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['draft_legend']    = 'Vorlage-Einstellungen';


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
$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['preview']     = array('Vorlage ansehen', 'Die Vorlage ansehen');
