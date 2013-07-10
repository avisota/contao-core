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
 * Table orm_avisota_mailing_list
 * Entity Avisota\Contao:MailingList
 */
$GLOBALS['TL_DCA']['orm_avisota_mailing_list'] = array
(
	// Entity
	'entity' => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
	),
	// Config
	'config'       => array
	(
		'dataContainer'     => 'General',
		'enableVersioning'  => true,
		'onload_callback'   => array
		(
			array('Avisota\Contao\DataContainer\MailingList', 'checkPermission')
		),
		'onsubmit_callback' => array
		(
			array('Avisota\Contao\Backend', 'regenerateDynamics')
		)
	),
	// DataContainer
	'dca_config'   => array
	(
		'callback'      => 'GeneralCallbackDefault',
		'data_provider' => array
		(
			'default' => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityData',
				'source' => 'orm_avisota_mailing_list'
			)
		),
		'controller'    => 'GeneralControllerDefault',
		'view'          => 'GeneralViewDefault'
	),
	// List
	'list'         => array
	(
		'sorting'           => array
		(
			'mode'        => 1,
			'flag'        => 1,
			'fields'      => array('title'),
			'panelLayout' => 'search,limit'
		),
		'label'             => array
		(
			'fields'         => array('title'),
			'format'         => '%s',
			'label_callback' => array('Avisota\Contao\DataContainer\MailingList', 'getLabel')
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
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['edit'],
				'href'            => 'act=edit',
				'icon'            => 'edit.gif',
				'button_callback' => array('Avisota\Contao\DataContainer\MailingList', 'editList')
			),
			'copy'   => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['copy'],
				'href'            => 'act=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\MailingList', 'copyCategory')
			),
			'delete' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\MailingList', 'deleteCategory')
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		),
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'list'   => array('title', 'alias'),
			'expert' => array('integratedRecipientManageSubscriptionPage')
		)
	),
	// Fields
	'fields'       => array
	(
		'id'                                        => array(
			'field' => array(
				'id'   => true,
				'type' => 'string',
				'length' => '36',
				'options' => array('fixed' => true),
			)
		),
		'createdAt'                                 => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'create')
			)
		),
		'updatedAt'                                => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'update')
			)
		),
		'title'                                     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['title'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
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
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['integratedRecipientManageSubscriptionPage'],
			'exclude'   => true,
			'inputType' => 'pageTree',
			'eval'      => array(
				'fieldType' => 'radio',
				'mandatory' => true
			)
		)
	)
);
