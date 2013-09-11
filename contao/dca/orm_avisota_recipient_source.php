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
 * Table orm_avisota_recipient_source
 * Entity Avisota\Contao:RecipientSource
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_source'] = array
(
	// Entity
	'entity'       => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
	),
	// Config
	'config'       => array
	(
		'dataContainer'     => 'General',
		'enableVersioning'  => true,
		'onload_callback'   => array(
			array('Avisota\Contao\DataContainer\RecipientSource', 'onload_callback')
		),
		'onsubmit_callback' => array(
			array('Avisota\Contao\DataContainer\RecipientSource', 'onsubmit_callback'),
			array('Avisota\Contao\Backend', 'regenerateDynamics')
		)
	),
	// DataContainer
	'dca_config'   => array
	(
		'callback'      => 'DcGeneral\Callbacks\ContaoStyleCallbacks',
		'data_provider' => array
		(
			'default' => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityData',
				'source' => 'orm_avisota_recipient_source'
			)
		),
		'controller'    => 'DcGeneral\Controller\DefaultController',
		'view'          => 'DcGeneral\View\DefaultView'
	),
	// List
	'list'         => array
	(
		'sorting'           => array
		(
			'mode'   => 1,
			'flag'   => 11,
			'fields' => array('sorting'),
		),
		'label'             => array
		(
			'fields' => array('title', 'type'),
			'format' => '%s <span style="color:#b3b3b3; padding-left:3px;">(%s)</span>'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations'        => array
		(
			'edit'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'cut'    => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['cut'],
				'href'  => 'act=paste&amp;mode=cut',
				'icon'  => 'cut.gif'
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['toggle'],
				'icon'            => 'visible.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback' => array('Avisota\Contao\DataContainer\RecipientSource', 'toggleIcon')
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			),
		),
	),
	// Palettes
	'palettes'     => array(
		'__selector__' => array('type')
	),
	// Meta Palettes
	'metapalettes' => array
	(
		'default'                    => array(
			'source' => array('type')
		),
		'integrated'                 => array(
			'source'     => array('title', 'alias', 'type'),
			'integrated' => array('integratedRecipientManageSubscriptionPage'),
			'details'    => array('integratedDetails', 'salutation'),
			'expert'     => array('disable'),
		),
		'integrated_by_mailing_list' => array(
			'source'     => array('title', 'alias', 'type'),
			'integrated' => array('mailingLists', 'integratedRecipientManageSubscriptionPage'),
			'details'    => array('integratedDetails', 'salutation'),
			'expert'     => array('disable'),
		),
		'csv_file'                   => array(
			'source'  => array('title', 'alias', 'type'),
			'csvFile' => array('csvFileSrc', 'csvColumnAssignment'),
			'details' => array('salutation'),
			'expert'  => array('disable'),
		),
		'dummy'                      => array(
			'source'  => array('title', 'alias', 'type'),
			'dummy'   => array('dummyMinCount', 'dummyMaxCount'),
			'details' => array('salutation'),
			'expert'  => array('disable'),
		),
	),
	// Fields
	'fields'       => array
	(
		'id'                                        => array(
			'field' => array(
				'id'      => true,
				'type'    => 'string',
				'length'  => '36',
				'options' => array('fixed' => true),
			)
		),
		'createdAt'                                 => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'create')
			)
		),
		'updatedAt'                                 => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'update')
			)
		),
		'sorting'                                   => array
		(
			'field' => array(
				'type' => 'integer',
			)
		),
		'type'                                      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['type'],
			'inputType' => 'select',
			'options'   => array_keys($GLOBALS['AVISOTA_RECIPIENT_SOURCE']),
			'reference' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source'],
			'eval'      => array(
				'mandatory'          => true,
				'submitOnChange'     => true,
				'includeBlankOption' => true,
				'helpwizard'         => true,
				'tl_class'           => 'w50'
			)
		),
		'title'                                     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['title'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'unique'    => true,
				'maxlength' => 255,
				'tl_class'  => 'w50'
			)
		),
		'alias'                                     => array
		(
			'label'           => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['alias'],
			'exclude'         => true,
			'search'          => true,
			'inputType'       => 'text',
			'eval'            => array(
				'rgxp'              => 'alnum',
				'unique'            => true,
				'spaceToUnderscore' => true,
				'maxlength'         => 128,
				'tl_class'          => 'w50'
			),
			'setter_callback' => array
			(
				array('Contao\Doctrine\ORM\Helper', 'generateAlias')
			)
		),
		// generic fields
		'mailingLists'                              => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['mailingLists'],
			'inputType'        => 'checkbox',
			'options_callback' => array('Avisota\Contao\DataContainer\OptionsBuilder', 'getMailingListOptions'),
			'eval'             => array(
				'mandatory' => true,
				'multiple'  => true,
			),
			'manyToMany'       => array(
				'targetEntity' => 'Avisota\Contao\Entity\MailingList',
				'cascade'      => array('all'),
				'joinTable'    => array(
					'name'               => 'orm_avisota_recipient_source_mailing_lists',
					'joinColumns'        => array(
						array(
							'name'                 => 'recipientSource',
							'referencedColumnName' => 'id',
						),
					),
					'inverseJoinColumns' => array(
						array(
							'name'                 => 'mailingList',
							'referencedColumnName' => 'id',
						),
					),
				),
			),
		),
		// integrated recipients
		'integratedDetails'                         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['integratedDetails'],
			'default'   => 'integrated_details',
			'inputType' => 'select',
			'options'   => array('integrated_details', 'member_details', 'integrated_member_details'),
			'reference' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source'],
			'eval'      => array(
				'mandatory' => true,
				'tl_class'  => 'w50',
			),
			'field'     => array(),
		),
		/*
		'viewOnlinePage'                            => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['viewOnlinePage'],
			'exclude'   => true,
			'inputType' => 'pageTree',
			'eval'      => array(
				'fieldType' => 'radio',
				'mandatory' => true
			)
		),
		*/
		'integratedRecipientManageSubscriptionPage' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['integratedRecipientManageSubscriptionPage'],
			'exclude'   => true,
			'inputType' => 'pageTree',
			'eval'      => array(
				'fieldType' => 'radio',
				'mandatory' => true,
			)
		),
		// csv source
		'csvFileSrc'                                => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['csvFileSrc'],
			'inputType' => 'fileTree',
			'eval'      => array(
				'mandatory'  => true,
				'files'      => true,
				'filesOnly'  => true,
				'extensions' => 'csv',
				'fieldType'  => 'radio'
			),
			'field'     => array(),
		),
		'csvColumnAssignment'                       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['csvColumnAssignment'],
			'inputType' => 'multiColumnWizard',
			'eval'      => array(
				'columnFields' => array(
					'column' => array(
						'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['csvColumnAssignmentColumn'],
						'inputType' => 'select',
						'options'   => range(1, 30),
						'eval'      => array()
					),
					'field'  => array(
						'label'            => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['csvColumnAssignmentField'],
						'inputType'        => 'select',
						'options_callback' => array(
							'Avisota\Contao\DataContainer\RecipientSource',
							'getRecipientColumns'
						),
						'eval'             => array()
					)
				)
			),
			'field'     => array(
				'type'     => 'serialized',
				'length'   => 65532,
				'nullable' => true,
			),
		),
		// dummy source
		'dummyMinCount'                             => array
		(
			'default'   => false,
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['dummyMinCount'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'tl_class'  => 'w50',
				'rgxp'      => 'digit',
			),
			'field'     => array(
				'type' => 'integer',
			),
		),
		'dummyMaxCount'                             => array
		(
			'default'   => false,
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['dummyMaxCount'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'tl_class'  => 'w50',
				'rgxp'      => 'digit',
			),
			'field'     => array(
				'type' => 'integer',
			),
		),
		// details settings
		'salutation'                                => array
		(
			'default'          => false,
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['salutation'],
			'inputType'        => 'select',
			'options_callback' => array(
				'Avisota\Contao\DataContainer\OptionsBuilder',
				'getSalutationGroups'
			),
			'eval'             => array('tl_class' => 'w50', 'includeBlankOption' => true)
		),
		// expert settings
		'disable'                                   => array
		(
			'default'   => false,
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['disable'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12')
		),
	)
);
