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
 * Table orm_avisota_recipient_migrate
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_migrate'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onsubmit_callback' => array
		(
			array('orm_avisota_recipient_migrate', 'onsubmit_callback'),
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
			'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient_migrate']['source'],
			'inputType'  => 'checkbox',
			'foreignKey' => 'tl_newsletter_channel.title',
			'eval'       => array('mandatory' => true, 'multiple' => true)
		),
		'personals' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_migrate']['personals'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12')
		),
		'force'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_migrate']['force'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12')
		)
	)
);
