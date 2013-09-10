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
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['title']            = array(
	'Title ',
	'Here you can enter the title of this distribution group.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['alias']            = array(
	'Alias',
	' The alias is a unique reference that can be called instead of the numeric ID.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['viewOnlinePage']   = array(
	'View Online Page ',
	'Please select the page that the subscriber will be redirected to if they wish to view the Newsletter online.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['subscriptionPage'] = array(
	'Manage Subscription Page ',
	'Please select a page that the subscriber will be redirected to if they wish to change their Newsletter preferences.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['list_legend']   = 'Distribution Group';
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['expert_legend'] = 'Redirect Settings';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['new']        = array(
	'New Distribution List',
	'Add a new distributor list'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['show']       = array(
	'Distribution Details',
	'Details of the distribution ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['copy']       = array('Copy Distribution', 'Copy distribution ID %s');
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['delete']     = array(
	'Delete Distribution',
	'Delete distribution ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['edit']       = array(
	'Edit Distribution',
	'Edit details of distribution ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['editheader'] = array(
	'Edit Header',
	'Edit the header of distribution ID %s'
);
