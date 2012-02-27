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
 * Table tl_avisota_transport
 */
$GLOBALS['TL_DCA']['tl_avisota_transport'] = array
(

	// Config
	'config'          => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onload_callback'             => array(array('tl_avisota_transport', 'onload_callback')),
		'onsubmit_callback'           => array(array('tl_avisota_transport', 'onsubmit_callback'))
	),

	// List
	'list'            => array
	(
		'sorting'           => array
		(
			'mode'                    => 1,
			'flag'                    => 11,
			'fields'                  => array('type', 'title')
		),
		'label'             => array
		(
			'fields'                  => array('title', 'type'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">(%s)</span>'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations'        => array
		(
			'edit'   => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_transport']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_transport']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show'   => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_transport']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		),
	),

	// Palettes
	'palettes'        => array(
		'__selector__' => array('type')
	),

	// Meta Palettes
	'metapalettes'    => array
	(
		'default' => array(
			'transport' => array('type')
		),
		'swift'   => array(
			'transport' => array('title', 'type'),
			'swift'     => array('swiftUseSmtp')
		)
	),

	'submetapalettes' => array
	(
		'swiftUseSmtp' => array('swiftSmtpHost', 'swiftSmtpUser', 'swiftSmtpPass', 'swiftSmtpEnc', 'swiftSmtpPort')
	),

	// Fields
	'fields'          => array
	(
		'type'          => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_transport']['type'],
			'inputType'               => 'select',
			'options'                 => array_keys($GLOBALS['TL_AVISOTA_TRANSPORT']),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_transport'],
			'eval'                    => array('mandatory'         => true,
			                                   'submitOnChange'    => true,
			                                   'includeBlankOption'=> true,
			                                   'tl_class'          => 'w50')
		),
		'title'         => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_transport']['title'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> true,
			                                   'maxlength'=> 255,
			                                   'tl_class' => 'w50')
		),

		// swift mailer
		'swiftUseSmtp'  => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftUseSmtp'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=> true)
		),
		'swiftSmtpHost' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpHost'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> true,
			                                   'maxlength'=> 64,
			                                   'nospace'  => true,
			                                   'doNotShow'=> true,
			                                   'tl_class' => 'long')
		),
		'swiftSmtpUser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpUser'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=> true,
			                                   'maxlength'     => 128,
			                                   'doNotShow'     => true,
			                                   'tl_class'      => 'w50')
		),
		'swiftSmtpPass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpPass'],
			'exclude'                 => true,
			'inputType'               => 'textStore',
			'eval'                    => array('decodeEntities'=> true,
			                                   'maxlength'     => 32,
			                                   'doNotShow'     => true,
			                                   'tl_class'      => 'w50')
		),
		'swiftSmtpEnc'  => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpEnc'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('ssl'=> 'SSL',
			                                   'tls'=> 'TLS'),
			'eval'                    => array('includeBlankOption' => true,
			                                   'doNotShow'          => true,
			                                   'tl_class'           => 'w50')
		),
		'swiftSmtpPort' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpPort'],
			'default'                 => 25,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> true,
			                                   'rgxp'     => 'digit',
			                                   'nospace'  => true,
			                                   'doNotShow'=> true,
			                                   'tl_class' => 'w50')
		),
	)
);

class tl_avisota_transport extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function onload_callback(DataContainer $dc)
	{
	}

	public function onsubmit_callback(DataContainer $dc)
	{
	}


	/**
	 * Check permissions to edit table tl_avisota_transport
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// TODO
	}
}
