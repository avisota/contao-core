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
 * Table orm_avisota_message
 * Entity Avisota\Contao:Message
 */
$GLOBALS['TL_DCA']['orm_avisota_message'] = array
(
	// Entity
	'entity'          => array(
		'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
	),
	// Config
	'config'          => array
	(
		'dataContainer'     => 'General',
		'ptable'            => 'orm_avisota_message_category',
		'ctable'            => array('orm_avisota_message_content'),
		'switchToEdit'      => true,
		'enableVersioning'  => true,
		'palettes_callback' => array
		(
			array('Avisota\Contao\DataContainer\Message', 'updatePalette'),
		),
		'onload_callback'   => array
		(
			array('Avisota\Contao\DataContainer\Message', 'checkPermission'),
		)
	),
	// DataContainer
	'dca_config'      => array
	(
		'callback'       => 'DcGeneral\Callbacks\ContaoStyleCallbacks',
		'data_provider'  => array
		(
			'default' => array
			(
				'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityData',
				'source' => 'orm_avisota_message'
			),
			'parent'  => array
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
			),
			array(
				'from'   => 'orm_avisota_message',
				'to'     => 'orm_avisota_message_content',
				'setOn'  => array
				(
					array(
						'to_field'   => 'message',
						'from_field' => 'id',
					),
				),
				'filter' => array
				(
					array
					(
						'local'     => 'message',
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
			'fields'                => array('sendOn'),
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
			'newsletter' => array('subject', 'alias', 'language'),
			'meta'       => array('description', 'keywords'),
			'recipient'  => array(),
			'layout'     => array(),
			'queue'      => array(),
			'attachment' => array('addFile'),
		),
	),
	// Subpalettes
	'metasubpalettes' => array
	(
		'setRecipients' => array('recipients'),
		'setLayout'     => array('layout'),
		'setQueue'      => array('queue'),
		'addFile'       => array('files')
	),
	// Fields
	'fields'          => array
	(
		'id'            => array(
			'field' => array(
				'id'      => true,
				'type'    => 'string',
				'length'  => '36',
				'options' => array('fixed' => true),
			)
		),
		'createdAt'     => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'create')
			)
		),
		'updatedAt'     => array(
			'field' => array(
				'type'          => 'datetime',
				'timestampable' => array('on' => 'update')
			)
		),
		'category'      => array(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['category'],
			'eval'      => array(
				'doNotShow' => true,
			),
			'manyToOne' => array(
				'index'        => true,
				'targetEntity' => 'Avisota\Contao\Entity\MessageCategory',
				'cascade'      => array('persist', 'detach', 'merge', 'refresh'),
				'inversedBy'   => 'messages',
				'joinColumns'  => array(
					array(
						'name'                 => 'category',
						'referencedColumnName' => 'id',
					)
				)
			)
		),
		'contents'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['contents'],
			'eval'      => array(
				'doNotShow' => true,
			),
			'oneToMany' => array(
				'targetEntity' => 'Avisota\Contao\Entity\MessageContent',
				'cascade'      => array('all'),
				'mappedBy'     => 'message',
				// 'orphanRemoval' => false,
				// 'isCascadeRemove' => false,
				'orderBy'      => array('cell' => 'ASC', 'sorting' => 'ASC')
			),
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
		'alias'         => array
		(
			'label'           => &$GLOBALS['TL_LANG']['orm_avisota_message']['alias'],
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
		'language'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['language'],
			'exclude'   => true,
			'filter'    => true,
			'flag'      => 1,
			'inputType' => 'select',
			'options'   => $this->getCountries(),
			'eval'      => array(
				'mandatory' => true,
				'tl_class'  => 'w50',
			),
			'field'     => array(
				'type'    => 'string',
				'length'  => 5,
				'options' => array('fixed' => true),
			),
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
			'eval'      => array('tl_class' => 'clr w50', 'submitOnChange' => true)
		),
		'recipients'    => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_message']['recipients'],
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\OptionsBuilder', 'getRecipientSourceOptions'),
			'eval'             => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
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
		'setLayout'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['setLayout'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'clr m12 w50', 'submitOnChange' => true)
		),
		'layout'        => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_message']['layout'],
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\OptionsBuilder', 'getLayoutOptions'),
			'eval'             => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
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
		'setQueue'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['orm_avisota_message']['setQueue'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'clr m12 w50', 'submitOnChange' => true)
		),
		'queue'             => array
		(
			'label'            => &$GLOBALS['TL_LANG']['orm_avisota_message']['queue'],
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
			),
			'field'     => array(),
		),
		'sendOn'        => array
		(
			'label'   => &$GLOBALS['TL_LANG']['orm_avisota_recipient']['sendOn'],
			'filter'  => true,
			'sorting' => true,
			'flag'    => 7,
			'eval'    => array(
				'doNotCopy' => true,
				'doNotShow' => true
			),
			'field'   => array(
				'type'     => 'datetime',
				'nullable' => true,
			),
		)
	)
);
