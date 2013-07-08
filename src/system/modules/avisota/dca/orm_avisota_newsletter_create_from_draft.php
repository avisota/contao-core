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
 * Table orm_avisota_message_create_from_draft
 */
$GLOBALS['TL_DCA']['orm_avisota_message_create_from_draft'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onload_callback'   => array
		(
			array('orm_avisota_message_create_from_draft', 'onload_callback'),
		),
		'onsubmit_callback' => array
		(
			array('orm_avisota_message_create_from_draft', 'onsubmit_callback'),
		)
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array(
			'create' => array('category', 'subject', 'draft')
		)
	),
	// Fields
	'fields'       => array
	(
		'category' => array
		(
			'label'      => &$GLOBALS['TL_LANG']['orm_avisota_message_create_from_draft']['category'],
			'inputType'  => 'select',
			'foreignKey' => 'orm_avisota_message_category.title',
			'eval'       => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
			)
		),
		'subject'  => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_create_from_draft']['subject'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
			)
		),
		'draft'    => array
		(
			'label'      => &$GLOBALS['TL_LANG']['orm_avisota_message_create_from_draft']['draft'],
			'inputType'  => 'radio',
			'foreignKey' => 'orm_avisota_message_draft.title',
			'eval'       => array(
				'mandatory' => true,
				'tl_class'  => 'clr'
			)
		)
	)
);
