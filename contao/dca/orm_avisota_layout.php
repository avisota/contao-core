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
 * Table orm_avisota_layout
 * Entity Avisota\Contao:Layout
 */
$GLOBALS['TL_DCA']['orm_avisota_layout'] = array
(
	// Entity
	'entity'          => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
	),
	// Config
	'config'          => array
	(
		'dataContainer'     => 'General',
		'ptable'            => 'orm_avisota_theme',
		'enableVersioning'  => true,
		'onload_callback'   => array
		(
			array('Avisota\Contao\DataContainer\Theme', 'checkPermission')
		),
		'onsubmit_callback' => array
		(
			array('Avisota\Contao\Backend', 'regenerateDynamics')
		)
	),
	// DataContainer
	'dca_config'      => array
	(
		'callback'       => 'GeneralCallbackDefault',
		'data_provider'  => array
		(
			'default' => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityData',
				'source' => 'orm_avisota_layout'
			),
			'parent'  => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityData',
				'source' => 'orm_avisota_theme'
			)
		),
		'controller'     => 'GeneralControllerDefault',
		'view'           => 'GeneralViewDefault',
		'childCondition' => array(
			array(
				'from'   => 'orm_avisota_theme',
				'to'     => 'self',
				'setOn'  => array
				(
					array(
						'to_field'   => 'theme',
						'from_field' => 'id',
					),
				),
				'filter' => array
				(
					array
					(
						'local'     => 'theme',
						'remote'    => 'id',
						'operation' => '=',
					)
				)
			)
		)
	),
	// List
	'list'            => array
	(
		'sorting'           => array
		(
			'mode'                  => 4,
			'flag'                  => 1,
			'fields'                => array('title'),
			'panelLayout'           => 'filter;search,limit',
			'headerFields'          => array('title'),
			'child_record_callback' => array('Avisota\Contao\DataContainer\Layout', 'addElement')
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
			'edit'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_layout']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'copy'   => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_layout']['copy'],
				'href'            => 'act=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\Theme', 'copyCategory')
			),
			'delete' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_layout']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\Theme', 'deleteCategory')
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_layout']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		),
	),
	// Palettes
	'metapalettes'    => array
	(
		'default' => array
		(
			'theme'     => array('title', 'alias', 'preview'),
			'template'  => array('stylesheets'),
			'structure' => array('baseTemplate', 'allowedCellContents', 'clearStyles'),
			'expert'    => array(':hide', 'templateDirectory')
		)
	),
	// Subpalettes
	'metasubpalettes' => array
	(),
	// Fields
	'fields'          => array
	(
		'id'                  => array(
			'field' => array(
				'id'      => true,
				'type'    => 'string',
				'length'  => '36',
				'options' => array('fixed' => true),
			)
		),
		'createdAt'           => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'create')
			)
		),
		'updatedAt'           => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'update')
			)
		),
		'theme'               => array(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message_content']['theme'],
			'eval'      => array(
				'doNotShow' => true,
			),
			'manyToOne' => array(
				'index'        => true,
				'targetEntity' => 'Avisota\Contao\Entity\Theme',
				'cascade'      => array('persist', 'detach', 'merge', 'refresh'),
				'inversedBy'   => 'layouts',
				'joinColumns'  => array(
					array(
						'name'                 => 'theme',
						'referencedColumnName' => 'id',
					)
				)
			)
		),
		'title'               => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_layout']['title'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 255,
				'tl_class'  => 'w50'
			)
		),
		'alias'               => array
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
		'preview'             => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_layout']['preview'],
			'exclude'   => true,
			'inputType' => 'fileTree',
			'eval'      => array(
				'files'      => true,
				'filesOnly'  => true,
				'fieldType'  => 'radio',
				'extensions' => 'jpg,jpeg,png,gif',
				'tl_class'   => 'clr',
			),
		),
		'stylesheets'         => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_layout']['stylesheets'],
			'inputType'        => 'checkboxWizard',
			'options_callback' => array('Avisota\Contao\DataContainer\Layout', 'getStylesheets'),
			'eval'             => array(
				'tl_class' => 'clr',
				'multiple' => true,
			),
			'field'            => array(),
		),
		'baseTemplate'              => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_layout']['baseTemplate'],
			'exclude'          => true,
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\OptionsBuilder', 'getMessagesBaseTemplateOptions'),
			'eval'             => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'submitOnChange'     => true,
			),
		),
		'allowedCellContents' => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_layout']['allowedCellContents'],
			'exclude'          => true,
			'inputType'        => 'checkbox',
			'options_callback' => array('Avisota\Contao\DataContainer\Layout', 'getCellContentOptions'),
			'eval'             => array(
				'multiple' => true,
			),
			'field'            => array(),
			'getter_callback'  => array(
				array('Avisota\Contao\DataContainer\Layout', 'getterCallbackAllowedCellContents')
			),
			'setter_callback'  => array(
				array('Avisota\Contao\DataContainer\Layout', 'setterCallbackAllowedCellContents')
			),
		),
		'clearStyles' => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_layout']['clearStyles'],
			'exclude'          => true,
			'inputType'        => 'checkbox',
		),
		/*
		'template_html'     => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_layout']['template_html'],
			'default'          => 'mail_html_default',
			'exclude'          => true,
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\Theme', 'getHtmlTemplates'),
			'eval'             => array('tl_class' => 'w50')
		),
		'template_plain'    => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_layout']['template_plain'],
			'default'          => 'mail_plain_default',
			'exclude'          => true,
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\Theme', 'getPlainTemplates'),
			'eval'             => array('tl_class' => 'w50')
		),
		*/
	)
);
