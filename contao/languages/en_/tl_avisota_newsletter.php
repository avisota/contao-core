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
$GLOBALS['TL_LANG']['orm_avisota_mailing']['subject']              = array(
	'Subject',
	'Please enter the subject of the Newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['alias']                = array(
	'Newsletter Alias',
	'The Newsletter Alias is a unique reference that can be called instead of the numerical Newsletter I.D.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['addFile']              = array(
	'Attach Files',
	'Attach one or more files to the Newsletter'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['files']                = array(
	'Attachments',
	'Please select the file(s) to be attached from the File Browser.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['template_html']        = array(
	'HTML Email Template',
	'Here you can select the HTML E-Mail template.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['template_plain']       = array(
	'Plain Text E-Mail-Template',
	'Here you can select the Plain Text E-Mail template.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['recipients']           = array('Recipients', 'Select the recipients.');
$GLOBALS['TL_LANG']['orm_avisota_mailing']['tstamp']               = array(
	'Change Date',
	'Date and time of last modification.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['sendPreviewTo']        = array(
	'Send Test',
	'Test the transmission of the Newsletter to this E-Mail address.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['preview_mode']         = array(
	'Preview Mode',
	'Type of Preview Mode.',
	'HTML Preview',
	'Plain Text Preview.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['preview_personalized'] = array(
	'Personalize',
	'The type of personalization',
	'None',
	'Anonymous',
	'Personal'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing']['newsletter_legend'] = 'Newsletter';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['recipient_legend']  = 'Recipient';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['attachment_legend'] = 'Attachments';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['template_legend']   = 'Template Settings';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['headline']          = 'See and send newsletters';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['from']              = 'Sender';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['live']              = 'Update Preview';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['preview']           = 'Preview';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['sendConfirm']       = 'Newsletter Sent Confirmation';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['unsubscribe']       = 'Unsubscribe from Newsletter';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['salutation']        = 'Dear/-r';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['salutation_male']   = 'Dear Sir';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['salutation_female'] = 'Dear Miss';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing']['view']    = array('View and Send', 'View the Newsletter and send it.');
$GLOBALS['TL_LANG']['orm_avisota_mailing']['send']    = array('Send', 'Send the Newsletter.');
$GLOBALS['TL_LANG']['orm_avisota_mailing']['sended']  = 'Sent %s';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['confirm'] = 'The Newsletter was sent to all recipients.';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['online']  = 'Problems with viewing? View the Newsletter online.';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['list']    = 'Distribution List';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['member']  = 'Members';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['mgroup']  = 'Member Group';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing']['new']        = array('New Newsletter', 'Create a new Newsletter.');
$GLOBALS['TL_LANG']['orm_avisota_mailing']['show']       = array(
	'Newsletter Details',
	'Deatails of the newsletter I.D. %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['copy']       = array(
	'Newsletter Duplicate',
	'Duplicate Newsletter ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing']['delete']     = array('Delete Newsletter', 'Delete Newsletter ID %s');
$GLOBALS['TL_LANG']['orm_avisota_mailing']['edit']       = array('Edit Newsletter', 'Edit Newsletter ID %s');
$GLOBALS['TL_LANG']['orm_avisota_mailing']['editheader'] = array(
	'Edit Newsletter Settings',
	'Edit the Newsletter Settings ID %s'
);


/**
 * Personalisation
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing']['anonymous']['salutation'] = 'Dear/-r';
$GLOBALS['TL_LANG']['orm_avisota_mailing']['anonymous']['name']       = 'Subscriber/-in';
