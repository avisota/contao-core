<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['subject']              = array(
	'Subject',
	'Please enter the subject of the Newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['alias']                = array(
	'Newsletter Alias',
	'The Newsletter Alias is a unique reference that can be called instead of the numerical Newsletter I.D.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['addFile']              = array(
	'Attach Files',
	'Attach one or more files to the Newsletter'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['files']                = array(
	'Attachments',
	'Please select the file(s) to be attached from the File Browser.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['template_html']        = array(
	'HTML Email Template',
	'Here you can select the HTML E-Mail template.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['template_plain']       = array(
	'Plain Text E-Mail-Template',
	'Here you can select the Plain Text E-Mail template.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['recipients']           = array('Recipients', 'Select the recipients.');
$GLOBALS['TL_LANG']['orm_avisota_message']['tstamp']               = array(
	'Change Date',
	'Date and time of last modification.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['sendPreviewTo']        = array(
	'Send Test',
	'Test the transmission of the Newsletter to this E-Mail address.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['preview_mode']         = array(
	'Preview Mode',
	'Type of Preview Mode.',
	'HTML Preview',
	'Plain Text Preview.'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['preview_personalized'] = array(
	'Personalize',
	'The type of personalization',
	'None',
	'Anonymous',
	'Personal'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['newsletter_legend'] = 'Newsletter';
$GLOBALS['TL_LANG']['orm_avisota_message']['recipient_legend']  = 'Recipient';
$GLOBALS['TL_LANG']['orm_avisota_message']['attachment_legend'] = 'Attachments';
$GLOBALS['TL_LANG']['orm_avisota_message']['template_legend']   = 'Template Settings';
$GLOBALS['TL_LANG']['orm_avisota_message']['headline']          = 'See and send newsletters';
$GLOBALS['TL_LANG']['orm_avisota_message']['from']              = 'Sender';
$GLOBALS['TL_LANG']['orm_avisota_message']['live']              = 'Update Preview';
$GLOBALS['TL_LANG']['orm_avisota_message']['preview']           = 'Preview';
$GLOBALS['TL_LANG']['orm_avisota_message']['sendConfirm']       = 'Newsletter Sent Confirmation';
$GLOBALS['TL_LANG']['orm_avisota_message']['unsubscribe']       = 'Unsubscribe from Newsletter';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['view']    = array('View and Send', 'View the Newsletter and send it.');
$GLOBALS['TL_LANG']['orm_avisota_message']['send']    = array('Send', 'Send the Newsletter.');
$GLOBALS['TL_LANG']['orm_avisota_message']['sended']  = 'Sent %s';
$GLOBALS['TL_LANG']['orm_avisota_message']['confirm'] = 'The Newsletter was sent to all recipients.';
$GLOBALS['TL_LANG']['orm_avisota_message']['online']  = 'Problems with viewing? View the Newsletter online.';
$GLOBALS['TL_LANG']['orm_avisota_message']['list']    = 'Distribution List';
$GLOBALS['TL_LANG']['orm_avisota_message']['member']  = 'Members';
$GLOBALS['TL_LANG']['orm_avisota_message']['mgroup']  = 'Member Group';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['new']        = array('New Newsletter', 'Create a new Newsletter.');
$GLOBALS['TL_LANG']['orm_avisota_message']['show']       = array(
	'Newsletter Details',
	'Deatails of the newsletter I.D. %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['copy']       = array(
	'Newsletter Duplicate',
	'Duplicate Newsletter ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message']['delete']     = array('Delete Newsletter', 'Delete Newsletter ID %s');
$GLOBALS['TL_LANG']['orm_avisota_message']['edit']       = array('Edit Newsletter', 'Edit Newsletter ID %s');
$GLOBALS['TL_LANG']['orm_avisota_message']['editheader'] = array(
	'Edit Newsletter Settings',
	'Edit the Newsletter Settings ID %s'
);


/**
 * Personalisation
 */
$GLOBALS['TL_LANG']['orm_avisota_message']['anonymous']['name'] = 'Subscriber/-in';
