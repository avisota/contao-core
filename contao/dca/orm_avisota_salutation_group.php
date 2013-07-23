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
 * Table orm_avisota_salutation_group
 * Entity Avisota\Contao:SalutationGroup
 */
$GLOBALS['TL_DCA']['orm_avisota_salutation_group'] = array
(
	// Entity
	'entity'                => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
	),
	// Config
	'config'                => array
	(
		'dataContainer'    => 'General',
		'ctable'           => array('orm_avisota_salutation'),
		'switchToEdit'     => true,
		'enableVersioning' => true,
		'onload_callback'  => array
		(
			array('Avisota\Contao\DataContainer\SalutationGroup', 'checkPermission')
		)
	),
	// DataContainer
	'dca_config'            => array
	(
		'callback'       => 'GeneralCallbackDefault',
		'data_provider'  => array
		(
			'default' => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityData',
				'source' => 'orm_avisota_salutation_group'
			)
		),
		'controller'     => 'GeneralControllerDefault',
		'view'           => 'GeneralViewDefault',
		'childCondition' => array(
			array(
				'from'   => 'self',
				'to'     => 'orm_avisota_salutation',
				'setOn'  => array
				(
					array(
						'to_field'   => 'salutationGroup',
						'from_field' => 'id',
					),
				),
				'filter' => array
				(
					array
					(
						'local'     => 'salutationGroup',
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
			'generate' => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['generate'],
				'href'       => 'key=generate',
				'class'      => 'header_avisota_generate_salutation',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="g"'
			),
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
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['edit'],
				'href'  => 'table=orm_avisota_salutation',
				'icon'  => 'edit.gif',
			),
			'editheader' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['editheader'],
				'href'            => 'act=edit',
				'icon'            => 'header.gif',
				'button_callback' => array('Avisota\Contao\DataContainer\MessageCategory', 'editHeader'),
			),
			'copy'       => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['copy'],
				'href'            => 'act=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\MessageCategory', 'copyCategory')
			),
			'delete'     => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\MessageCategory', 'deleteCategory')
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		),
	),
	// Palettes
	'metapalettes'          => array
	(
		'default'      => array
		(
			'category'   => array('title', 'alias'),
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
		'salutations'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['salutations'],
			'eval'      => array(
				'doNotShow' => true,
			),
			'oneToMany' => array(
				'targetEntity' => 'Avisota\Contao\Entity\Salutation',
				'cascade'      => array('all'),
				'mappedBy'     => 'salutationGroup',
				// 'orphanRemoval' => false,
				// 'isCascadeRemove' => false,
				'orderBy'      => array('sorting' => 'ASC')
			),
		),
		'title'             => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['title'],
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
			'label'           => &$GLOBALS['TL_LANG']['orm_avisota_salutation_group']['alias'],
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
	)
);
