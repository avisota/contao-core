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
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_transport']['type']           = array(
	'Transport type',
	'Please chose the transport type.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['title']          = array(
	'Title',
	'Please enter the transport module title.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['alias']          = array(
	'Alias',
	'The transport module alias is a unique reference to the transport module which can be used instead of its ID.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['fromAddress']    = array(
	'From address',
	'Please enter the from address.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['fromName']       = array(
	'From name',
	'Please enter the from name.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['setSender']      = array(
	'Set sender address',
	'Set the sender for this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['senderAddress']  = array(
	'Sender address',
	'Please enter the sender address.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['senderName']     = array(
	'Sender name',
	'Please enter the sender name.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['setReplyTo']     = array(
	'Set reply address',
	'Set the reply to for this newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['replyToAddress'] = array(
	'Reply address',
	'Please enter the reply address.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['replyToName']    = array(
	'Reply name',
	'Please enter the reply name.'
);
// swift transport
$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftUseSmtp']  = array(
	'Send e-mails via SMTP',
	'Use an SMTP server instead of the PHP mail() function to send e-mails.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpHost'] = array(
	'SMTP hostname',
	'Please enter the host name of the SMTP server.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpUser'] = array(
	'SMTP username',
	'Here you can enter the SMTP username.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpPass'] = array(
	'SMTP password',
	'Here you can enter the SMTP password.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpEnc']  = array(
	'SMTP encryption',
	'Here you can choose an encryption method (SSL or TLS).'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpPort'] = array(
	'SMTP port number',
	'Please enter the port number of the SMTP server.'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['serviceName']   = array(
	'Service name',
	'Please enter the service name.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_transport']['transport_legend'] = 'Transport module';
$GLOBALS['TL_LANG']['orm_avisota_transport']['contact_legend']   = 'From, Sender and Reply to settings';
$GLOBALS['TL_LANG']['orm_avisota_transport']['swift_legend']     = 'Swift PHP Mailer';
$GLOBALS['TL_LANG']['orm_avisota_transport']['service_legend']   = 'Service';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_transport']['swift']                   = 'Swift PHP Mailer';
$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpSystemSettings'] = 'Use system settings';
$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpOn']             = 'Send e-mails via SMTP';
$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpOff']            = 'Send e-mails via PHP';
$GLOBALS['TL_LANG']['orm_avisota_transport']['service']                 = 'Custom service';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_transport']['new']    = array(
	'New transport module',
	'Create a new transport module'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['show']   = array(
	'Transport module details',
	'Show the details of transport module ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['edit']   = array(
	'Edit transport module',
	'Edit transport module ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_transport']['delete'] = array(
	'Delete transport module',
	'Delete transport module ID %s'
);
