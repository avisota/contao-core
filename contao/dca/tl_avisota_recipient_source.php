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
 * Table tl_avisota_recipient_source
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_source'] = array
(

	// Config
	'config'                => array
	(
		'dataContainer'     => 'Table',
		'enableVersioning'  => true,
		'onload_callback'   => array(
			array('Avisota\DataContainer\RecipientSource', 'onload_callback')
		),
		'onsubmit_callback' => array(
			array('Avisota\DataContainer\RecipientSource', 'onsubmit_callback'),
			array('Avisota\Backend', 'regenerateDynamics')
		)
	),
	// List
	'list'                  => array
	(
		'sorting'           => array
		(
			'mode'            => 1,
			'flag'            => 11,
			'fields'          => array('sorting'),
			'disableGrouping' => true,
			'root'            => 0
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
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['toggle'],
				'icon'            => 'visible.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback' => array('Avisota\DataContainer\RecipientSource', 'toggleIcon')
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			),
			'move'   => array
			(
				'button_callback' => array('Avisota\DataContainer\RecipientSource', 'move_button_callback')
			)
		),
	),
	// Palettes
	'palettes'              => array(
		'__selector__' => array('type')
	),
	// Meta Palettes
	'metapalettes'          => array
	(
		'default'    => array(
			'source' => array('type')
		),
		'integrated' => array(
			'source'     => array('title', 'alias', 'type'),
			'integrated' => array('integratedBy', 'integratedDetails'),
			'filter'     => array(':hide', 'filter'),
			'expert'     => array('disable')
		),
		'member'     => array(
			'source' => array('title', 'alias', 'type'),
			'member' => array('memberBy'),
			'filter' => array(':hide', 'filter'),
			'expert' => array('disable')
		),
		'csv_file'   => array(
			'source'  => array('title', 'alias', 'type'),
			'csvFile' => array('csvFileSrc', 'csvColumnAssignment'),
			'expert'  => array('disable')
		)
	),
	'metasubselectpalettes' => array
	(
		'integratedBy' => array
		(
			'integratedByMailingLists'    => array('integratedAllowSingleListSelection', 'integratedMailingLists'),
			'integratedByAllMailingLists' => array('integratedAllowSingleListSelection'),
			'integratedByRecipients'      => array('integratedAllowSingleSelection', 'integratedMailingLists'),
			'integratedByAllRecipients'   => array('integratedAllowSingleSelection')
		),
		'memberBy'     => array
		(
			'memberByMailingLists'       => array('memberAllowSingleMailingListSelection', 'memberMailingLists'),
			'memberByAllMailingLists'    => array('memberAllowSingleMailingListSelection'),
			'memberByGroups'             => array('memberAllowSingleGroupSelection', 'memberGroups'),
			'memberByAllGroups'          => array('memberAllowSingleGroupSelection'),
			'memberByMailingListMembers' => array('memberAllowSingleSelection', 'memberMailingLists'),
			'memberByGroupMembers'       => array('memberAllowSingleSelection', 'memberGroups'),
			'memberByAllMembers'         => array('memberAllowSingleSelection')
		)
	),
	// Fields
	'fields'                => array
	(
		'type'                                  => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['type'],
			'inputType' => 'select',
			'options'   => array_keys($GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE']),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source'],
			'eval'      => array(
				'mandatory'          => true,
				'submitOnChange'     => true,
				'includeBlankOption' => true,
				'tl_class'           => 'w50'
			)
		),
		'title'                                 => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['title'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'unique'    => true,
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
				array('Avisota\DataContainer\RecipientSource', 'generateAlias')
			)
		),
		// integrated source
		'integratedBy'                          => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedBy'],
			'inputType' => 'select',
			'options'   => array(
				'integratedByMailingLists',
				'integratedByAllMailingLists',
				'integratedByRecipients',
				'integratedByAllRecipients'
			),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source'],
			'eval'      => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'submitOnChange'     => true,
				'tl_class'           => 'w50'
			)
		),
		'integratedMailingLists'                => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedMailingLists'],
			'inputType'  => 'checkbox',
			'foreignKey' => 'tl_avisota_mailing_list.title',
			'eval'       => array(
				'mandatory' => true,
				'multiple'  => true,
				'tl_class'  => 'clr'
			)
		),
		'integratedAllowSingleListSelection'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedAllowSingleListSelection'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12 w50')
		),
		'integratedAllowSingleSelection'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedAllowSingleSelection'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12 w50')
		),
		'integratedDetails'                     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedDetails'],
			'default'   => 'integrated_details',
			'inputType' => 'select',
			'options'   => array('integrated_details', 'member_details', 'integrated_member_details'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source'],
			'eval'      => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
			)
		),
		'integratedFilterByColumns'             => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumns'],
			'inputType' => 'multiColumnWizard',
			'eval'      => array(
				'columnFields' => array(
					'field'      => array(
						'label'            => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsField'],
						'inputType'        => 'select',
						'options_callback' => array('Avisota\DataContainer\RecipientSource', 'getRecipientFilterColumns'),
						'eval'             => array('style' => 'width:200px')
					),
					'comparator' => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsComparator'],
						'inputType' => 'select',
						'options'   => array(
							'='        => '=',
							'!='       => '!=',
							'<'        => '<',
							'<='       => '<=',
							'>'        => '>',
							'>='       => '>=',
							'LIKE'     => 'LIKE',
							'NOT LIKE' => 'NOT LIKE',
							'REGEXP'   => 'REGEXP'
						),
						'eval'      => array('style' => 'width:60px')
					),
					'value'      => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsValue'],
						'inputType' => 'text',
						'eval'      => array(
							'allowHtml'    => true,
							'preserveTags' => true,
							'style'        => 'width:300px'
						)
					),
					'noescape'   => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsNoEscape'],
						'inputType' => 'checkbox',
						'eval'      => array()
					)
				)
			)
		),
		// members
		'memberBy'                              => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberBy'],
			'inputType' => 'select',
			'options'   => array(
				'memberByMailingLists',
				'memberByAllMailingLists',
				'memberByGroups',
				'memberByAllGroups',
				'memberByMailingListMembers',
				'memberByGroupMembers',
				'memberByAllMembers'
			),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source'],
			'eval'      => array(
				'mandatory'          => true,
				'includeBlankOption' => true,
				'submitOnChange'     => true,
				'tl_class'           => 'w50'
			)
		),
		'memberAllowSingleMailingListSelection' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberAllowSingleMailingListSelection'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12 w50')
		),
		'memberMailingLists'                    => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberMailingLists'],
			'inputType'  => 'checkbox',
			'foreignKey' => 'tl_avisota_mailing_list.title',
			'eval'       => array(
				'mandatory' => true,
				'multiple'  => true,
				'tl_class'  => 'clr'
			)
		),
		'memberAllowSingleGroupSelection'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberAllowSingleGroupSelection'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12 w50')
		),
		'memberGroups'                          => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberGroups'],
			'inputType'  => 'checkbox',
			'foreignKey' => 'tl_member_group.name',
			'eval'       => array(
				'mandatory' => true,
				'multiple'  => true,
				'tl_class'  => 'clr'
			)
		),
		'memberAllowSingleSelection'            => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberAllowSingleSelection'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12 w50')
		),
		'memberFilterByColumns'                 => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumns'],
			'inputType' => 'multiColumnWizard',
			'eval'      => array(
				'columnFields' => array(
					'field'      => array(
						'label'            => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsField'],
						'inputType'        => 'select',
						'options_callback' => array('Avisota\DataContainer\RecipientSource', 'getMemberFilterColumns'),
						'eval'             => array('style' => 'width:200px')
					),
					'comparator' => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsComparator'],
						'inputType' => 'select',
						'options'   => array(
							'='        => '=',
							'!='       => '!=',
							'<'        => '<',
							'<='       => '<=',
							'>'        => '>',
							'>='       => '>=',
							'LIKE'     => 'LIKE',
							'NOT LIKE' => 'NOT LIKE',
							'REGEXP'   => 'REGEXP'
						),
						'eval'      => array('style' => 'width:60px')
					),
					'value'      => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsValue'],
						'inputType' => 'text',
						'eval'      => array(
							'allowHtml'    => true,
							'preserveTags' => true,
							'style'        => 'width:300px'
						)
					),
					'noescape'   => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsNoEscape'],
						'inputType' => 'checkbox',
						'eval'      => array()
					)
				)
			)
		),
		// csv source
		'csvFileSrc'                            => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvFileSrc'],
			'inputType' => 'fileTree',
			'eval'      => array(
				'mandatory'  => true,
				'files'      => true,
				'filesOnly'  => true,
				'extensions' => 'csv',
				'fieldType'  => 'radio'
			)
		),
		'csvColumnAssignment'                   => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvColumnAssignment'],
			'inputType' => 'multiColumnWizard',
			'eval'      => array(
				'columnFields' => array(
					'column' => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvColumnAssignmentColumn'],
						'inputType' => 'select',
						'options'   => range(1, 30),
						'eval'      => array()
					),
					'field'  => array(
						'label'            => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvColumnAssignmentField'],
						'inputType'        => 'select',
						'options_callback' => array('Avisota\DataContainer\RecipientSource', 'getRecipientColumns'),
						'eval'             => array()
					)
				)
			)
		),
		// filter settings
		'filter'                                => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['filter'],
			'inputType' => 'checkbox',
			'eval'      => array(
				'submitOnChange' => true,
				'tl_class'       => 'm12'
			)
		),
		// expert settings
		'disable'                               => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['disable'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12')
		)
	)
);
