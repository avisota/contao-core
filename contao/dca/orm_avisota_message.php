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
 * Table orm_avisota_message
 * Entity Avisota\Contao:Message
 */
$GLOBALS['TL_DCA']['orm_avisota_message'] = array
(
	// Config
	'config'          => array
	(
		'dataContainer'     => 'Table',
		'ptable'            => 'orm_avisota_message_category',
		'ctable'            => array('orm_avisota_message_content'),
		'switchToEdit'      => true,
		'enableVersioning'  => true,
		'palettes_callback' => array
		(
			array('Avisota\Contao\DataContainer\Message', 'updatePalette')
		),
		'onload_callback'   => array
		(
			array('Avisota\Contao\DataContainer\Message', 'checkPermission')
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
			'header_callback'       => array('Avisota\Contao\DataContainer\Message', 'addHeader'),
			'child_record_callback' => array('Avisota\Contao\DataContainer\Message', 'addMessageRow'),
			'child_record_class'    => 'no_padding',
		),
		'label'             => array
		(
			'group_callback' => array('Avisota\Contao\DataContainer\Message', 'addGroup')
		),
		'global_operations' => array
		(
			'createFromDraft' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['orm_avisota_message']['create_from_draft'],
				'href'       => 'table=orm_avisota_message_create_from_draft&amp;act=edit',
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
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_message']['edit'],
				'href'            => 'table=orm_avisota_message_content',
				'icon'            => 'edit.gif',
				'button_callback' => array('Avisota\Contao\DataContainer\Message', 'editMessage')
			),
			'editheader' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_message']['editheader'],
				'href'            => 'act=edit',
				'icon'            => 'header.gif',
				'button_callback' => array('Avisota\Contao\DataContainer\Message', 'editHeader')
			),
			'copy'       => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_message']['copy'],
				'href'            => 'act=paste&amp;mode=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\Message', 'copyMessage')
			),
			'delete'     => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_message']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\Message', 'deleteMessage')
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['orm_avisota_message']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			),
			'send'       => array
			(
				'label'           => &$GLOBALS['TL_LANG']['orm_avisota_message']['send'],
				'href'            => 'key=send',
				'icon'            => 'system/modules/avisota/html/send.png',
				'button_callback' => array('Avisota\Contao\DataContainer\Message', 'sendMessage')
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
		'id' => array(
			'field' => array(
				'id' => true,
				'type' => 'integer'
			)
		),
		'pid' => array(
			'field' => array(
				'index' => true,
				'type' => 'integer'
			)
		),
		'tstamp' => array(
			'field' => array(
				'type' => 'timestamp'
			)
		),
		'subject'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['subject'],
			'exclude'   => true,
			'search'    => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory'      => true,
				'maxlength'      => 255,
				'tl_class'       => 'w50',
				'decodeEntities' => true
			),
		),
		'language'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['language'],
			'exclude'   => true,
			'filter'    => true,
			'flag'      => 1,
			'inputType' => 'select',
			'options'   => $this->getCountries(),
			'eval'      => array(
				'mandatory'      => true,
				'tl_class'       => 'w50',
			),
		),
		'alias'         => array
		(
			'label'         => &$GLOBALS['TL_LANG']['orm_avisota_message']['alias'],
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
				array('Avisota\Contao\DataContainer\Message', 'generateAlias')
			)
		),
		'description'   => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['description'],
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
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['keywords'],
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
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['setRecipients'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'clr m12', 'submitOnChange' => true)
		),
		'recipients'    => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_message']['recipients'],
			'inputType'        => 'checkbox',
			'options_callback' => array('Avisota\Contao\DataContainer\Message', 'getRecipients'),
			'eval'             => array(
				'mandatory' => true,
				'multiple'  => true,
				'tl_class'  => 'clr'
			)
		),
		'setTheme'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['setTheme'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'clr m12 w50', 'submitOnChange' => true)
		),
		'theme'         => array
		(
			'label'      => &$GLOBALS['TL_LANG']['orm_avisota_message']['theme'],
			'inputType'  => 'select',
			'foreignKey' => 'orm_avisota_theme.title',
			'eval'       => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
			)
		),
		'setTransport'  => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['setTransport'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'clr m12 w50', 'submitOnChange' => true)
		),
		'transport'     => array
		(
			'label'      => &$GLOBALS['TL_LANG']['orm_avisota_message']['transport'],
			'inputType'  => 'select',
			'foreignKey' => 'orm_avisota_transport.title',
			'eval'       => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
			)
		),
		'addFile'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['addFile'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => array('submitOnChange' => true)
		),
		'files'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['files'],
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
			'label'   => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['sendOn'],
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
