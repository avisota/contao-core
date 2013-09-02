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
 * Table orm_avisota_message_category
 * Entity Avisota\Contao:MessageCategory
 */
$GLOBALS['TL_DCA']['orm_avisota_message_category'] = array
(
	// Entity
	'entity'                => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
	),
	// Config
	'config'                => array
	(
		'dataContainer'     => 'General',
		'ctable'            => array('orm_avisota_message'),
		'switchToEdit'      => true,
		'enableVersioning'  => true,
		'onload_callback'   => array
		(
			array('Avisota\Contao\DataContainer\MessageCategory', 'checkPermission')
		),
		'onsubmit_callback' => array
		(
			array('Avisota\Contao\Backend', 'regenerateDynamics')
		)
	),
	// DataContainer
	'dca_config'            => array
	(
		'callback'       => 'DcGeneral\Callbacks\ContaoStyleCallbacks',
		'data_provider'  => array
		(
			'default' => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityData',
				'source' => 'orm_avisota_message_category'
			)
		),
		'controller'     => 'DcGeneral\Controller\DefaultController',
		'view'           => 'DcGeneral\View\DefaultView',
		'childCondition' => array(
			array(
				'from'   => 'orm_avisota_message_category',
				'to'     => 'orm_avisota_message',
				'setOn'  => array
				(
					array(
						'to_field'   => 'category',
						'from_field' => 'id',
					),
				),
				'filter' => array
				(
					array
					(
						'local'     => 'category',
						'remote'    => 'id',
						'operation' => '=',
					)
				)
			)
		)
	),
	// List
	'list'                  => array
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
			'fields' => array('title'),
			'format' => '%s'
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
			'edit'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['edit'],
				'href'  => 'table=orm_avisota_message',
				'icon'  => 'edit.gif',
			),
			'editheader' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['editheader'],
				'href'            => 'act=edit',
				'icon'            => 'header.gif',
				'button_callback' => array('Avisota\Contao\DataContainer\MessageCategory', 'editHeader'),
			),
			'copy'       => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['copy'],
				'href'            => 'act=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\MessageCategory', 'copyCategory')
			),
			'delete'     => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\MessageCategory', 'deleteCategory')
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		),
	),
	// Palettes
	'palettes'              => array(
		'__selector__' => array('boilerplates')
	),
	'metapalettes'          => array
	(
		'default'      => array
		(
			'category'   => array('title', 'alias'),
			'recipients' => array('recipientsMode'),
			'layout'     => array('layoutMode'),
			'queue'      => array('queueMode'),
			'expert'     => array(':hide', 'boilerplates', 'showInMenu'),
		),
		'boilerplates' => array
		(
			'category' => array('title', 'alias'),
			'expert'   => array(':hide', 'boilerplates'),
		),
	),
	// Subpalettes
	'metasubpalettes'       => array
	(
		'showInMenu'        => array('useCustomMenuIcon'),
		'useCustomMenuIcon' => array('menuIcon'),
	),
	// Subselectpalettes
	'metasubselectpalettes' => array
	(
		'recipientsMode' => array
		(
			'byCategory'          => array('recipients'),
			'byMessageOrCategory' => array('recipients'),
		),
		'layoutMode'     => array
		(
			'byCategory'          => array('layout'),
			'byMessageOrCategory' => array('layout')
		),
		'queueMode'      => array
		(
			'byCategory'          => array('queue'),
			'byMessageOrCategory' => array('queue')
		),
	),
	// Fields
	'fields'                => array
	(
		'id'                => array(
			'field' => array(
				'id'      => true,
				'type'    => 'string',
				'length'  => '36',
				'options' => array('fixed' => true),
			)
		),
		'createdAt'         => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'create')
			)
		),
		'updatedAt'         => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'update')
			)
		),
		'messages'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['messages'],
			'eval'      => array(
				'doNotShow' => true,
			),
			'oneToMany' => array(
				'targetEntity' => 'Avisota\Contao\Entity\Message',
				'cascade'      => array('all'),
				'mappedBy'     => 'category',
				// 'orphanRemoval' => false,
				// 'isCascadeRemove' => false,
				'orderBy'      => array('sendOn' => 'ASC')
			),
		),
		'title'             => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['title'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 255,
				'tl_class'  => 'w50'
			)
		),
		'alias'             => array
		(
			'label'           => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['alias'],
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
		'recipientsMode'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['recipientsMode'],
			'default'   => 'byCategory',
			'inputType' => 'select',
			'options'   => array('byCategory', 'byMessageOrCategory', 'byMessage'),
			'reference' => &$GLOBALS['TL_LANG']['orm_avisota_message_category'],
			'eval'      => array(
				'mandatory'      => true,
				'submitOnChange' => true,
				'tl_class'       => 'clr w50'
			)
		),
		'recipients'        => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['recipients'],
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\OptionsBuilder', 'getRecipientSourceOptions'),
			'eval'             => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			),
			'manyToOne'        => array(
				'targetEntity' => 'Avisota\Contao\Entity\RecipientSource',
				'cascade'      => array('all'),
				'joinColumns'  => array(
					array(
						'name'                 => 'recipientSource',
						'referencedColumnName' => 'id',
					),
				),
			),
		),
		'layoutMode'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['layoutMode'],
			'default'   => 'byCategory',
			'inputType' => 'select',
			'options'   => array('byCategory', 'byMessageOrCategory', 'byMessage'),
			'reference' => &$GLOBALS['TL_LANG']['orm_avisota_message_category'],
			'eval'      => array(
				'mandatory'      => true,
				'submitOnChange' => true,
				'tl_class'       => 'w50'
			)
		),
		'layout'            => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['layout'],
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\OptionsBuilder', 'getLayoutOptions'),
			'eval'             => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			),
			'manyToOne'        => array(
				'targetEntity' => 'Avisota\Contao\Entity\Layout',
				'cascade'      => array('all'),
				'joinColumns'  => array(
					array(
						'name'                 => 'layout',
						'referencedColumnName' => 'id',
					),
				),
			),
		),
		'queueMode'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['queueMode'],
			'default'   => 'byCategory',
			'inputType' => 'select',
			'options'   => array('byCategory', 'byMessageOrCategory', 'byMessage'),
			'reference' => &$GLOBALS['TL_LANG']['orm_avisota_message_category'],
			'eval'      => array(
				'mandatory'      => true,
				'submitOnChange' => true,
				'tl_class'       => 'w50'
			)
		),
		'queue'             => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['queue'],
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\OptionsBuilder', 'getQueueOptions'),
			'eval'             => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			),
			'manyToOne'        => array(
				'targetEntity' => 'Avisota\Contao\Entity\Queue',
				'cascade'      => array('all'),
				'joinColumns'  => array(
					array(
						'name'                 => 'queue',
						'referencedColumnName' => 'id',
					),
				),
			),
		),
		'boilerplates'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['boilerplates'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array(
				'submitOnChange' => true,
				'tl_class'       => 'm12'
			)
		),
		'showInMenu'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['showInMenu'],
			'inputType' => 'checkbox',
			'eval'      => array(
				'submitOnChange' => true,
				'tl_class'       => 'm12 w50'
			)
		),
		'useCustomMenuIcon' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['useCustomMenuIcon'],
			'default'   => false,
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12 w50', 'submitOnChange' => true)
		),
		'menuIcon'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['menuIcon'],
			'inputType' => 'fileTree',
			'eval'      => array(
				'tl_class'   => 'clr',
				'files'      => true,
				'filesOnly'  => true,
				'fieldType'  => 'radio',
				'extensions' => 'png,gif,jpg,jpeg'
			),
			'field'     => array(),
		),
	)
);
