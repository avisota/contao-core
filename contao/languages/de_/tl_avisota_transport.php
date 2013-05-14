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
$GLOBALS['TL_LANG']['tl_avisota_transport']['type']        = array(
	'Transportmodul',
	'Wählen Sie hier das Transportmodul aus.'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['title']       = array(
	'Titel',
	'Hier können Sie einen Titel für das Transportmodul angeben.'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['sender']      = array(
	'Absenderadresse',
	'Hier können Sie eine individuelle Absenderadresse eingeben.'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['senderName']  = array(
	'Absendername',
	'Hier können Sie den Namen des Absenders eingeben.'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['replyTo']     = array(
	'Antwortadresse',
	'Hier können Sie eine individuelle Antwort-Adresse eingeben.'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['replyToName'] = array(
	'Antwortname',
	'Hier können Sie den Namen des Antwort-Empfängers eingeben.'
);
// swift transport
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftUseSmtp']  = array(
	'Eigener SMTP-Server',
	'Einen eigenen SMTP-Server für den Newsletter-Versand verwenden.'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpHost'] = array(
	'SMTP-Hostname',
	'Bitte geben Sie den Hostnamen des SMTP-Servers ein.'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpUser'] = array(
	'SMTP-Benutzername',
	'Hier können Sie den SMTP-Benutzernamen eingeben.'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpPass'] = array(
	'SMTP-Passwort',
	'Hier können Sie das SMTP-Passwort eingeben.'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpEnc']  = array(
	'SMTP-Verschlüsselung',
	'Hier können Sie eine Verschlüsselungsmethode auswählen (SSL oder TLS).'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpPort'] = array(
	'SMTP-Portnummer',
	'Bitte geben Sie die Portnummer des SMTP-Servers ein.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_transport']['transport_legend'] = 'Transportmodul';
$GLOBALS['TL_LANG']['tl_avisota_transport']['sender_legend']    = 'Absender';
$GLOBALS['TL_LANG']['tl_avisota_transport']['swift_legend']     = 'Swift PHP Mailer';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_transport']['swift']                   = 'Swift PHP Mailer';
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpSystemSettings'] = 'Systemeinstellung verwenden';
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpOn']             = 'SMTP verwenden';
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpOff']            = 'SMTP nicht verwenden';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_transport']['new']    = array(
	'Neues Transportmodul',
	'Eine neues Transportmodul erstellen'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['show']   = array(
	'Transportmoduldetails',
	'Details des Transportmoduls ID %s anzeigen'
);
$GLOBALS['TL_LANG']['tl_avisota_transport']['delete'] = array('Transportmodul löschen', 'Transportmodul ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_transport']['edit']   = array(
	'Transportmodul bearbeiten',
	'Transportmodul ID %s bearbeiten'
);
