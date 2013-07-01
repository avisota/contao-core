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
 * Table orm_avisota_recipient_import
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_import'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onload_callback'   => array
		(
			array('orm_avisota_recipient_import', 'onload_callback'),
		),
		'onsubmit_callback' => array
		(
			array('orm_avisota_recipient_import', 'onsubmit_callback'),
		)
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'import' => array('source', 'upload'),
			'format' => array(':hide', 'delimiter', 'enclosure', 'columns', 'overwrite', 'force')
		)
	),
	// Fields
	'fields'       => array
	(
		'source'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['source'],
			'inputType' => 'fileTree',
			'eval'      => array('fieldType' => 'checkbox', 'files' => true, 'filesOnly' => true, 'extensions' => 'csv')
		),
		'upload'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['upload'],
			'inputType' => 'upload'
		),
		'delimiter' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['delimiter'],
			'inputType' => 'select',
			'options'   => array('comma', 'semicolon', 'tabulator', 'linebreak'),
			'reference' => &$GLOBALS['TL_LANG']['MSC'],
			'eval'      => array('mandatory' => true, 'tl_class' => 'w50')
		),
		'enclosure' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['enclosure'],
			'inputType' => 'select',
			'options'   => array('double', 'single'),
			'reference' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_import'],
			'eval'      => array('tl_class' => 'w50')
		),
		'columns'   => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['columns'],
			'inputType' => 'multiSelectWizard',
			'eval'      => array(
				'columnsCallback' => array('orm_avisota_recipient_import', 'createFieldSelectorArray'),
				'storeCallback'   => array('orm_avisota_recipient_import', 'storeFieldSelectorArray'),
				'tl_class'        => 'clr'
			)
		),
		'overwrite' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['overwrite'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'w50 m12')
		),
		'force'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_import']['force'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'w50 m12')
		)
	)
);
