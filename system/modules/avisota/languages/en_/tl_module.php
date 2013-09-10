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
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_lists']                           = array('Listen', '');
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe_mail_plain']   = array(
	'Sign in plain text e-mail template',
	''
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe_mail_html']    = array('Sign in HTML e-mail template', '');
$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe_mail_plain'] = array(
	'Unsubscribe Plain Text e-mail template',
	''
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe_mail_html']  = array(
	'Unsubscribe HTML e-mail template',
	''
);

$GLOBALS['TL_LANG']['tl_module']['avisota_show_lists']                      = array(
	'Show mailing list selection',
	'Show selection of the mailing lists in the frontend.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_lists']                           = array(
	'Mailing lists',
	'Please choose the selectable mailing lists.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_recipient_fields']                = array(
	'Personal fields',
	'Please choose the personal fields.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_sender_name']        = array(
	'Sender name',
	'Please type in the sender name.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_sender']             = array(
	'Sender email',
	'Please type in the sender email.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe_mail_plain']   = array(
	'Subscribe plain text email template',
	'Please choose the subscribe plain text email template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe_mail_html']    = array(
	'Subscribe html email template',
	'Please choose the subscribe html email template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe_mail_plain'] = array(
	'Unsubscribe plain text email template',
	'Please choose the unsubsribe plain text email template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe_mail_html']  = array(
	'Unsubscribe html email template',
	'Please choose the unsubscribe html email template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscription']           = array(
	'Form template',
	'Please choose the form template. The template have to be prefixed with <strong>subscription_</strong>.'
);

$GLOBALS['TL_LANG']['tl_module']['avisota_send_notification']                = array(
	'Send reminder',
	'Send a reminder, if the subscription is not confirmed within certain days.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_notification_time']                = array(
	'Days until remind',
	'Please choose the count of days before remind the subscription.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_notification_mail_plain'] = array(
	'Reminder plain text email template',
	'Please choose the reminder plain text email template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_notification_mail_html']  = array(
	'Reminder html email template',
	'Please choose the reminder html email template.'
);

$GLOBALS['TL_LANG']['tl_module']['avisota_do_cleanup']   = array(
	'Delete unconfirmed subscriptions',
	'Delete unconfirmed subscriptions after certain days.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_cleanup_time'] = array(
	'Days until deletion',
	'Please choose the count of days before delete unconfirmed subscriptions.'
);

$GLOBALS['TL_LANG']['tl_module']['avisota_categories']      = array(
	'Categories',
	'Please choose the categories to show newsletters from.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_reader_template'] = array(
	'Reader template',
	'Please choose the reader template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_list_template']   = array(
	'List template',
	'Please choose the list template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_view_page']       = array(
	'View page',
	'Choose a page, the newsletter is displayed in. If not set, the view online page from the category is used.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_legend'] = 'Subscription';
$GLOBALS['TL_LANG']['tl_module']['avisota_mail_legend']         = 'Mail Settings';
$GLOBALS['TL_LANG']['tl_module']['avisota_notification_legend'] = 'Reminder';
$GLOBALS['TL_LANG']['tl_module']['avisota_cleanup_legend']      = 'Clean up';
$GLOBALS['TL_LANG']['tl_module']['avisota_reader_legend']       = 'Newsletter-Reader';
$GLOBALS['TL_LANG']['tl_module']['avisota_list_legend']         = 'Newsletter-List';
