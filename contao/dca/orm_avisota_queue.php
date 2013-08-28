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
 * Table orm_avisota_queue
 * Entity Avisota\Contao:Queue
 */
$GLOBALS['TL_DCA']['orm_avisota_queue'] = array
(
	// Entity
	'entity'          => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
	),
	// Config
	'config'          => array
	(
		'dataContainer'     => 'General',
		'enableVersioning'  => true,
		'onload_callback'   => array(
			array('Avisota\Contao\DataContainer\Queue', 'onload_callback')
		),
		'onsubmit_callback' => array(
			array('Avisota\Contao\DataContainer\Queue', 'onsubmit_callback'),
			array('Avisota\Contao\Backend', 'regenerateDynamics')
		)
	),
	// DataContainer
	'dca_config'      => array
	(
		'callback'      => 'DcGeneral\Callbacks\ContaoStyleCallbacks',
		'data_provider' => array
		(
			'default' => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityData',
				'source' => 'orm_avisota_queue'
			)
		),
		'controller'    => 'DcGeneral\Controller\DefaultController',
		'view'          => 'DcGeneral\View\DefaultView'
	),
	// List
	'list'            => array
	(
		'sorting'           => array
		(
			'mode'   => 1,
			'flag'   => 11,
			'fields' => array('title')
		),
		'label'             => array
		(
			'fields' => array('title', 'type'),
			'format' => '%s <span style="color:#b3b3b3; padding-left:3px;">(%s)</span><br>'
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
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_queue']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_queue']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_queue']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		),
	),
	// Palettes
	'palettes'        => array(
		'__selector__' => array('type')
	),
	// Meta Palettes
	'metapalettes'    => array
	(
		'default'        => array(
			'queue' => array('type'),
		),
		'simpleDatabase' => array(
			'queue'     => array('type', 'title', 'alias'),
			'transport' => array(
				'transport',
				'maxSendTime',
				'maxSendCount',
				'cyclePause',
			),
			'config'    => array('simpleDatabaseQueueTable'),
			'send'      => array('allowManualSending')
		),
	),
	'metasubpalettes' => array(),
	// Fields
	'fields'          => array
	(
		'id'                       => array(
			'field' => array(
				'id'      => true,
				'type'    => 'string',
				'length'  => '36',
				'options' => array('fixed' => true),
			)
		),
		'createdAt'                => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'create')
			)
		),
		'updatedAt'                => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'update')
			)
		),
		'type'                     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['type'],
			'inputType' => 'select',
			'options'   => array_keys($GLOBALS['AVISOTA_QUEUE']),
			'reference' => &$GLOBALS['TL_LANG']['orm_avisota_queue'],
			'filter'    => true,
			'eval'      => array(
				'mandatory'          => true,
				'submitOnChange'     => true,
				'includeBlankOption' => true,
			)
		),
		'title'                    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['title'],
			'inputType' => 'text',
			'search'    => true,
			'flag'      => 1,
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 255,
				'tl_class'  => 'w50'
			)
		),
		'alias'                    => array
		(
			'label'           => &$GLOBALS['TL_LANG']['orm_avisota_queue']['alias'],
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
			'load_callback'   => array
			(
				array('Avisota\Contao\DataContainer\Queue', 'rememberAlias')
			),
			'setter_callback' => array
			(
				array('Contao\Doctrine\ORM\Helper', 'generateAlias')
			)
		),
		'transport'                => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_queue']['transport'],
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\OptionsBuilder', 'getTransportOptions'),
			'eval'             => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			),
			'manyToOne'        => array(
				'targetEntity' => 'Avisota\Contao\Entity\Transport',
				'cascade'      => array('all'),
				'joinColumns'  => array(
					array(
						'name'                 => 'transport',
						'referencedColumnName' => 'id',
					),
				),
			),
		),
		'maxSendTime'              => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['maxSendTime'],
			'default'   => ini_get('max_execution_time') > 0
				? floor(0.85 * ini_get('max_execution_time'))
				: 60,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'digit',
				'tl_class'  => 'w50'
			)
		),
		'maxSendCount'             => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['maxSendCount'],
			'default'   => 100,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'digit',
				'tl_class'  => 'w50'
			)
		),
		'cyclePause'                => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['cyclePause'],
			'default'   => 10,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'digit',
				'tl_class'  => 'w50'
			)
		),
		'simpleDatabaseQueueTable' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['simpleDatabaseQueueTable'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 255,
				'tl_class'  => 'm12 w50'
			)
		),
		'allowManualSending'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['allowManualSending'],
			'default'   => true,
			'inputType' => 'checkbox',
			'eval'      => array(
				'mandatory' => true,
				'tl_class'  => 'm12 w50'
			)
		),
	)
);
