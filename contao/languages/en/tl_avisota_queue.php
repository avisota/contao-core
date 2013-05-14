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
$GLOBALS['TL_LANG']['tl_avisota_queue']['title']       = array(
	'Title',
	'Please enter the transport module title.'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['alias']       = array(
	'Alias',
	'The transport module alias is a unique reference to the transport module which can be used instead of its numeric ID.'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['sender']      = array(
	'Sender address',
	'Please enter the sender address.'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['senderName']  = array(
	'Sender name',
	'Please enter the sender name.'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['replyTo']     = array(
	'Reply address',
	'Please enter the reply address.'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['replyToName'] = array(
	'Reply name',
	'Please enter the reply name.'
);
// swift transport
$GLOBALS['TL_LANG']['tl_avisota_queue']['swiftUseSmtp']  = array(
	'Send e-mails via SMTP',
	'Use an SMTP server instead of the PHP mail() function to send e-mails.'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['swiftSmtpHost'] = array(
	'SMTP hostname',
	'Please enter the host name of the SMTP server.'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['swiftSmtpUser'] = array(
	'SMTP username',
	'Here you can enter the SMTP username.'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['swiftSmtpPass'] = array(
	'SMTP password',
	'Here you can enter the SMTP password.'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['swiftSmtpEnc']  = array(
	'SMTP encryption',
	'Here you can choose an encryption method (SSL or TLS).'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['swiftSmtpPort'] = array(
	'SMTP port number',
	'Please enter the port number of the SMTP server.'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['serviceName'] = array(
	'Service name',
	'Please enter the service name.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_queue']['queue_legend'] = 'Queue';
$GLOBALS['TL_LANG']['tl_avisota_queue']['sender_legend']    = 'Sender';
$GLOBALS['TL_LANG']['tl_avisota_queue']['reply_legend']     = 'Reply';
$GLOBALS['TL_LANG']['tl_avisota_queue']['swift_legend']     = 'Swift PHP Mailer';
$GLOBALS['TL_LANG']['tl_avisota_queue']['service_legend']     = 'Service';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_queue']['swift']                   = 'Swift PHP Mailer';
$GLOBALS['TL_LANG']['tl_avisota_queue']['swiftSmtpSystemSettings'] = 'Use system settings';
$GLOBALS['TL_LANG']['tl_avisota_queue']['swiftSmtpOn']             = 'Send e-mails via SMTP';
$GLOBALS['TL_LANG']['tl_avisota_queue']['swiftSmtpOff']            = 'Send e-mails via PHP';
$GLOBALS['TL_LANG']['tl_avisota_queue']['service']                   = 'Custom service';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_queue']['new']    = array(
	'New queue',
	'Create a new queue'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['show']   = array(
	'Queue details',
	'Show the details of queue ID %s'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['edit']   = array(
	'Edit queue',
	'Edit queue ID %s'
);
$GLOBALS['TL_LANG']['tl_avisota_queue']['delete'] = array(
	'Delete queue',
	'Delete queue ID %s'
);
