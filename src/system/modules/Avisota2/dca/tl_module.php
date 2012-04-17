<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @author     Oliver Hoff <oliver@hofff.com>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = array('tl_module_avisota', 'onload_callback');

$GLOBALS['TL_DCA']['tl_module']['metapalettes']['avisota_subscribe'] = array
(
	'title'                => array('name', 'headline', 'type'),
	'avisota_subscription' => array('avisota_show_lists', 'avisota_lists', 'avisota_recipient_fields'),
	'template'             => array('tableless', 'avisota_template_subscribe'),
	'protected'            => array(':hide', 'protected'),
	'expert'               => array(':hide', 'avisota_form_target', 'jumpTo', 'guests', 'cssID', 'space')
);
$GLOBALS['TL_DCA']['tl_module']['metapalettes']['avisota_unsubscribe'] = array
(
	'title'                => array('name', 'headline', 'type'),
	'avisota_subscription' => array('avisota_show_lists', 'avisota_lists'),
	'template'             => array('tableless', 'avisota_template_subscription'),
	'protected'            => array(':hide', 'protected'),
	'expert'               => array(':hide', 'avisota_form_target', 'jumpTo', 'guests', 'cssID', 'space')
);
$GLOBALS['TL_DCA']['tl_module']['metapalettes']['avisota_subscription'] = array
(
	'title'                => array('name', 'headline', 'type'),
	'avisota_subscription' => array('avisota_show_lists', 'avisota_lists', 'avisota_recipient_fields'),
	'avisota_mail'         => array('avisota_subscription_sender_name', 'avisota_subscription_sender'),
	'template'             => array('tableless', 'avisota_template_unsubscribe'),
	'protected'            => array(':hide', 'protected'),
	'expert'               => array(':hide', 'jumpTo', 'guests', 'cssID', 'space')
);
$GLOBALS['TL_DCA']['tl_module']['metapalettes']['avisota_reader'] = array
(
	'title'          => array('name', 'headline', 'type'),
	'avisota_reader' => array('avisota_categories'),
	'template'       => array('avisota_reader_template'),
	'protected'      => array(':hide', 'protected'),
	'expert'         => array(':hide', 'guests', 'cssID', 'space')
);
$GLOBALS['TL_DCA']['tl_module']['metapalettes']['avisota_list'] = array
(
	'title'        => array('name', 'headline', 'type'),
	'avisota_list' => array('avisota_categories', 'perPage'),
	'redirect'     => array(':hide', 'avisota_view_page'),
	'template'     => array(':hide', 'avisota_list_template'),
	'protected'    => array(':hide', 'protected'),
	'expert'       => array(':hide', 'guests,cssID,space')
);

$GLOBALS['TL_DCA']['tl_module']['metasubpalettes']['avisota_send_notification'] = array
(
	'avisota_notification_time', 'avisota_template_notification_mail_plain', 'avisota_template_notification_mail_html'
);
$GLOBALS['TL_DCA']['tl_module']['metasubpalettes']['avisota_do_cleanup'] = array
(
	'avisota_cleanup_time'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_recipient_fields'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_recipient_fields'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options_callback'        => array('tl_module_avisota', 'getEditableRecipientProperties'),
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_subscription_sender_name'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_sender_name'],
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_subscription_sender'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_sender'],
	'inputType'               => 'text',
	'eval'                    => array('rgxp'=>'email', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_show_lists'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_show_lists'],
	'inputType'               => 'checkbox',
	'eval'                    => array()
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_lists'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_lists'],
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_avisota', 'getLists'),
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_template_subscription'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscription'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_avisota', 'getSubscriptionTemplates'),
	'eval'                    => array('addBlankOption' => true, 'tl_class' => 'clr')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_template_subscribe'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_avisota', 'getTemplates'),
	'eval'                    => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_template_unsubscribe'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_avisota', 'getTemplates'),
	'eval'                    => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_categories'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_categories'],
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_avisota', 'getCategories'),
	'eval'                    => array('mandatory'=>true, 'multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_reader_template'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_reader_template'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_avisota', 'getTemplates'),
	'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_list_template'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_list_template'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_avisota', 'getTemplates'),
	'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_view_page'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_view_page'],
	'inputType'               => 'pageTree',
	'eval'                    => array('fieldType'=>'radio')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_selectable_lists'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_selectable_lists'],
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_avisota', 'getLists'),
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_confirm_on_activate'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_confirm_on_activate'],
	'inputType'               => 'checkbox',
	'eval'                    => array()
);

$GLOBALS['TL_DCA']['tl_module']['fields']['avisota_form_target'] = array
(
	'exclude'                 => true,
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['avisota_form_target'],
	'inputType'               => 'pageTree',
	'eval'                    => array('fieldType'=>'radio')
);


/**
 * Class tl_module_avisota
 *
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class tl_module_avisota extends Backend
{
	public function onload_callback()
	{
		MetaPalettes::appendFields('tl_module', 'registration', 'config', array('avisota_selectable_lists'));
		MetaPalettes::appendFields('tl_module', 'personalData', 'config', array('avisota_selectable_lists'));
	}

	/**
	 * Get the category options
	 *
	 * @return array
	 */
	public function getCategories()
	{
		$objCategory = $this->Database
			->execute("SELECT * FROM tl_avisota_newsletter_category ORDER BY title");
		$arrList = array();
		while ($objCategory->next())
		{
			$arrList[$objCategory->id] = $objCategory->title;
		}
		return $arrList;
	}

	/**
	 * Get the lists options.
	 *
	 * @return array
	 */
	public function getLists()
	{
		$objList = $this->Database->execute("
				SELECT
					*
				FROM
					`tl_avisota_mailing_list`
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
			case 'avisota_reader_template':
				$strTemplatePrefix = 'avisota_reader_';
				break;

			case 'avisota_list_template':
				$strTemplatePrefix = 'avisota_list_';
				break;

			case 'avisota_template_subscribe':
				$strTemplatePrefix = 'avisota_subscribe_';
				break;

			case 'avisota_template_unsubscribe':
				$strTemplatePrefix = 'avisota_unsubscribe_';
				break;

			default:
				return array();
		}

		return $this->getTemplateGroup($strTemplatePrefix, $dc->activeRecord->pid);
	}

	public function getEditableRecipientProperties()
	{
		$return = array();

		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');

		foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $k=>$v)
		{
			if ($v['eval']['feEditable'])
			{
				$return[$k] = $GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['label'][0];
			}
		}

		return $return;
	}


	/**
	 * Return all subscription templates as array
	 * @param object
	 * @return array
	 */
	public function getSubscriptionTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return array_merge
		(
			array('mod_avisota_subscription'),
			$this->getTemplateGroup('subscription_', $intPid)
		);
	}
}
