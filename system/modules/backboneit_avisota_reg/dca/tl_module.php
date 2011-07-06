<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['backboneit_avisota_reg'] = ';{backboneit_avisota_reg_legend},backboneit_avisota_reg_lists';

$GLOBALS['TL_DCA']['tl_module']['fields']['backboneit_avisota_reg_lists'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_module']['backboneit_avisota_reg_lists'],
	'inputType'	=> 'checkbox',
	'options_callback'	=> array('AvisotaRegDCA', 'getLists'),
	'eval'		=> array(
		'multiple'		=> true,
		'tl_class'		=> 'clr'
	)
);
