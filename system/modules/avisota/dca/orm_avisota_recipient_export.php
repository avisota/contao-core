<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Table orm_avisota_recipient_export
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_export'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onload_callback'   => array
		(
			array('orm_avisota_recipient_export', 'onload_callback'),
		),
		'onsubmit_callback' => array
		(
			array('orm_avisota_recipient_export', 'onsubmit_callback'),
		)
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'format' => array(':hide', 'delimiter', 'enclosure', 'fields')
		)
	),
	// Fields
	'fields'       => array
	(
		'delimiter' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_export']['delimiter'],
			'inputType' => 'select',
			'options'   => array('comma', 'semicolon', 'tabulator', 'linebreak'),
			'reference' => &$GLOBALS['TL_LANG']['MSC'],
			'eval'      => array('mandatory' => true, 'tl_class' => 'w50')
		),
		'enclosure' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_export']['enclosure'],
			'inputType' => 'select',
			'options'   => array('double', 'single'),
			'reference' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_export'],
			'eval'      => array('tl_class' => 'w50')
		),
		'fields'    => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_recipient_export']['fields'],
			'inputType'        => 'checkboxWizard',
			'options_callback' => array('orm_avisota_recipient_export', 'getFields'),
			'eval'             => array('multiple' => true, 'tl_class' => 'clr')
		)
	)
);
