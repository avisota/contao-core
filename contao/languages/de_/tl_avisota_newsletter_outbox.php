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
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['newsletter']  = 'Newsletter';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['date']        = 'Aufgegeben';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['count']       = 'Empfänger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['state']       = 'Status';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['outstanding'] = 'ausstehend';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['total']       = 'total';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['failed']      = 'fehlgeschlagen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['sended']      = 'versendet';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['sended_on']   = 'Versendet';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['recipient']   = 'Empfänger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['source']      = 'Quelle';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['headline']       = 'Newsletter versenden';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['open']           = 'Versand offen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['incomplete']     = 'Versand unvollständig';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['complete']       = 'Versand vollständig';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['recipient_list'] = 'Empfängerliste';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['mgroup']         = 'Mitgliedergruppe';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['empty']         = 'Es sind keine Aufträge im Postausgang.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['rejected']      = 'Die E-Mail-Adresse %s wurde deaktiviert.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['error']         = 'In diesem Verteiler sind keine aktiven Abonnenten vorhanden.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['removed']       = 'Auftrag wurde gelöscht.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['dateimsFormat'] = 'd.m.Y h:i:s';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['send']    = array(
	'Versenden',
	'Newsletter an ausstehende Empfänger versenden.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['remove']  = array(
	'Auftrag löschen',
	'Den Auftrag löschen.',
	'Soll der Auftrag wirklich gelöscht werden?'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['details'] = array('Details', 'Details über den Auftrag anzeigen.');


/**
 * Personalisation
 */
