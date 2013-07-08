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


$this->loadLanguageFile('orm_avisota_message_content');
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content'] = array_slice(
	$GLOBALS['TL_LANG']['orm_avisota_message_content'],
	0
);

/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['unmodifiable'] = array(
	'Nicht veränderbar',
	'Das Element als nicht veränderbar markieren. Das Element kann später im Newsletter nicht mehr verändert werden.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['undeletable']  = array(
	'Nicht löschbar',
	'Das Element als nicht löschbar markieren. Das Element kann später nicht aus dem Newsletter gelöscht werden.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['draft_legend'] = 'Vorlage-Einstellungen';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['anonymous']      = 'Anonym personalisieren, falls keine persönlichen Daten verfügbar sind';
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['private']        = 'Persönlich personalisieren, blendet das Element aus, wenn keine persönlichen Daten verfügbar sind';
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['cell']['body']   = 'Inhalt';
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['cell']['header'] = 'Kopfzeile';
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['cell']['footer'] = 'Fußzeile';
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['cell']['left']   = 'Linke Spalte';
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['cell']['right']  = 'Rechte Spalte';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['new']         = array(
	'Neues Element',
	'Ein neues Element erstellen'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['show']        = array(
	'Elementdetails',
	'Details des Inhaltselements ID %s anzeigen'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['cut']         = array(
	'Element verschieben',
	'Inhaltselement ID %s verschieben'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['copy']        = array(
	'Element duplizieren',
	'Inhaltselement ID %s duplizieren'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['delete']      = array(
	'Element löschen',
	'Inhaltselement ID %s löschen'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['edit']        = array(
	'Element bearbeiten',
	'Inhaltselement ID %s bearbeiten'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['pasteafter']  = array(
	'Oben einfügen',
	'Nach dem Inhaltselement ID %s einfügen'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['pastenew']    = array(
	'Neues Element oben erstellen',
	'Neues Element nach dem Inhaltselement ID %s erstellen'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['toggle']      = array(
	'Sichtbarkeit ändern',
	'Die Sichtbarkeit des Inhaltselements ID %s ändern'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['editalias']   = array(
	'Quellelement bearbeiten',
	'Das Quellelement ID %s bearbeiten'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['editarticle'] = array(
	'Artikel bearbeiten',
	'Artikel ID %s bearbeiten'
);
$GLOBALS['TL_LANG']['orm_avisota_message_draft_content']['preview']     = array(
	'Vorlage ansehen',
	'Die Vorlage ansehen'
);
