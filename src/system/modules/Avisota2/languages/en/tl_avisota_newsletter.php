<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['subject']              = array(
	'Subject',
	'Please enter the subject of the Newsletter.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['alias']                = array(
	'Newsletter Alias',
	'The Newsletter Alias is a unique reference that can be called instead of the numerical Newsletter I.D.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['addFile']              = array(
	'Attach Files',
	'Attach one or more files to the Newsletter'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['files']                = array(
	'Attachments',
	'Please select the file(s) to be attached from the File Browser.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_html']        = array(
	'HTML Email Template',
	'Here you can select the HTML E-Mail template.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_plain']       = array(
	'Plain Text E-Mail-Template',
	'Here you can select the Plain Text E-Mail template.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipients']           = array('Recipients', 'Select the recipients.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['tstamp']               = array(
	'Change Date',
	'Date and time of last modification.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sendPreviewTo']        = array(
	'Send Test',
	'Test the transmission of the Newsletter to this E-Mail address.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_mode']         = array(
	'Preview Mode',
	'Type of Preview Mode.',
	'HTML Preview',
	'Plain Text Preview.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_personalized'] = array(
	'Personalize',
	'The type of personalization',
	'None',
	'Anonymous',
	'Personal'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['newsletter_legend'] = 'Newsletter';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipient_legend']  = 'Recipient';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['attachment_legend'] = 'Attachments';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_legend']   = 'Template Settings';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['headline']          = 'See and send newsletters';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['from']              = 'Sender';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['live']              = 'Update Preview';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview']           = 'Preview';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sendConfirm']       = 'Newsletter Sent Confirmation';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['unsubscribe']       = 'Unsubscribe from Newsletter';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation']        = 'Dear/-r';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_male']   = 'Dear Sir';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_female'] = 'Dear Miss';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['view']    = array('View and Send', 'View the Newsletter and send it.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['send']    = array('Send', 'Send the Newsletter.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sended']  = 'Sent %s';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirm'] = 'The Newsletter was sent to all recipients.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['online']  = 'Problems with viewing? View the Newsletter online.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['list']    = 'Distribution List';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['member']  = 'Members';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['mgroup']  = 'Member Group';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['new']        = array('New Newsletter', 'Create a new Newsletter.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['show']       = array(
	'Newsletter Details',
	'Deatails of the newsletter I.D. %s'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['copy']       = array(
	'Newsletter Duplicate',
	'Duplicate Newsletter ID %s'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['delete']     = array('Delete Newsletter', 'Delete Newsletter ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['edit']       = array('Edit Newsletter', 'Edit Newsletter ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['editheader'] = array(
	'Edit Newsletter Settings',
	'Edit the Newsletter Settings ID %s'
);


/**
 * Personalisation
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['salutation'] = 'Dear/-r';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['name']       = 'Subscriber/-in';
