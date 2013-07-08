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
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['title']             = array(
	'Title',
	'Please enter the newsletter category title.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['alias']             = array(
	'Alias',
	'The category alias is a unique reference to the category which can be called instead of its numeric ID.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['recipientsMode']    = array(
	'Recipients selection mode',
	'Please chose the recipients selection mode.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['recipients']        = array(
	'Recipients',
	'Please chose the preselected recipients.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['themeMode']         = array(
	'Layout selection mode',
	'Please chose the layout selection mode.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['theme']             = array(
	'Layout',
	'Please chose the preselected layout.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['transportMode']     = array(
	'Transport module selection mode',
	'Please chose the transport module selection mode.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['transport']         = array(
	'Transport module',
	'Please chose the preselected transport module.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['queueMode']         = array(
	'Queue selection mode',
	'Please chose the queue selection mode.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['queue']             = array(
	'Queue',
	'Please chose the preselected queue.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['boilerplates']        = array(
	'Contains boilerplates',
	'This category contain boilerplate mailings.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['showInMenu']        = array(
	'Show in menu',
	'Please chose to show this category in the backend menu.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['useCustomMenuIcon'] = array(
	'Use custom icon',
	'Please chose if you want to use a custom icon in the menu.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['menuIcon']          = array(
	'Custom icon',
	'Please chose a custom backend menu item.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['tstamp']            = array(
	'Revision date',
	'Date and time of the latest revision'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['category_legend']   = 'Category';
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['recipients_legend'] = 'Recipients settings';
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['theme_legend']      = 'Layout settings';
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['transport_legend']  = 'Transport settings';
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['queue_legend']      = 'Queue settings';
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['expert_legend']     = 'Experts settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['byCategory']             = 'only in category';
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['byMailingOrCategory'] = 'preselected in category, overwriteable in newsletter';
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['byMailing']           = 'only in newsletter';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['new']        = array(
	'New category',
	'Create a new category'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['show']       = array(
	'Category details',
	'Show the details of category ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['edit']       = array(
	'Edit category',
	'Edit category ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['editheader'] = array(
	'Edit category settings',
	'Edit category settings ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['copy']       = array(
	'Duplicate category',
	'Duplicate category ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_category']['delete']     = array(
	'Delete category',
	'Delete category ID %s'
);
