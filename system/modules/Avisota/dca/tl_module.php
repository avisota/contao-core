<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @filesource
 */


$GLOBALS['TL_DCA']['tl_module']['palettes']['avisota_subscription'] = '{title_legend},name,headline,type;{avisota_subscription_legend},avisota_show_lists,avisota_lists;{template_legend},avisota_template_subscribe_mail_plain,avisota_template_subscribe_mail_html,avisota_template_unsubscribe_mail_plain,avisota_template_unsubscribe_mail_html;{protected_legend:hide},protected;{expert_legend:hide},jumpTo,guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_show_lists'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_show_lists'],
	'inputType'               => 'checkbox',
	'eval'                    => array()
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_lists'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_lists'],
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_avisota', 'getLists'),
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_template_subscribe_mail_plain'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe_mail_plain'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_avisota', 'getTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_template_subscribe_mail_html'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe_mail_html'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_avisota', 'getTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_template_unsubscribe_mail_plain'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe_mail_plain'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_avisota', 'getTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_template_unsubscribe_mail_html'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe_mail_html'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_avisota', 'getTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

/**
 * Class tl_module_avisota
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class tl_module_avisota extends Backend
{
	public function getLists()
	{
		$objList = $this->Database->execute("
				SELECT
					*
				FROM
					`tl_avisota_recipient_list`
				ORDER BY
					`title`");
		$arrList = array();
		while ($objList->next())
		{
			$arrList[$objList->id] = $objList->title;
		}
		return $arrList;
	}
	
	public function getTemplates(DataContainer $dc)
	{
		// Return all templates
		switch ($dc->field)
		{
		case 'avisota_template_subscribe_mail_plain':
			$strTemplatePrefix = 'mail_subscribe_plain_';
			break;
		case 'avisota_template_subscribe_mail_html':
			$strTemplatePrefix = 'mail_subscribe_html_';
			break;
		case 'avisota_template_unsubscribe_mail_plain':
			$strTemplatePrefix = 'mail_unsubscribe_plain_';
			break;
		case 'avisota_template_unsubscribe_mail_html':
			$strTemplatePrefix = 'mail_unsubscribe_html_';
			break;
		default:
			return array();
		}
		
		return $this->getTemplateGroup($strTemplatePrefix, $dc->activeRecord->pid);
	}
}
?>