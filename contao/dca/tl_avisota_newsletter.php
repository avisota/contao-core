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
 * Table tl_avisota_newsletter
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter'] = array
(
	// Config
	'config'          => array
	(
		'dataContainer'     => 'Table',
		'ptable'            => 'tl_avisota_newsletter_category',
		'ctable'            => array('tl_avisota_newsletter_content'),
		'switchToEdit'      => true,
		'enableVersioning'  => true,
		'palettes_callback' => array
		(
			array('Avisota\DataContainer\Newsletter', 'updatePalette')
		),
		'onload_callback'   => array
		(
			array('Avisota\DataContainer\Newsletter', 'checkPermission')
		)
	),
	// List
	'list'            => array
	(
		'sorting'           => array
		(
			'mode'                  => 4,
			'fields'                => array('sendOn=\'\' DESC', 'sendOn DESC'),
			'panelLayout'           => 'search,limit',
			'headerFields'          => array('title'),
			'header_callback'       => array('Avisota\DataContainer\Newsletter', 'addHeader'),
			'child_record_callback' => array('Avisota\DataContainer\Newsletter', 'addNewsletter'),
			'child_record_class'    => 'no_padding',
		),
		'label'             => array
		(
			'group_callback' => array('Avisota\DataContainer\Newsletter', 'addGroup')
		),
		'global_operations' => array
		(
			'createFromDraft' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['create_from_draft'],
				'href'       => 'table=tl_avisota_newsletter_create_from_draft&amp;act=edit',
				'class'      => 'header_new',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="d"'
			),
			'all'             => array
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
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['edit'],
				'href'            => 'table=tl_avisota_newsletter_content',
				'icon'            => 'edit.gif',
				'attributes'      => 'class="contextmenu"',
				'button_callback' => array('Avisota\DataContainer\Newsletter', 'editNewsletter')
			),
			'editheader' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['editheader'],
				'href'            => 'act=edit',
				'icon'            => 'header.gif',
				'attributes'      => 'class="edit-header"',
				'button_callback' => array('Avisota\DataContainer\Newsletter', 'editHeader')
			),
			'copy'       => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['copy'],
				'href'            => 'act=paste&amp;mode=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\DataContainer\Newsletter', 'copyNewsletter')
			),
			'delete'     => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\DataContainer\Newsletter', 'deleteNewsletter')
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			),
			'send'       => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['send'],
				'href'            => 'key=send',
				'icon'            => 'system/modules/avisota/html/send.png',
				'button_callback' => array('Avisota\DataContainer\Newsletter', 'sendNewsletter')
			)
		),
	),
	// Palettes
	'metapalettes'    => array
	(
		'default' => array
		(
			'newsletter' => array('subject', 'alias'),
			'meta'       => array('description', 'keywords'),
			'recipient'  => array(),
			'theme'      => array(),
			'transport'  => array(),
			'attachment' => array('addFile'),
			'template'   => array(':hide', 'template_html', 'template_plain')
		),
	),
	// Subpalettes
	'metasubpalettes' => array
	(
		'setRecipients' => array('recipients'),
		'setTheme'      => array('theme'),
		'setTransport'  => array('transport'),
		'addFile'       => array('files')
	),
	// Fields
	'fields'          => array
	(
		'subject'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['subject'],
			'exclude'   => true,
			'search'    => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory'      => true,
				'maxlength'      => 255,
				'tl_class'       => 'w50',
				'decodeEntities' => true
			)
		),
		'alias'         => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['alias'],
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
				array('Avisota\DataContainer\Newsletter', 'generateAlias')
			)
		),
		'description'   => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['description'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'textarea',
			'eval'      => array(
				'maxlength' => 255,
				'rows'      => 4,
			)
		),
		'keywords'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['keywords'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength' => 255,
				'tl_class'  => 'long'
			)
		),
		'setRecipients' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['setRecipients'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'clr m12', 'submitOnChange' => true)
		),
		'recipients'    => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipients'],
			'inputType'        => 'checkbox',
			'options_callback' => array('Avisota\DataContainer\Newsletter', 'getRecipients'),
			'eval'             => array(
				'mandatory' => true,
				'multiple'  => true,
				'tl_class'  => 'clr'
			)
		),
		'setTheme'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['setTheme'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'clr m12 w50', 'submitOnChange' => true)
		),
		'theme'         => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['theme'],
			'inputType'  => 'select',
			'foreignKey' => 'tl_avisota_newsletter_theme.title',
			'eval'       => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
			)
		),
		'setTransport'  => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['setTransport'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'clr m12 w50', 'submitOnChange' => true)
		),
		'transport'     => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['transport'],
			'inputType'  => 'select',
			'foreignKey' => 'tl_avisota_transport.title',
			'eval'       => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
			)
		),
		'addFile'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['addFile'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array('submitOnChange' => true)
		),
		'files'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['files'],
			'exclude'   => true,
			'inputType' => 'fileTree',
			'eval'      => array(
				'fieldType' => 'checkbox',
				'files'     => true,
				'filesOnly' => true,
				'mandatory' => true
			)
		),
		'sendOn'        => array
		(
			'label'   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['sendOn'],
			'filter'  => true,
			'sorting' => true,
			'flag'    => 7,
			'eval'    => array(
				'rgxp'      => 'datim',
				'doNotCopy' => true,
				'doNotShow' => true
			)
		)
	)
);
