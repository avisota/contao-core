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

MetaPalettes::appendBefore('tl_member', 'default', 'login', array('avisota' => array(':hide', 'avisota_lists')));

$GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][]   = array('AvisotaDCA', 'filterByMailingLists');
$GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][]   = array('tl_member_avisota', 'onload_callback');
$GLOBALS['TL_DCA']['tl_member']['config']['onsubmit_callback'][] = array('tl_member_avisota', 'onsubmit_callback');

$GLOBALS['TL_DCA']['tl_member']['fields']['avisota_lists'] = array
(
	'label'            => &$GLOBALS['TL_LANG']['tl_member']['avisota_lists'],
	'inputType'        => 'checkbox',
	'options_callback' => array('AvisotaDCA', 'getSelectableLists'),
	'load_callback'    => array(array('AvisotaDCA', 'convertFromStringList')),
	'save_callback'    => array(array('AvisotaDCA', 'convertToStringList')),
	'eval'             => array
	(
		'multiple'   => true,
		'feEditable' => true,
		'feGroup'    => 'newsletter'
	)
);

$GLOBALS['TL_DCA']['tl_member']['fields']['avisota_subscribe'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_member']['avisota_subscribe'],
	'inputType' => 'checkbox',
	'eval'      => array
	(
		'feEditable' => true,
		'feGroup'    => 'newsletter'
	)
);
