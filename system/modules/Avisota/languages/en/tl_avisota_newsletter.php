<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['subject']              = array('Subject', 'Please enter the subject of the Newsletter.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['alias']                = array('Newsletter Alias', 'The Newsletter Alias is a unique reference that can be called instead of the numerical Newsletter I.D.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['addFile']              = array('Attach Files', 'Attach one or more files to the Newsletter');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['files']                = array('Attachments', 'Please select the file(s) to be attached from the File Browser.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_html']        = array('HTML Email Template', 'Here you can select the HTML E-Mail template.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_plain']       = array('Plain Text E-Mail-Template', 'Here you can select the Plain Text E-Mail template.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipients']           = array('Recipients', 'Select the recipients.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['tstamp']               = array('Change Date', 'Date and time of last modification.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sendPreviewTo']        = array('Send Test', 'Test the transmission of the Newsletter to this E-Mail address.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_mode']         = array('Preview Mode', 'Type of Preview Mode.','HTML Preview','Plain Text Preview.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_personalized'] = array('Personalize','The type of personalization','None','Anonymous','Personal');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['newsletter_legend']  = 'Newsletter';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipient_legend']   = 'Recipient';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['attachment_legend']  = 'Attachments';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_legend']    = 'Template Settings';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['headline']           = 'See and send newsletters';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['from']               = 'Sender';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['live']               = 'Update Preview';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview']            = 'Preview';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sendConfirm']        = 'Newsletter Sent Confirmation';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['unsubscribe']        = 'Unsubscribe from Newsletter';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation']         = 'Dear/-r';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_male']    = 'Dear Sir';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_female'] = 'Dear Miss';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['view']     = array('View and Send','View the Newsletter and send it.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['send']     = array('Send','Send the Newsletter.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sended']   = 'Sent %s';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirm']  = 'The Newsletter was sent to all recipients.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['online']   = 'Problems with viewing? View the Newsletter online.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['list']     = 'Distribution List';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['member']   = 'Members';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['mgroup']   = 'Member Group';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['new']         = array('New Newsletter','Create a new Newsletter.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['show']        = array('Newsletter Details','Deatails of the newsletter I.D. %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['copy']        = array('Newsletter Duplicate','Duplicate Newsletter ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['delete']      = array('Delete Newsletter','Delete Newsletter ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['edit']        = array('Edit Newsletter','Edit Newsletter ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['editheader']  = array('Edit Newsletter Settings','Edit the Newsletter Settings ID %s');


/**
 * Personalisation
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['salutation'] = 'Dear/-r';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['name']       = 'Subscriber/-in';

?>