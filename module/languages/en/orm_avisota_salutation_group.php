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
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['title']             = array(
	'Title',
	'Please enter the newsletter group title.'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['alias']             = array(
	'Alias',
	'The group alias is a unique reference to the group which can be called instead of its ID.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['salutation_group_legend']   = 'Group';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['generate']        = array(
	'Generate default group',
	'Generate a default group, containing predefined salutations in the current language.'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['new']        = array(
	'New group',
	'Create a new group'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['show']       = array(
	'Group details',
	'Show the details of group ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['edit']       = array(
	'Edit group',
	'Edit group ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['editheader'] = array(
	'Edit group settings',
	'Edit group settings ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['copy']       = array(
	'Duplicate group',
	'Duplicate group ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['delete']     = array(
	'Delete group',
	'Delete group ID %s'
);


/**
 * Messages
 */
$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['group_generated'] = 'A new default group has been generated.';
