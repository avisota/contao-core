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
 * Table tl_avisota_recipient_remove
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_remove'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onsubmit_callback' => array
		(
			array('tl_avisota_recipient_remove', 'onsubmit_callback'),
		)
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'remove' => array('source', 'upload', 'emails')
		)
	),
	// Fields
	'fields'       => array
	(
		'source' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['source'],
			'inputType' => 'fileTree',
			'eval'      => array('fieldType' => 'checkbox', 'files' => true, 'filesOnly' => true, 'extensions' => 'csv')
		),
		'upload' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['upload'],
			'inputType' => 'upload'
		),
		'emails' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_remove']['emails'],
			'inputType' => 'textarea'
		)
	)
);
