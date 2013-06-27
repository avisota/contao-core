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
			array('Avisota\Contao\DataContainer\Settings', 'onload_callback')
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
			'recipients'   => array(
				'avisota_salutations',
				'avisota_dont_disable_recipient_on_failure',
				'avisota_dont_disable_member_on_failure'
			),
			'subscription' => array(
				'avisota_subscribe_mail',
				'avisota_unsubscribe_mail'
			),
			'notification' => array(':hide', 'avisota_send_notification'),
			'cleanup'      => array(':hide', 'avisota_do_cleanup'),
			'transport'    => array(
				'avisota_default_transport',
				'avisota_max_send_time',
				'avisota_max_send_count',
				'avisota_max_send_timeout'
			),
			'developer'    => array(':hide', 'avisota_developer_mode')
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
		'avisota_chart'                  => array
		(
			'highstock' => array('avisota_chart_highstock_confirmed')
		),
	),
	// Fields
	'fields'                => array
	(
		'avisota_dont_disable_recipient_on_failure' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_dont_disable_recipient_on_failure'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'w50 clr')
		),
		'avisota_dont_disable_member_on_failure'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_dont_disable_member_on_failure'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'w50')
		),
		'avisota_salutations'                       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations'],
			'inputType' => 'multiColumnWizard',
			'eval'      => array(
				'columnFields' => array(
					'salutation' => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_salutation'],
						'inputType' => 'text',
						'eval'      => array('style' => 'width: 400px')
					),
					'title'      => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_title'],
						'inputType' => 'checkbox',
						'eval'      => array()
					),
					'firstname'  => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_firstname'],
						'inputType' => 'checkbox',
						'eval'      => array()
					),
					'lastname'   => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_lastname'],
						'inputType' => 'checkbox',
						'eval'      => array()
					)
				)
			)
		),
		'avisota_subscribe_mail'     => array
		(
			'exclude'          => true,
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_subscribe_mail'],
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\Settings', 'getBoilerplateNewsletters'),
			'eval'             => array('tl_class' => 'w50 clr')
		),
		'avisota_unsubscribe_mail'   => array
		(
			'exclude'          => true,
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_unsubscribe_mail'],
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\Settings', 'getBoilerplateNewsletters'),
			'eval'             => array('tl_class' => 'w50')
		),
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
		'avisota_notification_mail'   => array
		(
			'exclude'          => true,
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_mail'],
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\Settings', 'getBoilerplateNewsletters'),
			'eval'             => array('tl_class' => 'w50')
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
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_default_transport'],
			'inputType'  => 'select',
			'foreignKey' => 'tl_avisota_transport.title',
			'eval'       => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
			)
		),
		'avisota_max_send_time'                     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_time'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'digit',
				'tl_class'  => 'w50'
			)
		),
		'avisota_max_send_count'                    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_count'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'digit',
				'tl_class'  => 'w50'
			)
		),
		'avisota_max_send_timeout'                  => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_timeout'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'digit',
				'tl_class'  => 'w50'
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
