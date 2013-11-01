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
$GLOBALS['TL_LANG']['orm_avisota_queue']['type']                     = array(
	'Type',
	'Please select the type of the queue.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['title']                    = array(
	'Title',
	'Please enter the queue title.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['alias']                    = array(
	'Alias',
	'The queue alias is a unique reference to the queue which can be used instead of its ID.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['transport']                = array(
	'Transport module',
	'Please chose the transport module.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['maxSendTime']              = array(
	'Sending time',
	'Please enter the maximum time in seconds for each cycle.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['maxSendCount']             = array(
	'Sending count',
	'Please enter the maximum number of mails send per cycle.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['cyclePause']                = array(
	'Cycle pause',
	'Please enter the time in seconds between each cycle.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['simpleDatabaseQueueTable'] = array(
	'Table name',
	'Please enter a table name for the queue. The table should <strong>not</strong> start with <em>tl_</em> or <em>orm_</em>!'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['allowManualSending']       = array(
	'Allow manual sending',
	'Allow users to manual execute a queue and sending its contents.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['scheduledSending']         = array(
	'Scheduled sending',
	'Use sheduled sending algorithm for automated execution.'
);
$GLOBALS['TL_LANG']['orm_avisota_queue']['sendingTime']              = array(
	'Sending time chart',
	'Time chart that define execution times.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_queue']['queue_legend']     = 'Queue';
$GLOBALS['TL_LANG']['orm_avisota_queue']['transport_legend'] = 'Transport settings';
$GLOBALS['TL_LANG']['orm_avisota_queue']['config_legend']    = 'Queue settings';
$GLOBALS['TL_LANG']['orm_avisota_queue']['send_legend']      = 'Sending';


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
