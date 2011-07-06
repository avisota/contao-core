<?php

$GLOBALS['TL_DCA']['tl_member']['fields']['backboneit_avisota_reg_lists'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_member']['backboneit_avisota_reg_lists'],
	'inputType'	=> 'checkbox',
	'options_callback'	=> array('AvisotaRegDCA', 'getSelectableLists'),
	'eval'		=> array(
		'multiple'		=> true,
		'feEditable'	=> true,
		'feGroup'		=> 'newsletter'
	)
);
