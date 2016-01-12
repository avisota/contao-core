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
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['title']            = array(
	'Name',
	'Please enter the name of the mailing list.'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['alias']            = array(
	'Alias',
	'The mailing list alias is a unique reference to the article which can be called instead of its ID.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['list_legend']   = 'Mailing list';
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['expert_legend'] = 'Expert settings';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['new']    = array('New mailing list', 'Add a new mailing list');
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['show']   = array(
	'Mailing list details',
	'Show the details of mailing list ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['copy']   = array(
	'Duplicate mailing list',
	'Duplicate mailing list ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['delete'] = array('Delete mailing list', 'Delete mailing list ID %s');
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['edit']   = array(
	'Edit mailing list',
	'Edit mailing list ID %s'
);

/**
 * Label
 */
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['label_recipients'] = '%1$d recipients (<span title="%2$d active recipients">%2$d</span> / <span title="%3$d inactive recipients">%3$d</span>)';
$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['label_members']    = '%1$d members (<span title="%2$d active members">%2$d</span> / <span title="%3$d inactive members">%3$d</span>)';
