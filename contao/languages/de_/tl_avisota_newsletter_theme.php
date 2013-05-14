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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['title']             = array(
	'Titel',
	'Hier können Sie den Titel des Layouts angeben.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['preview']           = array(
	'Vorschaubild',
	'Wählen Sie hier ein Vorschaubild zu diesem Layout aus. Das Vorschaubild sollte eine Größe von 120x120 haben!'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['areas']             = array(
	'Bereiche',
	'Komma-getrennte Liste von zusätzlichen Newsletterbereichen (z.B. header,left,right,footer).'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['stylesheets']       = array(
	'Stylesheets',
	'Stylesheets, die in den Newsletter eingebunden werden sollen.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['template_html']     = array(
	'HTML E-Mail-Template',
	'Hier können Sie das HTML E-Mail-Template auswählen.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['template_plain']    = array(
	'Plain Text E-Mail-Template',
	'Hier können Sie das Plain Text E-Mail-Template auswählen.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['templateDirectory'] = array(
	'Templates-Ordner',
	'Hier können Sie einen Templates-Ordner festlegen.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['theme_legend']     = 'Layout';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['structure_legend'] = 'Struktur und Inhalt';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['template_legend']  = 'Template-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['expert_legend']    = 'Experten-Einstellungen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['new']    = array('Neues Layout', 'Ein neues Layout erstellen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['show']   = array(
	'Layoutdetails',
	'Details des Layout ID %s anzeigen'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['copy']   = array('Layout duplizieren', 'Layout ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['delete'] = array('Layout löschen', 'Layout ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['edit']   = array(
	'Layouteinstellungen bearbeiten',
	'Einstellungen des Layout ID %s bearbeiten'
);
