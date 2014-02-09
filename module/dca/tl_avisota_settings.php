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

use ContaoCommunityAlliance\Contao\EventDispatcher\Factory\CreateOptionsEventCallbackFactory;

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
			'notification' => array('avisota_send_notification'),
			'cleanup'      => array('avisota_do_cleanup'),
			'transport'    => array('avisota_default_transport'),
			'developer'    => array('avisota_developer_mode')
		)
	),
	// Subpalettes
	'metasubpalettes'       => array
	(
		'avisota_send_notification' => array(
			'avisota_notification_time',
			'avisota_notification_count',
			'avisota_notification_mail'
		),
		'avisota_do_cleanup'        => array('avisota_cleanup_time'),
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
		'avisota_send_notification'                 => array
		(
			'exclude'   => true,
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_send_notification'],
			'inputType' => 'checkbox',
			'eval'      => array(
				'submitOnChange' => true,
				'tl_class'       => 'clr'
			)
		),
		'avisota_notification_time'                 => array
		(
			'exclude'   => true,
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_time'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'digit',
				'tl_class'  => 'w50'
			)
		),
		'avisota_notification_count'                => array
		(
			'exclude'   => true,
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_count'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'digit',
				'tl_class'  => 'w50'
			)
		),
		'avisota_notification_mail'                 => array
		(
			'exclude'          => true,
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_mail'],
			'inputType'        => 'select',
			'options_callback' => CreateOptionsEventCallbackFactory::createCallback('avisota.create-boilerplate-message-options'),
			'eval'             => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			)
		),
		'avisota_do_cleanup'                        => array
		(
			'exclude'   => true,
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_do_cleanup'],
			'inputType' => 'checkbox',
			'eval'      => array(
				'submitOnChange' => true,
				'tl_class'       => 'm12 w50 clr'
			)
		),
		'avisota_cleanup_time'                      => array
		(
			'exclude'   => true,
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_cleanup_time'],
			'default'   => 7,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'digit',
				'tl_class'  => 'w50'
			)
		),
		'avisota_default_transport'                 => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_default_transport'],
			'inputType'        => 'select',
			'options_callback' => CreateOptionsEventCallbackFactory::createCallback('avisota.create-transport-options'),
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
