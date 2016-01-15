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
 * Table orm_avisota_recipient_notify
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_notify'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onload_callback'   => array
		(
			array('orm_avisota_recipient_notify', 'onload_callback'),
		),
		'onsubmit_callback' => array
		(
			array('orm_avisota_recipient_notify', 'onsubmit_callback'),
		)
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'notify' => array('recipient', 'confirmations', 'notifications', 'overdue')
		)
	),
	// Fields
	'fields'       => array
	(
		'recipient'     => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['recipient'],
			'inputType'        => 'select',
			'options_callback' => array('orm_avisota_recipient_notify', 'getRecipients'),
			'eval'             => array('submitOnChange' => true)
		),
		'confirmations' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['confirmations'],
			'inputType' => 'checkbox',
			'options'   => array(),
			'eval'      => array('multiple' => true)
		),
		'notifications' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['notifications'],
			'inputType' => 'checkbox',
			'options'   => array(),
			'eval'      => array('multiple' => true)
		),
		'overdue'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['overdue'],
			'inputType' => 'checkbox',
			'options'   => array(),
			'eval'      => array('multiple' => true)
		)
	)
);
