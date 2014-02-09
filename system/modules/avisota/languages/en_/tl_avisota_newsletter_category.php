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
$GLOBALS['TL_LANG']['orm_avisota_message_category']['title']            = array(
	'Title ',
	'Here you can enter the title of the category.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['alias']            = array(
	'Category Alias',
	'The Category alias is a unique reference that can be called instead of the numerical category Alias ID.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['viewOnlinePage']   = array(
	'Newsletter Reader Page',
	'Please select the Newsletter Read Page that subscribers will be directed to, if they choose to read the Newsletter online.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['subscriptionPage'] = array(
	'Manage Member Subscriptions',
	'Please select the page that subscribers will be forwareded to in order to manage their subscription.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['senderName']       = array(
	'Sender Name ',
	'Here you can enter the name of the sender.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['sender']           = array(
	'Sender Address',
	'Here you can enter a customized return address.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['useSMTP']          = array(
	'Personal SMTP Server',
	'Use your own SMTP server to send the Newsletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['smtpHost']         = array(
	'SMTP Host Name ',
	'Please enter the host name of the SMTP server.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['smtpUser']         = array(
	'SMTP Username ',
	'Here you can enter the SMTP user name.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['smtpPass']         = array(
	'SMTP Password ',
	'Here you can enter the SMTP password.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['smtpEnc']          = array(
	'SMTP encryption ',
	'Here you can select an encryption method (SSL or TLS).'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['smtpPort']         = array(
	'SMTP port number ',
	' Please enter the port number of the SMTP server.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['tstamp']           = array(
	'Change Date ',
	' Date and time of last modification.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['areas']            = array(
	'Areas',
	'Comma separated list of additional content in the newsletter (e.a. header,left,right,footer).'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['template_html']    = array(
	'HTML Email Template ',
	'Here you can select the HTML e-mail template.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['template_plain']   = array(
	'Plain text e-mail template ',
	'Here you can choose the Plain Text e-mail template.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['stylesheets']      = array(
	'Style Sheets ',
	' Style sheets, which are to be included in the Newsletter.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_message_category']['category_legend'] = 'Category';
$GLOBALS['TL_LANG']['orm_avisota_message_category']['smtp_legend']     = 'SMTP-Settings';
$GLOBALS['TL_LANG']['orm_avisota_message_category']['expert_legend']   = 'Expert Settings';
$GLOBALS['TL_LANG']['orm_avisota_message_category']['template_legend'] = 'Template Settings';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_message_category']['new']        = array('New Category ', ' Create a new category');
$GLOBALS['TL_LANG']['orm_avisota_message_category']['show']       = array(
	'Category Details',
	'Details of category ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message_category']['copy']       = array('Copy Category ', ' Copy category ID %s');
$GLOBALS['TL_LANG']['orm_avisota_message_category']['delete']     = array('Delete Category', 'Delete category ID %s');
$GLOBALS['TL_LANG']['orm_avisota_message_category']['edit']       = array('Edit Category', 'Edit category ID %s');
$GLOBALS['TL_LANG']['orm_avisota_message_category']['editheader'] = array(
	'Edit Category Header',
	'Edit the header of category ID %s'
);
