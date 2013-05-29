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
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['title']             = array(
	'Title',
	'Please enter the newsletter category title.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['alias']             = array(
	'Alias',
	'The category alias is a unique reference to the category which can be called instead of its numeric ID.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipientsMode']    = array(
	'Recipients selection mode',
	'Please chose the recipients selection mode.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipients']        = array(
	'Recipients',
	'Please chose the preselected recipients.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['themeMode']         = array(
	'Layout selection mode',
	'Please chose the layout selection mode.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['theme']             = array(
	'Layout',
	'Please chose the preselected layout.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['transportMode']     = array(
	'Transport module selection mode',
	'Please chose the transport module selection mode.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['transport']         = array(
	'Transport module',
	'Please chose the preselected transport module.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['queueMode']         = array(
	'Queue selection mode',
	'Please chose the queue selection mode.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['queue']             = array(
	'Queue',
	'Please chose the preselected queue.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['showInMenu']        = array(
	'Show in menu',
	'Please chose to show this category in the backend menu.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['useCustomMenuIcon'] = array(
	'Use custom icon',
	'Please chose if you want to use a custom icon in the menu.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['menuIcon']          = array(
	'Custom icon',
	'Please chose a custom backend menu item.'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['tstamp']            = array(
	'Revision date',
	'Date and time of the latest revision'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['category_legend']   = 'Category';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipients_legend'] = 'Recipients settings';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['theme_legend']      = 'Layout settings';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['transport_legend']  = 'Transport settings';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['queue_legend']      = 'Queue settings';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['expert_legend']     = 'Experts settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['byCategory']             = 'only in category';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['byNewsletterOrCategory'] = 'preselected in category, overwriteable in newsletter';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['byNewsletter']           = 'only in newsletter';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['new']        = array(
	'New category',
	'Create a new category'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['show']       = array(
	'Category details',
	'Show the details of category ID %s'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['edit']       = array(
	'Edit category',
	'Edit category ID %s'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['editheader'] = array(
	'Edit category settings',
	'Edit category settings ID %s'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['copy']       = array(
	'Duplicate category',
	'Duplicate category ID %s'
);
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['delete']     = array(
	'Delete category',
	'Delete category ID %s'
);
