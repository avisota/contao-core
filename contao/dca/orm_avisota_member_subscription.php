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
 * Table orm_avisota_member_subscription
 * Entity Avisota\Contao:MemberSubscription
 */
$GLOBALS['TL_DCA']['orm_avisota_member_subscription'] = array
(
	// Entity
	'entity' => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_NONE
	),
	// Fields
	'fields' => array
	(
		'member'        => array
		(
			'field' => array(
				'id'   => true,
				'type' => 'integer',
			)
			
		),
		'list'             => array
		(
			'field' => array(
				'id'   => true,
				'type' => 'string',
			)
		),
		'confirmationSent' => array
		(
			'field' => array(
				'type'     => 'timestamp',
				'nullable' => true,
			)
		),
		'reminderSent'     => array
		(
			'field' => array(
				'type'     => 'timestamp',
				'nullable' => true,
			)
		),
		'reminderCount'    => array
		(
			'field' => array(
				'type'     => 'timestamp',
				'nullable' => true,
			)
		),
		'confirmed'        => array
		(
			'field' => array(
				'type' => 'boolean',
			)
		),
		'confirmedAt'      => array
		(
			'field' => array(
				'type'     => 'timestamp',
				'nullable' => true,
			)
		),
		'token'            => array
		(
			'field' => array(
				'type'     => 'string',
				'length'   => 16,
				'options'  => array('fixed' => true),
				'nullable' => true,
			)
		),
	)
);
