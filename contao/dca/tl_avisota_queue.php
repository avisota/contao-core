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
 * Table tl_avisota_queue
 */
$GLOBALS['TL_DCA']['tl_avisota_queue'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Table',
		'enableVersioning'  => true,
		'onload_callback'   => array(
			array('Avisota\DataContainer\Queue', 'onload_callback')
		),
		'onsubmit_callback' => array(
			array('Avisota\DataContainer\Queue', 'onsubmit_callback'),
			array('Avisota\Backend', 'regenerateDynamics')
		)
	),
	// List
	'list'         => array
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
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_queue']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_avisota_queue']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_queue']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		),
	),
	// Palettes
	'palettes'     => array(
		'__selector__' => array('type', 'swiftUseSmtp')
	),
	// Meta Palettes
	'metapalettes' => array
	(
		'default'          => array(
			'queue' => array('title', 'alias'),
			'send'  => array('sendOn')
		),
	),
	// Fields
	'fields'       => array
	(
		'title'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_queue']['title'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 255,
				'tl_class'  => 'w50'
			)
		),
		'alias'                                     => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_avisota_queue']['alias'],
			'exclude'       => true,
			'search'        => true,
			'inputType'     => 'text',
			'eval'          => array(
				'rgxp'              => 'alnum',
				'unique'            => true,
				'spaceToUnderscore' => true,
				'maxlength'         => 128,
				'tl_class'          => 'w50'
			),
			'load_callback' => array
			(
				array('Avisota\DataContainer\Queue', 'rememberAlias')
			),
			'save_callback' => array
			(
				array('Avisota\DataContainer\Queue', 'generateAlias')
			)
		),
		'sendOn'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_queue']['sendOn'],
			'inputType' => 'select',
			'options'   => array('custom', 'time'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_queue'],
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 255,
				'tl_class'  => 'w50'
			)
		),
		'timechart'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_queue']['timechart'],
			'inputType' => 'select',
			'options'   => array('custom', 'time'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_queue'],
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 255,
				'tl_class'  => 'w50'
			)
		),
	)
);
