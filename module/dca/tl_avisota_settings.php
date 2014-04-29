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
 * System configuration
 */

$GLOBALS['TL_DCA']['tl_avisota_settings'] = array
(
	// Config
	'config'                => array
	(
		'dataContainer'   => 'File',
		'closed'          => true,
		'onload_callback' => array(
			array('Avisota\Contao\Core\DataContainer\Settings', 'onload_callback')
		)
	),
	// Palettes
	'palettes'              => array
	(
		'__selector__' => array(),
	),
	'metapalettes'          => array
	(
		'default' => array(
			'transport'    => array('avisota_default_transport'),
			'developer'    => array('avisota_developer_mode')
		)
	),
	// Subpalettes
	'metasubpalettes'       => array
	(
		'avisota_developer_mode'    => array('avisota_developer_email'),
	),
	'metasubselectpalettes' => array
	(
		'avisota_chart' => array
		(
			'highstock' => array('avisota_chart_highstock_confirmed')
		),
	),
	// Fields
	'fields'                => array
	(
		'avisota_default_transport'                 => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_default_transport'],
			'inputType'        => 'select',
			'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
				\Avisota\Contao\Core\CoreEvents::CREATE_TRANSPORT_OPTIONS
			),
			'eval'             => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			)
		),
		'avisota_developer_mode'                    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_developer_mode'],
			'inputType' => 'checkbox',
			'eval'      => array(
				'submitOnChange' => true,
				'tl_class'       => 'clr m12 w50'
			)
		),
		'avisota_developer_email'                   => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_developer_email'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'email',
				'tl_class'  => 'w50'
			)
		)
	)
);
