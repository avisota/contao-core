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
 * Table tl_avisota_newsletter_theme
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter_theme'] = array
(

	// Config
	'config'          => array
	(
		'dataContainer'    => 'Table',
		'enableVersioning' => true,
		'onload_callback'  => array
		(
			array('Avisota\Contao\DataContainer\NewsletterTheme', 'checkPermission')
		),
		'onsubmit_callback'  => array
		(
			array('Avisota\Contao\Backend', 'regenerateDynamics')
		)
	),
	// List
	'list'            => array
	(
		'sorting'           => array
		(
			'mode'        => 1,
			'flag'        => 1,
			'fields'      => array('title'),
			'panelLayout' => 'limit'
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
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'copy'   => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['copy'],
				'href'            => 'act=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\NewsletterTheme', 'copyCategory')
			),
			'delete' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('Avisota\Contao\DataContainer\NewsletterTheme', 'deleteCategory')
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['show'],
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
			'structure' => array('areas'),
			'template'  => array('stylesheets', 'template_html', 'template_plain'),
			'expert'    => array(':hide', 'templateDirectory')
		)
	),
	// Subpalettes
	'metasubpalettes' => array
	(),
	// Fields
	'fields'          => array
	(
		'title'             => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['title'],
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
				array('Avisota\Contao\DataContainer\NewsletterTheme', 'generateAlias')
			)
		),
		'preview'           => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['preview'],
			'exclude'   => true,
			'inputType' => 'fileTree',
			'eval'      => array(
				'files'      => true,
				'filesOnly'  => true,
				'fieldType'  => 'radio',
				'extensions' => 'jpg,jpeg,png,gif',
				'tl_class'   => 'clr'
			)
		),
		'areas'             => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['areas'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => false,
				'rgxp'      => 'extnd',
				'nospace'   => true
			)
		),
		'stylesheets'       => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['stylesheets'],
			'inputType'        => 'checkboxWizard',
			'options_callback' => array('Avisota\Contao\DataContainer\NewsletterTheme', 'getStylesheets'),
			'eval'             => array(
				'tl_class' => 'clr',
				'multiple' => true
			)
		),
		'template_html'     => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['template_html'],
			'default'          => 'mail_html_default',
			'exclude'          => true,
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\NewsletterTheme', 'getHtmlTemplates'),
			'eval'             => array('tl_class' => 'w50')
		),
		'template_plain'    => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['template_plain'],
			'default'          => 'mail_plain_default',
			'exclude'          => true,
			'inputType'        => 'select',
			'options_callback' => array('Avisota\Contao\DataContainer\NewsletterTheme', 'getPlainTemplates'),
			'eval'             => array('tl_class' => 'w50')
		),
		'templateDirectory' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['templateDirectory'],
			'exclude'   => true,
			'inputType' => 'fileTree',
			'eval'      => array(
				'tl_class'  => 'clr',
				'fieldType' => 'radio',
				'path'      => 'templates'
			)
		)
	)
);
