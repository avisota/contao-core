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
 * Table orm_avisota_recipient
 * Entity Avisota\Contao:Recipient
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient'] = array
(
	// Entity
	'entity'       => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
	),
	// Config
	'config'       => array
	(
		'dataContainer'     => 'General',
		'switchToEdit'      => true,
		'enableVersioning'  => true,
		'onload_callback'   => array
		(
			array('Avisota\Contao\DataContainer\Recipient', 'checkPermission'),
			array('Avisota\Contao\DataContainer\Recipient', 'filterByMailingLists'),
			array('Avisota\Contao\DataContainer\Recipient', 'onload_callback')
		),
		'onsubmit_callback' => array
		(
			array('Avisota\Contao\DataContainer\Recipient', 'onsubmit_callback')
		),
		'ondelete_callback' => array
		(
			array('Avisota\Contao\DataContainer\Recipient', 'ondelete_callback')
		),
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
				'source' => 'orm_avisota_recipient'
			),
			'tl_user' => array
			(
				'class'  => 'GeneralDataDefault',
				'source' => 'tl_user'
			),
		),
		'controller'    => 'DcGeneral\Controller\DefaultController',
		'view'          => 'DcGeneral\View\DefaultView'
	),
	// List
	'list'         => array
	(
		'sorting'           => array
		(
			'mode'        => 2,
			'fields'      => array('email'),
			'panelLayout' => 'filter;sort,search,limit',
		),
		'label'             => array
		(
			'fields'         => array('firstname', 'lastname', 'email'),
			'format'         => '%s %s &lt;%s&gt;',
			'label_callback' => array('Avisota\Contao\DataContainer\Recipient', 'getLabel')
		),
		'global_operations' => array
		(
			'migrate' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['migrate'],
				'href'       => 'table=orm_avisota_recipient_migrate&amp;act=edit',
				'class'      => 'header_recipient_migrate recipient_tool',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
			'import'  => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['import'],
				'href'       => 'table=orm_avisota_recipient_import&amp;act=edit',
				'class'      => 'header_recipient_import recipient_tool',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
			'export'  => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['export'],
				'href'       => 'table=orm_avisota_recipient_export&amp;act=edit',
				'class'      => 'header_recipient_export recipient_tool',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
			'remove'  => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['remove'],
				'href'       => 'table=orm_avisota_recipient_remove&amp;act=edit',
				'class'      => 'header_recipient_remove recipient_tool',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
			'all'     => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations'        => array
		(
			'edit'                => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['edit'],
				'href'            => 'act=edit',
				'icon'            => 'edit.gif',
				'button_callback' => array('Avisota\Contao\DataContainer\Recipient', 'editRecipient')
			),
			'delete'              => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\Recipient', 'deleteRecipient')
			),
			'delete_no_blacklist' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['delete_no_blacklist'],
				'href'            => 'act=delete&amp;blacklist=false',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\Recipient', 'deleteRecipientNoBlacklist')
			),
			'show'                => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			),
			'notify'              => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['notify'],
				'href'            => '',
				'icon'            => 'system/modules/avisota/html/notify.png',
				'button_callback' => array('Avisota\Contao\DataContainer\Recipient', 'notify')
			),
		),
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'recipient' => array('email'),
			/* 'subscription' => array('lists', 'subscriptionAction'), */
			'personals' => array('salutation', 'title', 'firstname', 'lastname', 'gender'),
			'tracing'   => array('permitPersonalTracing')
		)
	),
	// Fields
	'fields'       => array
	(
		'id'                    => array(
			'field' => array(
				'id'      => true,
				'type'    => 'string',
				'length'  => '36',
				'options' => array('fixed' => true),
			),
		),
		'createdAt'             => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'create')
			)
		),
		'updatedAt'             => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'update')
			)
		),
		'email'                 => array
		(
			'label'         => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['email'],
			'exclude'       => true,
			'search'        => true,
			'sorting'       => true,
			'flag'          => 1,
			'inputType'     => 'text',
			'eval'          => array(
				'tl_class'   => 'w50',
				'rgxp'       => 'email',
				'mandatory'  => true,
				'maxlength'  => 255,
				'importable' => true,
				'exportable' => true
			),
			'save_callback' => array
			(
				array('Avisota\Contao\DataContainer\Recipient', 'saveEmail')
			)
		),
		'lists'                 => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['lists'],
			'inputType'        => 'checkbox',
			'options_callback' => array('Avisota\Contao\DataContainer\OptionsBuilder', 'getMailingListOptions'),
			'eval'             => array(
				'multiple'       => true,
				'doNotSaveEmpty' => true,
				'doNotCopy'      => true,
				'doNotShow'      => true,
				'tl_class'       => 'clr'
			),
			'load_callback'    => array
			(
				array('Avisota\Contao\DataContainer\Recipient', 'loadMailingLists')
			),
			'save_callback'    => array
			(
				array('Avisota\Contao\DataContainer\Recipient', 'validateBlacklist'),
				array('Avisota\Contao\DataContainer\Recipient', 'saveMailingLists')
			),
			'field'            => false,
		),
		'subscriptionAction'    => array
		(
			'label'         => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscriptionAction'],
			'inputType'     => 'select',
			'options'       => array('sendConfirmation', 'activateSubscription', 'doNothink'),
			'reference'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient'],
			'eval'          => array(
				'doNotSaveEmpty' => true,
				'doNotCopy'      => true,
				'doNotShow'      => true
			),
			'save_callback' => array(array('Avisota\Contao\DataContainer\Recipient', 'saveSubscriptionAction')),
			'field'         => false,
		),
		'salutation'            => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['salutation'],
			'exclude'   => true,
			'filter'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'select',
			'options'   => array(),
			//array_combine($GLOBALS['TL_CONFIG']['avisota_salutations'], $GLOBALS['TL_CONFIG']['avisota_salutations']),
			'eval'      => array(
				'maxlength'          => 255,
				'includeBlankOption' => true,
				'importable'         => true,
				'exportable'         => true,
				'feEditable'         => true,
				'tl_class'           => 'w50'
			),
			'field'     => array(
			),
		),
		'title'                 => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['title'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'  => 255,
				'importable' => true,
				'exportable' => true,
				'feEditable' => true,
				'tl_class'   => 'w50'
			),
			'field'     => array(
			),
		),
		'firstname'             => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['firstname'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'  => 255,
				'importable' => true,
				'exportable' => true,
				'feEditable' => true,
				'tl_class'   => 'w50'
			),
			'field'     => array(
			),
		),
		'lastname'              => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['lastname'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'  => 255,
				'importable' => true,
				'exportable' => true,
				'feEditable' => true,
				'tl_class'   => 'w50'
			),
			'field'     => array(
			),
		),
		'gender'                => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['gender'],
			'exclude'   => true,
			'filter'    => true,
			'sorting'   => true,
			'inputType' => 'select',
			'options'   => array('male', 'female'),
			'reference' => &$GLOBALS['TL_LANG']['MSC'],
			'eval'      => array(
				'includeBlankOption' => true,
				'importable'         => true,
				'exportable'         => true,
				'feEditable'         => true,
				'tl_class'           => 'clr'
			),
			'field'     => array(
			),
		),
		'permitPersonalTracing' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['permitPersonalTracing'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array(
				'importable' => true,
				'exportable' => true,
				'feEditable' => true,
				'tl_class'   => 'clr m12'
			),
			'field'     => array(
			),
		),
		'addedOn'               => array
		(
			'label'   => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedOn'],
			'filter'  => true,
			'sorting' => true,
			'flag'    => 8,
			'eval'    => array(
				'importable' => true,
				'exportable' => true,
				'doNotShow'  => true,
				'doNotCopy'  => true
			),
			'field'   => array(
				'type' => 'datetime',
				'nullable' => true,
			),
		),
		'addedBy'               => array
		(
			'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedBy'],
			'default'    => $this->User->id,
			'filter'     => true,
			'sorting'    => true,
			'flag'       => 1,
			'foreignKey' => 'tl_user.name',
			'eval'       => array(
				'importable' => true,
				'exportable' => true,
				'doNotShow'  => true,
				'doNotCopy'  => true
			),
			'field'      => array(
				'nullable' => true,
			),
		),
	)
);
