<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'avisota_developer_mode';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{avisota_legend:hide},avisota_developer_mode';
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['avisota_developer_mode'] = 'avisota_developer_email';

$GLOBALS['TL_DCA']['tl_settings']['fields']['avisota_developer_mode'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['avisota_developer_mode'],
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['avisota_developer_email'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['avisota_developer_email'],
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'email')
);
?>