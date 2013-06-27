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
 * Table tl_avisota_transport
 */
$GLOBALS['TL_DCA']['tl_avisota_transport'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Table',
		'enableVersioning'  => true,
		'onload_callback'   => array(
			array('Avisota\Contao\DataContainer\Transport', 'onload_callback')
		),
		'onsubmit_callback' => array(
			array('Avisota\Contao\DataContainer\Transport', 'onsubmit_callback'),
			array('Avisota\Contao\Backend', 'regenerateDynamics')
		)
	),
	// List
	'list'         => array
	(
		'sorting'           => array
		(
			'mode'   => 1,
			'flag'   => 11,
			'fields' => array('type', 'title')
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
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_transport']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_avisota_transport']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_transport']['show'],
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
			'transport' => array('type')
		),
		'swift'            => array(
			'transport' => array('title', 'alias', 'type'),
			'sender'    => array('sender', 'senderName'),
			'reply'     => array('replyTo', 'replyToName'),
			'swift'     => array('swiftUseSmtp')
		),
		'swiftswiftSmtpOn' => array(
			'transport' => array('title', 'alias', 'type'),
			'sender'    => array('sender', 'senderName'),
			'swift'     => array(
				'swiftUseSmtp',
				'swiftSmtpHost',
				'swiftSmtpUser',
				'swiftSmtpPass',
				'swiftSmtpEnc',
				'swiftSmtpPort'
			)
		),
		'service'            => array(
			'transport' => array('title', 'alias', 'type'),
			'service'   => array('serviceName')
		),
	),
	// Fields
	'fields'       => array
	(
		'type'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['type'],
			'inputType' => 'select',
			'options'   => array_keys($GLOBALS['TL_AVISOTA_TRANSPORT']),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_transport'],
			'eval'      => array(
				'mandatory'          => true,
				'submitOnChange'     => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			)
		),
		'title'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['title'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 255,
				'tl_class'  => 'w50'
			)
		),
		'alias'                                     => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_avisota_transport']['alias'],
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
			'save_callback' => array
			(
				array('Avisota\Contao\DataContainer\Transport', 'generateAlias')
			)
		),
		'sender'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['sender'],
			'exclude'   => true,
			'search'    => true,
			'filter'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'rgxp'           => 'email',
				'maxlength'      => 128,
				'decodeEntities' => true,
				'tl_class'       => 'w50'
			)
		),
		'senderName'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['senderName'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 11,
			'inputType' => 'text',
			'eval'      => array(
				'decodeEntities' => true,
				'maxlength'      => 128,
				'tl_class'       => 'w50'
			)
		),
		'replyTo'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['replyTo'],
			'exclude'   => true,
			'search'    => true,
			'filter'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'rgxp'           => 'email',
				'maxlength'      => 128,
				'decodeEntities' => true,
				'tl_class'       => 'w50'
			)
		),
		'replyToName'   => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['replyToName'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 11,
			'inputType' => 'text',
			'eval'      => array(
				'decodeEntities' => true,
				'maxlength'      => 128,
				'tl_class'       => 'w50'
			)
		),
		// swift mailer
		'swiftUseSmtp'  => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftUseSmtp'],
			'default'   => 'swiftSmtpSystemSettings',
			'exclude'   => true,
			'inputType' => 'select',
			'options'   => array('swiftSmtpSystemSettings', 'swiftSmtpOn', 'swiftSmtpOff'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_transport'],
			'eval'      => array(
				'submitOnChange' => true,
				'tl_class'       => 'w50'
			)
		),
		'swiftSmtpHost' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpHost'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 64,
				'nospace'   => true,
				'doNotShow' => true,
				'tl_class'  => 'w50'
			)
		),
		'swiftSmtpUser' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpUser'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array(
				'decodeEntities' => true,
				'maxlength'      => 128,
				'doNotShow'      => true,
				'tl_class'       => 'w50'
			)
		),
		'swiftSmtpPass' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpPass'],
			'exclude'   => true,
			'inputType' => 'textStore',
			'eval'      => array(
				'decodeEntities' => true,
				'maxlength'      => 32,
				'doNotShow'      => true,
				'tl_class'       => 'w50'
			)
		),
		'swiftSmtpEnc'  => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpEnc'],
			'exclude'   => true,
			'inputType' => 'select',
			'options'   => array(
				'ssl' => 'SSL',
				'tls' => 'TLS'
			),
			'eval'      => array(
				'includeBlankOption' => true,
				'doNotShow'          => true,
				'tl_class'           => 'w50'
			)
		),
		'swiftSmtpPort' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpPort'],
			'default'   => 25,
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'rgxp'      => 'digit',
				'nospace'   => true,
				'doNotShow' => true,
				'tl_class'  => 'w50'
			)
		),
		'serviceName'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_transport']['serviceName'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'maxlength' => 255,
				'tl_class'  => 'w50'
			)
		),
	)
);
