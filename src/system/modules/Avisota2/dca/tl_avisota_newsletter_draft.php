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
 * Table tl_avisota_newsletter_draft
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter_draft'] = array
(

	// Config
	'config'          => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_avisota_newsletter_draft_content'),
		'switchToEdit'                => true,
		'enableVersioning'            => true
	),

	// List
	'list'            => array
	(
		'sorting'           => array
		(
			'mode'                    => 1,
			'flag'                    => 11,
			'fields'                  => array('title'),
			'panelLayout'             => 'search,limit'
		),
		'label'             => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s'
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
			'edit'       => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['edit'],
				'href'                => 'table=tl_avisota_newsletter_draft_content',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'attributes'          => 'class="edit-header"'
			),
			'copy'       => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete'     => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show'       => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'preview'    => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['preview'],
				'href'                => 'key=preview',
				'icon'                => 'system/modules/Avisota2/html/preview.png'
			)
		),
	),

	// Palettes
	'metapalettes'    => array
	(
		'default'         => array(
			'newsletter' => array('title', 'alias', 'description'),
			'attachment' => array('addFile')
		),
	),

	// Subpalettes
	'metasubpalettes' => array
	(
		'addFile'                     => array('files')
	),

	// Fields
	'fields'          => array
	(
		'title'       => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> true,
			                                   'maxlength'=> 255,
			                                   'tl_class' => 'w50')
		),
		'alias'       => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'             => 'alnum',
			                                   'unique'           => true,
			                                   'spaceToUnderscore'=> true,
			                                   'maxlength'        => 128,
			                                   'tl_class'         => 'w50'),
			'save_callback'           => array
			(
				array('tl_avisota_newsletter_draft', 'generateAlias')
			)
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['description'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'       => 'tinyMCE',
			                                   'helpwizard'=> true),
			'explanation'             => 'insertTags'
		),

		'addFile'     => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['addFile'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=> true)
		),
		'files'       => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['files'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=> 'checkbox',
			                                   'files'    => true,
			                                   'filesOnly'=> true,
			                                   'mandatory'=> true)
		)
	)
);


class tl_avisota_newsletter_draft extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Autogenerate a news alias if it has not been set yet
	 *
	 * @param mixed
	 * @param object
	 *
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue)) {
			$autoAlias = true;
			$varValue  = standardize($dc->activeRecord->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_avisota_newsletter_draft WHERE alias=?")
			->execute($varValue);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias) {
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias) {
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}
}
