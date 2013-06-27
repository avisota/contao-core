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
 * Table tl_avisota_newsletter_category
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter_category'] = array
(

	// Config
	'config'                => array
	(
		'dataContainer'    => 'Table',
		'ctable'           => array('tl_avisota_newsletter'),
		'switchToEdit'     => true,
		'enableVersioning' => true,
		'onload_callback'  => array
		(
			array('Avisota\Contao\DataContainer\NewsletterCategory', 'checkPermission')
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
				'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['edit'],
				'href'       => 'table=tl_avisota_newsletter',
				'icon'       => 'edit.gif',
				'attributes' => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['editheader'],
				'href'            => 'act=edit',
				'icon'            => 'header.gif',
				'button_callback' => array('Avisota\Contao\DataContainer\NewsletterCategory', 'editHeader'),
				'attributes'      => 'class="edit-header"'
			),
			'copy'       => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['copy'],
				'href'            => 'act=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\NewsletterCategory', 'copyCategory')
			),
			'delete'     => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\NewsletterCategory', 'deleteCategory')
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		),
	),
	// Palettes
	'metapalettes'          => array
	(
		'default' => array
		(
			'category'   => array('title', 'alias'),
			'recipients' => array('recipientsMode'),
			'theme'      => array('themeMode'),
			'transport'  => array('transportMode'),
			'queue'      => array('queueMode'),
			'expert'     => array(':hide', 'showInMenu'),
		)
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
			'byCategory'             => array('recipients'),
			'byNewsletterOrCategory' => array('recipients'),
		),
		'themeMode'      => array
		(
			'byCategory'             => array('theme'),
			'byNewsletterOrCategory' => array('theme')
		),
		'transportMode'  => array
		(
			'byCategory'             => array('transport'),
			'byNewsletterOrCategory' => array('transport')
		),
		'queueMode'      => array
		(
			'byCategory'             => array('queue'),
			'byNewsletterOrCategory' => array('queue')
		)
	),
	// Fields
	'fields'                => array
	(
		'title'             => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['title'],
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
			'label'         => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['alias'],
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
				array('Avisota\Contao\DataContainer\NewsletterCategory', 'generateAlias')
			)
		),
		'recipientsMode'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipientsMode'],
			'default'   => 'byCategory',
			'inputType' => 'select',
			'options'   => array('byCategory', 'byNewsletterOrCategory', 'byNewsletter'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category'],
			'eval'      => array(
				'mandatory'      => true,
				'submitOnChange' => true,
				'tl_class'       => 'w50'
			)
		),
		'recipients'        => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipients'],
			'inputType'        => 'checkbox',
			'options_callback' => array('Avisota\Contao\DataContainer\NewsletterCategory', 'getRecipients'),
			'eval'             => array(
				'mandatory' => true,
				'multiple'  => true,
				'tl_class'  => 'clr'
			)
		),
		'themeMode'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['themeMode'],
			'default'   => 'byCategory',
			'inputType' => 'select',
			'options'   => array('byCategory', 'byNewsletterOrCategory', 'byNewsletter'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category'],
			'eval'      => array(
				'mandatory'      => true,
				'submitOnChange' => true,
				'tl_class'       => 'w50'
			)
		),
		'theme'             => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['theme'],
			'inputType'  => 'select',
			'foreignKey' => 'tl_avisota_newsletter_theme.title',
			'eval'       => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			)
		),
		'transportMode'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['transportMode'],
			'default'   => 'byCategory',
			'inputType' => 'select',
			'options'   => array('byCategory', 'byNewsletterOrCategory', 'byNewsletter'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category'],
			'eval'      => array(
				'mandatory'      => true,
				'submitOnChange' => true,
				'tl_class'       => 'w50'
			)
		),
		'transport'         => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['transport'],
			'inputType'  => 'select',
			'foreignKey' => 'tl_avisota_transport.title',
			'eval'       => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			)
		),
		'queueMode'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['queueMode'],
			'default'   => 'byCategory',
			'inputType' => 'select',
			'options'   => array('byCategory', 'byNewsletterOrCategory', 'byNewsletter'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category'],
			'eval'      => array(
				'mandatory'      => true,
				'submitOnChange' => true,
				'tl_class'       => 'w50'
			)
		),
		'queue'             => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['queue'],
			'inputType'  => 'select',
			'foreignKey' => 'tl_avisota_queue.title',
			'eval'       => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			)
		),
		'showInMenu'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['showInMenu'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12 w50')
		),
		'useCustomMenuIcon' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['useCustomMenuIcon'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12 w50', 'submitOnChange' => true)
		),
		'menuIcon'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['menuIcon'],
			'inputType' => 'fileTree',
			'eval'      => array(
				'tl_class'   => 'clr',
				'files'      => true,
				'filesOnly'  => true,
				'fieldType'  => 'radio',
				'extensions' => 'png,gif,jpg,jpeg'
			)
		)
	)
);
