<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['avisota']                   = array('Avisota newsletter');
$GLOBALS['TL_LANG']['MOD']['avisota_outbox']            = array('Outbox');
$GLOBALS['TL_LANG']['MOD']['avisota_newsletter']        = array('Messages');
$GLOBALS['TL_LANG']['MOD']['avisota_recipients']        = array('Recipients');
$GLOBALS['TL_LANG']['MOD']['avisota_config']            = array('Avisota settings');
$GLOBALS['TL_LANG']['MOD']['avisota_settings']          = array(
	'Avisota system settings',
	'Manage basic avisota system settings'
);
$GLOBALS['TL_LANG']['MOD']['avisota_config:recipient']  = array('Recipient config');
$GLOBALS['TL_LANG']['MOD']['avisota_mailing_list']      = array(
	'Mailing lists',
	'Manage the mailing lists that can be subscribed.'
);
$GLOBALS['TL_LANG']['MOD']['avisota_recipient_source']  = array('Recipient sources', 'Manager recipient sources.');
$GLOBALS['TL_LANG']['MOD']['avisota_config:newsletter'] = array('Message config');
$GLOBALS['TL_LANG']['MOD']['avisota_newsletter_draft']  = array(
	'Message drafts',
	'Manager newsletter drafts to create new newsletters from.'
);
$GLOBALS['TL_LANG']['MOD']['avisota_theme']             = array(
	'Theme',
	'Manage themes, including templates, stylesheets and layout settings for newsletters.'
);
$GLOBALS['TL_LANG']['MOD']['avisota_config:transport']  = array('Transport config');
$GLOBALS['TL_LANG']['MOD']['avisota_transport']         = array('Transports', 'Manager transport modules.');
$GLOBALS['TL_LANG']['MOD']['avisota_queue']             = array('Queues', 'Manager transport queues.');


/**
 * Front end modules
 */
