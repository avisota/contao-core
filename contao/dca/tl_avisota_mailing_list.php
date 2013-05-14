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
 * Table tl_avisota_mailing_list
 */
$GLOBALS['TL_DCA']['tl_avisota_mailing_list'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'    => 'Table',
		'enableVersioning' => true,
		'onload_callback'  => array
		(
			array('Avisota\DataContainer\MailingList', 'checkPermission')
		),
		'onsubmit_callback'  => array
		(
			array('Avisota\Backend', 'regenerateDynamics')
		)
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
			'label_callback' => array('Avisota\DataContainer\MailingList', 'getLabel')
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
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['edit'],
				'href'            => 'act=edit',
				'icon'            => 'edit.gif',
				'button_callback' => array('Avisota\DataContainer\MailingList', 'editList')
			),
			'copy'   => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['copy'],
				'href'            => 'act=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\DataContainer\MailingList', 'copyCategory')
			),
			'delete' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\DataContainer\MailingList', 'deleteCategory')
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['show'],
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
		'title'                                     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['title'],
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
			'label'         => &$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['alias'],
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
				array('Avisota\DataContainer\MailingList', 'generateAlias')
			)
		),
		'viewOnlinePage'                            => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['viewOnlinePage'],
			'exclude'   => true,
			'inputType' => 'pageTree',
			'eval'      => array(
				'fieldType' => 'radio',
				'mandatory' => true
			)
		),
		'integratedRecipientManageSubscriptionPage' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['integratedRecipientManageSubscriptionPage'],
			'exclude'   => true,
			'inputType' => 'pageTree',
			'eval'      => array(
				'fieldType' => 'radio',
				'mandatory' => true
			)
		)
	)
);
