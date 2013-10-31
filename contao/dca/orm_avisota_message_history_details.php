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
 * Table orm_avisota_message_history_details
 * Entity Avisota\Contao:MessageHistoryDetails
 */
$GLOBALS['TL_DCA']['orm_avisota_message_history_details'] = array
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
		'history'        => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_message_history_details']['history'],
			'oneToOne'         => array(
				'id'           => true,
				'targetEntity' => 'Avisota\Contao\Entity\MessageHistory',
				'cascade'      => array('persist', 'detach', 'merge', 'refresh'),
				'joinColumns'  => array(
					array(
						'name'                 => 'history',
						'referencedColumnName' => 'id',
					)
				),
			),
		),
	)
);
