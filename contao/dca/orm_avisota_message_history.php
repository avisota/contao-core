<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  MEN AT WORK 2013
 * @package    avisota
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Table orm_avisota_message_history
 * Entity Avisota\Contao:MessageHistory
 */
$GLOBALS['TL_DCA']['orm_avisota_message_history'] = array
(
	// Entity
	'entity'          => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_NONE
	),
	// Fields
	'fields'          => array
	(
		'id'            => array(
			'field' => array(
				'id'      => true,
				'type'    => 'string',
				'length'  => '36',
				'options' => array('fixed' => true),
			)
		),
		'message'        => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_message_history']['message'],
			'oneToOne'         => array(
				'id'           => true,
				'targetEntity' => 'Avisota\Contao\Entity\Message',
				'cascade'      => array('persist', 'detach', 'merge', 'refresh'),
				'joinColumns'  => array(
					array(
						'name'                 => 'message',
						'referencedColumnName' => 'id',
					)
				),
			),
		),		
		'createdAt'     => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'create')
			)
		),
		'updatedAt'     => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'update')
			)
		),
		'mailCount'        => array
		(
			'field' => array(
				'type' => 'integer',
				'default'  => 0,
			)
		),
		'viewCount'        => array
		(
			'field' => array(
				'type' => 'integer',
				'default'  => 0,
			)	
		),
		'clickCount'        => array
		(
			'field' => array(
				'type' => 'integer',
				'default'  => 0,
			)
		),
	)
);
