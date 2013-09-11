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
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Table mem_avisota_recipient_migrate
 */
$GLOBALS['TL_DCA']['mem_avisota_recipient_migrate'] = array
(
	// Config
	'config'       => array
	(
		'dataContainer' => 'General',
		'forceEdit'     => true,
	),
	// DataContainer
	'dca_config'   => array
	(
		'callback'      => 'DcGeneral\Callbacks\ContaoStyleCallbacks',
		'data_provider' => array
		(
			'default' => array
			(
				'class' => 'Avisota\Contao\DataContainer\DataProvider\RecipientMigrateDataProvider',
			),
		),
		'controller'    => 'DcGeneral\Controller\DefaultController',
		'view'          => 'DcGeneral\View\DefaultView'
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'migrate' => array('channels', 'overwrite', 'importFromMembers'),
		)
	),
	// Fields
	'fields'       => array
	(
		'channels'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['channels'],
			'inputType' => 'multiColumnWizard',
			'eval'      => array(
				'columnFields' => array
				(
					'channel'     => array
					(
						'label'      => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['channels_channel'],
						'inputType'  => 'select',
						'foreignKey' => 'tl_newsletter_channel.title',
						'eval'       => array(
							'style'              => 'width:250px',
							'mandatory'          => true,
							'includeBlankOption' => true,
							'chosen'             => true,
						)
					),
					'mailingList' => array
					(
						'label'            => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['channels_mailingList'],
						'inputType'        => 'select',
						'options_callback' => array(
							'Avisota\Contao\DataContainer\OptionsBuilder',
							'getMailingListOptions'
						),
						'eval'             => array(
							'style'              => 'width:250px',
							'mandatory'          => true,
							'includeBlankOption' => true,
							'chosen'             => true,
						)
					),
				),
				'mandatory'    => true,
				'multiple'     => true
			),
		),
		'overwrite'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['overwrite'],
			'inputType' => 'checkbox',
		),
		'importFromMembers' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['importFromMembers'],
			'inputType' => 'checkbox',
		),
	)
);
