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
 * Table orm_avisota_recipient_subscription
 * Entity Avisota\Contao:RecipientSubscription
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_subscription'] = array
(
	// Fields
	'fields'       => array
	(
		'recipient' => array
		(
			'field' => array(
				'id' => true,
				'type'   => 'integer',
			)
		),
		'list'  => array
		(
			'field' => array(
				'id' => true,
				'type'   => 'string',
				'length' => 64,
			)
		),
		'confirmationSent'  => array
		(
			'field' => array(
				'type'   => 'timestamp',
			)
		),
		'reminderSent'  => array
		(
			'field' => array(
				'type'   => 'timestamp',
			)
		),
		'reminderCount'  => array
		(
			'field' => array(
				'type'   => 'timestamp',
			)
		),
		'confirmed'  => array
		(
			'field' => array(
				'type'   => 'boolean',
			)
		),
		'confirmedAt'  => array
		(
			'field' => array(
				'type'   => 'timestamp',
			)
		),
		'token'  => array
		(
			'field' => array(
				'type'   => 'string',
				'length' => 8,
				'fixed'  => true,
			)
		),
	)
);
