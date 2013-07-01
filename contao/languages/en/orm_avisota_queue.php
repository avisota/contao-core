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
$GLOBALS['TL_LANG']['orm_avisota_queue']['type']               = array(
	'Type',
	'Please select the type of the queue.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['title']              = array(
	'Title',
	'Please enter the transport module title.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['alias']              = array(
	'Alias',
	'The queue alias is a unique reference to the queue which can be used instead of its numeric ID.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['allowManualSending'] = array(
	'Allow manual sending',
	'Allow users to manual execute a queue and sending its contents.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['scheduledSending']   = array(
	'Scheduled sending',
	'Use sheduled sending algorithm for automated execution.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['sendingTime']        = array(
	'Sending time chart',
	'Time chart that define execution times.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_queue']['queue_legend'] = 'Queue';
$GLOBALS['TL_LANG']['orm_avisota_queue']['send_legend']  = 'Sending';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_queue']['simpleDatabase'] = 'Simple database driven queue';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_queue']['new']    = array(
	'New queue',
	'Create a new queue'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['show']   = array(
	'Queue details',
	'Show the details of queue ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['edit']   = array(
	'Edit queue',
	'Edit queue ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['delete'] = array(
	'Delete queue',
	'Delete queue ID %s'
);
