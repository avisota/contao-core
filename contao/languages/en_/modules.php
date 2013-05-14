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
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['avisota']             = 'Newsletter';
$GLOBALS['TL_LANG']['MOD']['avisota_recipients']  = array('Subscribers', 'Manage Newsletter Subscribers');
$GLOBALS['TL_LANG']['MOD']['avisota_newsletter']  = array('Newsletter', 'Manage and send newsletters to outbox.');
$GLOBALS['TL_LANG']['MOD']['avisota_outbox']      = array('Outbox', 'View outbox and send newsletters to recipients');
$GLOBALS['TL_LANG']['MOD']['avisota_translation'] = array(
	'Language Variables',
	'Change the language variables for the newsletter.'
);
$GLOBALS['TL_LANG']['MOD']['avisota_update']      = array('Update', 'Update the Avisota newsletter system.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['avisota']              = 'Newsletter';
$GLOBALS['TL_LANG']['FMD']['avisota_list']         = array(
	'Newsletter list',
	'Navigation list of all sended newsletters.'
);
$GLOBALS['TL_LANG']['FMD']['avisota_reader']       = array('Newsletter reader', 'Show a newsletter in a regular page.');
$GLOBALS['TL_LANG']['FMD']['avisota_subscription'] = array(
	'Manage Subscriptions',
	'Login and Logout of the Newsletter System.'
);
