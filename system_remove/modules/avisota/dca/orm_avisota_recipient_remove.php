<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Table orm_avisota_recipient_remove
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_remove'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onsubmit_callback' => array
		(
			array('orm_avisota_recipient_remove', 'onsubmit_callback'),
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
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_remove']['source'],
			'inputType' => 'fileTree',
			'eval'      => array('fieldType' => 'checkbox', 'files' => true, 'filesOnly' => true, 'extensions' => 'csv')
		),
		'upload' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_remove']['upload'],
			'inputType' => 'upload'
		),
		'emails' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_remove']['emails'],
			'inputType' => 'textarea'
		)
	)
);
