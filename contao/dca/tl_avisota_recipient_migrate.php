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
 * Table tl_avisota_recipient_migrate
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_migrate'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onsubmit_callback' => array
		(
			array('tl_avisota_recipient_migrate', 'onsubmit_callback'),
		)
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'migrate' => array('source', 'personals', 'force')
		)
	),
	// Fields
	'fields'       => array
	(
		'source'    => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['source'],
			'inputType'  => 'checkbox',
			'foreignKey' => 'tl_newsletter_channel.title',
			'eval'       => array('mandatory' => true, 'multiple' => true)
		),
		'personals' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['personals'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12')
		),
		'force'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['force'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12')
		)
	)
);
