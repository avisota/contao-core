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
 * Table orm_avisota_recipient_subscription
 * Entity Avisota\Contao:RecipientSubscription
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_subscription'] = array
(
	// Entity
	'entity'       => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_NONE
	),
	// Config
	'config'       => array
	(
		'dataContainer' => 'General',
		'ptable'        => 'orm_avisota_recipient',
	),
	// DataContainer
	'dca_config'   => array
	(
		'callback'       => 'DcGeneral\Callbacks\ContaoStyleCallbacks',
		'data_provider'  => array
		(
			'default' => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityData',
				'source' => 'orm_avisota_recipient_subscription'
			),
			'parent'  => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityData',
				'source' => 'orm_avisota_recipient'
			),
		),
		'controller'     => 'DcGeneral\Controller\DefaultController',
		'view'           => 'DcGeneral\View\DefaultView',
		'childCondition' => array(
			array(
				'from'   => 'orm_avisota_recipient',
				'to'     => 'orm_avisota_recipient_subscription',
				'setOn'  => array
				(
					array(
						'to_field'   => 'recipient',
						'from_field' => 'id',
					),
				),
				'filter' => array
				(
					array
					(
						'local'     => 'recipient',
						'remote'    => 'id',
						'operation' => '=',
					)
				)
			),
		)
	),
	// List
	'list'         => array
	(
		'sorting'           => array
		(
			'mode'                  => 4,
			'fields'                => array('list'),
			'panelLayout'           => 'filter;search,limit',
			'headerFields'          => array('forename', 'surname', 'email'),
			'child_record_callback' => array(
				'Avisota\Contao\DataContainer\RecipientSubscription',
				'addRecipientSubscriptionRow'
			),
			'child_record_class'    => 'no_padding',
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
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif',
			),
			'copy'   => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['copy'],
				'href'       => 'act=paste&amp;mode=copy',
				'icon'       => 'copy.gif',
				'attributes' => 'onclick="Backend.getScrollOffset();"',
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'toggle' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['toggle'],
				'icon'            => 'visible.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback' => array('Avisota\Contao\DataContainer\RecipientSubscription', 'toggleIcon')
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			),
		),
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'subscription' => array('list'),
			'status'       => array('confirmed'),
		),
	),
	// Fields
	'fields'       => array
	(
		'createdAt'  => array(
			'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['createdAt'],
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'create')
			)
		),
		'updatedAt'  => array(
			'label' => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['updatedAt'],
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'update')
			)
		),
		'recipient'        => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['recipient'],
			'oneToOne'         => array(
				'id'           => true,
				'targetEntity' => 'Avisota\Contao\Entity\Recipient',
				'cascade'      => array('persist', 'detach', 'merge', 'refresh'),
				'joinColumns'  => array(
					array(
						'name'                 => 'recipient',
						'referencedColumnName' => 'id',
					)
				),
			),
		),
		'list'             => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['list'],
			'inputType'        => 'select',
			'options_callback' => array(
				'Avisota\Contao\DataContainer\OptionsBuilder',
				'getSubscriptionListOptions'
			),
			'reference'        => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['subscription_list'],
			'eval'             => array(
				'includeBlankOption' => true,
				'mandatory'          => true,
			),
			'field'            => array(
				'id'   => true,
				'type' => 'string',
			)
		),
		'confirmationSent' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['confirmationSent'],
			'field' => array(
				'type'     => 'timestamp',
				'nullable' => true,
			)
		),
		'reminderSent'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['reminderSent'],
			'field' => array(
				'type'     => 'timestamp',
				'nullable' => true,
			)
		),
		'reminderCount'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['reminderCount'],
			'field' => array(
				'type'     => 'timestamp',
				'nullable' => true,
			)
		),
		'confirmed'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['confirmed'],
			'inputType' => 'checkbox',
			'field'     => array(
				'type' => 'boolean',
			)
		),
		'confirmedAt'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['confirmedAt'],
			'field' => array(
				'type'     => 'timestamp',
				'nullable' => true,
			)
		),
		'token'            => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_recipient_subscription']['token'],
			'field' => array(
				'type'     => 'string',
				'length'   => 16,
				'options'  => array('fixed' => true),
				'nullable' => true,
			)
		),
	)
);
