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
 * Table orm_avisota_recipient_blacklist
 * Entity Avisota\Contao:RecipientBlacklist
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_blacklist'] = array
(
	// Entity
	'entity' => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_NONE
	),
	// Fields
	'fields' => array
	(
		'email' => array
		(
			'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_blacklist']['email'],
			'field' => array(
				'id' => true,
				'type'   => 'string',
				'length' => 32
			)
		),
		'list'  => array
		(
			'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_blacklist']['list'],
			'field' => array(
				'id' => true,
				'type'   => 'string',
				'length' => 64
			)
		),
	)
);
