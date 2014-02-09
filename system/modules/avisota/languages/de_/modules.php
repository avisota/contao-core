<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['avisota']              = 'Newsletter';
$GLOBALS['TL_LANG']['MOD']['avisota_mailing_list'] = array('Verteiler', 'Newsletter Verteiler verwalten.');
$GLOBALS['TL_LANG']['MOD']['avisota_recipients']   = array('Abonnenten', 'Newsletter Abonnenten verwalten.');
$GLOBALS['TL_LANG']['MOD']['avisota_newsletter']   = array('Newsletter', 'Newsletter verwalten und versenden.');
$GLOBALS['TL_LANG']['MOD']['avisota_outbox']       = array(
	'Postausgang',
	'Postausgang einsehen und Newsletter versenden.'
);

$GLOBALS['TL_LANG']['MOD']['avisota_settings_group']   = 'Newslettersystem';
$GLOBALS['TL_LANG']['MOD']['avisota_newsletter_draft'] = array('Vorlagen', 'Vorlagen verwalten.');
$GLOBALS['TL_LANG']['MOD']['avisota_update']           = array('Update', 'Avisota Newslettersystem aktualisieren.');
$GLOBALS['TL_LANG']['MOD']['avisota_settings']         = array('Einstellungen', 'Einstellungen verwalten.');
$GLOBALS['TL_LANG']['MOD']['avisota_theme']            = array('Layouts', 'Newsletter Layouts verwalten.');
$GLOBALS['TL_LANG']['MOD']['avisota_recipient_source'] = array(
	'Abonnentenquellen',
	'Quellen für Newsletter Abonnenten verwalten.'
);
$GLOBALS['TL_LANG']['MOD']['avisota_transport']        = array('Transportmodule', 'Transportmodule verwalten.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['avisota']              = 'Newslettersystem';
$GLOBALS['TL_LANG']['FMD']['avisota_subscribe']    = array(
	'Newsletter Abonieren (Avisota)',
	'Anmeldung zum Avisota Newslettersystem.'
);
$GLOBALS['TL_LANG']['FMD']['avisota_unsubscribe']  = array(
	'Newsletter Kündigen (Avisota)',
	'Abmeldung vom Avisota Newslettersystem.'
);
$GLOBALS['TL_LANG']['FMD']['avisota_subscription'] = array(
	'Newsletter Abonnement verwalten (Avisota)',
	'An- und Abmeldung zum Avisota Newslettersystem.'
);
$GLOBALS['TL_LANG']['FMD']['avisota_list']         = array(
	'Newsletter-Liste (Avisota)',
	'List aller versendeten Newsletter.'
);
$GLOBALS['TL_LANG']['FMD']['avisota_reader']       = array(
	'Newsletter-Leser (Avisota)',
	'Einen Newsletter innerhalb einer Seite anzeigen.'
);
