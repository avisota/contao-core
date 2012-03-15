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
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * System configuration
 */

$GLOBALS['TL_DCA']['tl_avisota_settings'] = array
(

	// Config
	'config'                => array
	(
		'dataContainer'               => 'File',
		'closed'                      => true,
		'onload_callback'             => array(
			array('tl_avisota_settings', 'onload_callback')
		)
	),

	// Palettes
	'palettes'              => array
	(
		'__selector__'                => array(),
	),

	'metapalettes'          => array
	(
		'default' => array(
			'recipients'   => array('avisota_salutations', 'avisota_dont_disable_recipient_on_failure', 'avisota_dont_disable_member_on_failure'),
			'subscription' => array('avisota_template_subscribe_mail_plain', 'avisota_template_subscribe_mail_html', 'avisota_template_unsubscribe_mail_plain', 'avisota_template_unsubscribe_mail_html'),
			'notification' => array(':hide', 'avisota_send_notification'),
			'cleanup'      => array(':hide', 'avisota_do_cleanup'),
			'transport'    => array('avisota_default_transport', 'avisota_max_send_time', 'avisota_max_send_count', 'avisota_max_send_timeout'),
			'backend'      => array(':hide', 'avisota_chart'),
			'developer'    => array(':hide', 'avisota_developer_mode')
		)
	),

	// Subpalettes
	'metasubpalettes'       => array
	(
		'avisota_send_notification' => array('avisota_notification_time', 'avisota_notification_count', 'avisota_template_notification_mail_plain', 'avisota_template_notification_mail_html'),
		'avisota_do_cleanup'        => array('avisota_cleanup_time'),
		'avisota_developer_mode'    => array('avisota_developer_email')
	),

	'metasubselectpalettes' => array
	(
		'avisota_chart' => array
		(
			'highstock' => array('avisota_chart_highstock_confirmed')
		)
	),

	// Fields
	'fields'                => array
	(
		'avisota_salutations'                           => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations'],
			'inputType'               => 'multiColumnWizard',
			'eval'                    => array(
				'columnFields' => array(
					'salutation' => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_salutation'],
						'inputType' => 'text',
						'eval'      => array('style' => 'width: 400px')
					),
					'title'      => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_title'],
						'inputType' => 'checkbox',
						'eval'      => array()
					),
					'firstname'  => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_firstname'],
						'inputType' => 'checkbox',
						'eval'      => array()
					),
					'lastname'   => array(
						'label'     => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_lastname'],
						'inputType' => 'checkbox',
						'eval'      => array()
					)
				)
			)
		),
		'avisota_template_subscribe_mail_plain'         => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_subscribe_mail_plain'],
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_settings', 'getTemplates'),
			'eval'                    => array('tl_class'=> 'w50 clr')
		),
		'avisota_template_subscribe_mail_html'          => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_subscribe_mail_html'],
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_settings', 'getTemplates'),
			'eval'                    => array('tl_class'=> 'w50')
		),
		'avisota_template_unsubscribe_mail_plain'       => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_unsubscribe_mail_plain'],
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_settings', 'getTemplates'),
			'eval'                    => array('tl_class'=> 'w50')
		),
		'avisota_template_unsubscribe_mail_html'        => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_unsubscribe_mail_html'],
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_settings', 'getTemplates'),
			'eval'                    => array('tl_class'=> 'w50')
		),
		'avisota_send_notification'                     => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_send_notification'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true,
			                                   'tl_class'       => 'clr')
		),
		'avisota_notification_time'                     => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_time'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory' => true,
			                                   'rgxp'      => 'digit',
			                                   'tl_class'  => 'w50')
		),
		'avisota_notification_count'                    => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_count'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory' => true,
			                                   'rgxp'      => 'digit',
			                                   'tl_class'  => 'w50')
		),
		'avisota_template_notification_mail_plain'      => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_notification_mail_plain'],
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_settings', 'getTemplates'),
			'eval'                    => array('tl_class'=> 'w50')
		),
		'avisota_template_notification_mail_html'       => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_notification_mail_html'],
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_settings', 'getTemplates'),
			'eval'                    => array('tl_class'=> 'w50')
		),
		'avisota_do_cleanup'                            => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_do_cleanup'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true,
			                                   'tl_class'       => 'w50 clr')
		),
		'avisota_cleanup_time'                          => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_cleanup_time'],
			'default'                 => 7,
			'inputType'               => 'text',
			'eval'                    => array('mandatory' => true,
			                                   'rgxp'      => 'digit',
			                                   'tl_class'  => 'w50')
		),
		'avisota_default_transport'                     => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_default_transport'],
			'inputType'               => 'select',
			'foreignKey'              => 'tl_avisota_transport.title',
			'eval'                    => array('mandatory'=> true,
			                                   'tl_class' => 'w50')
		),
		'avisota_max_send_time'                         => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_time'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> true,
			                                   'rgxp'     => 'digit',
			                                   'tl_class' => 'w50')
		),
		'avisota_max_send_count'                        => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_count'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> true,
			                                   'rgxp'     => 'digit',
			                                   'tl_class' => 'w50')
		),
		'avisota_max_send_timeout'                      => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_timeout'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> true,
			                                   'rgxp'     => 'digit',
			                                   'tl_class' => 'w50')
		),
		'avisota_dont_disable_recipient_on_failure'     => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_dont_disable_recipient_on_failure'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=> 'w50 clr')
		),
		'avisota_dont_disable_member_on_failure'        => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_dont_disable_member_on_failure'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=> 'w50')
		),
		'avisota_chart'                                 => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_chart'],
			'inputType'               => 'select',
			'options'                 => array('jqplot', 'highstock', 'pchart'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_settings'],
			'eval'                    => array('submitOnChange'=> true,
			                                   'tl_class'      => 'clr')
		),
		'avisota_chart_highstock_confirmed'             => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_chart_highstock_confirmed'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=> 'long')
		),
		'avisota_developer_mode'                        => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_developer_mode'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=> true,
			                                   'tl_class'      => 'clr')
		),
		'avisota_developer_email'                       => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_developer_email'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> true,
			                                   'rgxp'     => 'email')
		)
	)
);

class tl_avisota_settings extends Backend
{
	public function onload_callback()
	{
		if (!is_dir(TL_ROOT . '/system/modules/Avisota/highstock')
			|| !is_file(TL_ROOT . '/system/modules/Avisota/highstock/js/highstock.js')
		) {
			$GLOBALS['TL_DCA']['tl_avisota_settings']['fields']['avisota_chart_highstock_confirm']['input_field_callback'] = array('tl_avisota_settings', 'renderMissingHighstockField');
		}
	}

	public function renderMissingHighstockField(DataContainer $dc, $strLabel)
	{
		return $GLOBALS['TL_LANG']['tl_avisota_settings']['missing_highstock'];
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
			case 'avisota_template_notification_mail_plain':
				$strTemplatePrefix = 'mail_notification_plain_';
				break;
			case 'avisota_template_notification_mail_html':
				$strTemplatePrefix = 'mail_notification_html_';
				break;
			default:
				return array();
		}

		return $this->getTemplateGroup($strTemplatePrefix);
	}
}
