<?php

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
 * @author Tristan Lins <tristan.lins@infinitysoft.de>
 * @package Avisota
 * @license LGPL
 * @filesource
 */


/**
 * Table tl_avisota_newsletter_category
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter_category'] = array
(

	// Config
	'config'                => array
	(
		'dataContainer'    => 'Table',
		'ctable'           => array('tl_avisota_newsletter'),
		'switchToEdit'     => true,
		'enableVersioning' => true,
		'onload_callback'  => array
		(
			array('tl_avisota_newsletter_category', 'checkPermission')
		)
	),

	// List
	'list'                  => array
	(
		'sorting'           => array
		(
			'mode'        => 1,
			'flag'        => 1,
			'fields'      => array('title'),
			'panelLayout' => 'search,limit'
		),
		'label'             => array
		(
			'fields' => array('title'),
			'format' => '%s'
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
			'edit'       => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['edit'],
				'href'       => 'table=tl_avisota_newsletter',
				'icon'       => 'edit.gif',
				'attributes' => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['editheader'],
				'href'            => 'act=edit',
				'icon'            => 'header.gif',
				'button_callback' => array('tl_avisota_newsletter_category', 'editHeader'),
				'attributes'      => 'class="edit-header"'
			),
			'copy'       => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['copy'],
				'href'            => 'act=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();"',
				'button_callback' => array('tl_avisota_newsletter_category', 'copyCategory')
			),
			'delete'     => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['delete'],
				'href'            => 'act=delete',
				'icon'            => 'delete.gif',
				'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback' => array('tl_avisota_newsletter_category', 'deleteCategory')
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		),
	),

	// Palettes
	'metapalettes'          => array
	(
		'default' => array
		(
			'category'   => array('title', 'alias'),
			'recipients' => array('recipientsMode'),
			'theme'      => array('themeMode'),
			'transport'  => array('transportMode'),
			'expert'     => array(':hide', 'showInMenu')
		)
	),

	// Subpalettes
	'metasubpalettes' => array
	(
		'showInMenu' => array('menuIcon')
	),

	// Subselectpalettes
	'metasubselectpalettes' => array
	(
		'recipientsMode' => array
		(
			'byCategory'             => array('recipients'),
			'byNewsletterOrCategory' => array('recipients'),
		),
		'themeMode'      => array
		(
			'byCategory'             => array('theme'),
			'byNewsletterOrCategory' => array('theme')
		),
		'transportMode'      => array
		(
			'byCategory'             => array('transport'),
			'byNewsletterOrCategory' => array('transport')
		)
	),

	// Fields
	'fields'                => array
	(
		'title'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['title'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array('mandatory'=> true,
			                     'maxlength'=> 255,
			                     'tl_class' => 'w50')
		),
		'alias'          => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['alias'],
			'exclude'       => true,
			'search'        => true,
			'inputType'     => 'text',
			'eval'          => array('rgxp'             => 'alnum',
			                         'unique'           => true,
			                         'spaceToUnderscore'=> true,
			                         'maxlength'        => 128,
			                         'tl_class'         => 'w50'),
			'save_callback' => array
			(
				array('tl_avisota_newsletter_category', 'generateAlias')
			)
		),

		'recipientsMode' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipientsMode'],
			'default'   => 'byCategory',
			'inputType' => 'select',
			'options'   => array('byCategory', 'byNewsletterOrCategory', 'byNewsletter'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category'],
			'eval'      => array('mandatory'     => true,
			                     'submitOnChange'=> true,
			                     'tl_class'      => 'w50')
		),
		'recipients'     => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipients'],
			'inputType'        => 'checkbox',
			'options_callback' => array('AvisotaBackend', 'getRecipients'),
			'eval'             => array('mandatory'=> true,
			                            'multiple' => true,
			                            'tl_class' => 'clr')
		),
		'themeMode'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['themeMode'],
			'default'   => 'byCategory',
			'inputType' => 'select',
			'options'   => array('byCategory', 'byNewsletterOrCategory', 'byNewsletter'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category'],
			'eval'      => array('mandatory'     => true,
			                     'submitOnChange'=> true,
			                     'tl_class'      => 'w50')
		),
		'theme'          => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['theme'],
			'inputType'  => 'select',
			'foreignKey' => 'tl_avisota_newsletter_theme.title',
			'eval'       => array('mandatory'=> true,
			                      'tl_class' => 'w50')
		),
		'transportMode'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['transportMode'],
			'default'   => 'byCategory',
			'inputType' => 'select',
			'options'   => array('byCategory', 'byNewsletterOrCategory', 'byNewsletter'),
			'reference' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category'],
			'eval'      => array('mandatory'     => true,
			                     'submitOnChange'=> true,
			                     'tl_class'      => 'w50')
		),
		'transport'          => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['transport'],
			'inputType'  => 'select',
			'foreignKey' => 'tl_avisota_transport.title',
			'eval'       => array('includeBlankOption'=> true,
			                      'tl_class' => 'w50')
		),
		'showInMenu'         => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['showInMenu'],
			'inputType'  => 'checkbox',
			'eval'       => array('tl_class' => 'w50')
		),
		'menuIcon'         => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['menuIcon'],
			'inputType'  => 'fileTree',
			'eval'       => array('tl_class' => 'clr',
								  'files' => true,
								  'filesOnly' => true,
								  'fieldType' => 'radio',
								  'extensions' => 'png,gif,jpg,jpeg')
		)
	)
);

class tl_avisota_newsletter_category extends Backend
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
	 * Check permissions to edit table tl_newsletter_channel
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// Set root IDs
		if (!is_array($this->User->avisota_newsletter_categories) || count($this->User->avisota_newsletter_categories) < 1) {
			$root = array(0);
		}
		else
		{
			$root = $this->User->avisota_newsletter_categories;
		}

		$GLOBALS['TL_DCA']['tl_avisota_newsletter_category']['list']['sorting']['root'] = $root;

		// Check permissions to add channels
		if (!$this->User->hasAccess('create', 'avisota_newsletter_category_permissions')) {
			$GLOBALS['TL_DCA']['tl_avisota_newsletter_category']['config']['closed'] = true;
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array($this->Input->get('id'), $root)) {
					$arrNew = $this->Session->get('new_records');

					if (is_array($arrNew['tl_avisota_newsletter_category']) && in_array($this->Input->get('id'), $arrNew['tl_avisota_newsletter_category'])) {
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0]) {
							$objUser = $this->Database->prepare("SELECT avisota_newsletter_categories, avisota_newsletter_category_permissions FROM tl_user WHERE id=?")
								->limit(1)
								->execute($this->User->id);

							$arrNewsletterCategoryPermissions = deserialize($objUser->avisota_newsletter_category_permissions);

							if (is_array($arrNewsletterCategoryPermissions) && in_array('create', $arrNewsletterCategoryPermissions)) {
								$arrNewsletterCategories   = deserialize($objUser->avisota_newsletter_categories);
								$arrNewsletterCategories[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user SET avisota_newsletter_categories=? WHERE id=?")
									->execute(serialize($arrNewsletterCategories), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT avisota_newsletter_categories, avisota_newsletter_category_permissions FROM tl_user_group WHERE id=?")
								->limit(1)
								->execute($this->User->groups[0]);

							$arrNewsletterCategoryPermissions = deserialize($objGroup->avisota_newsletter_category_permissions);

							if (is_array($arrNewsletterCategoryPermissions) && in_array('create', $arrNewsletterCategoryPermissions)) {
								$arrNewsletterCategories   = deserialize($objGroup->avisota_newsletter_categories);
								$arrNewsletterCategories[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user_group SET avisota_newsletter_categories=? WHERE id=?")
									->execute(serialize($arrNewsletterCategories), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[]                                    = $this->Input->get('id');
						$this->User->avisota_newsletter_categories = $root;
					}
				}
			// No break;

			case 'copy':
			case 'paste':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $root) || ($this->Input->get('act') == 'delete' && !$this->User->hasAccess('delete', 'avisota_newsletter_category_permissions'))) {
					$this->log('Not enough permissions to ' . $this->Input->get('act') . ' avisota newsletter category ID "' . $this->Input->get('id') . '"', 'tl_avisota_newsletter_category checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'avisota_newsletter_category_permissions')) {
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act'))) {
					$this->log('Not enough permissions to ' . $this->Input->get('act') . ' avisota newsletter categories', 'tl_avisota_newsletter_category checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Return the edit header button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^tl_avisota_newsletter_category::/', $this->User->alexf)) > 0) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : '';
	}


	/**
	 * Return the copy channel button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function copyCategory($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'avisota_newsletter_category_permissions')) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)) . ' ';
	}


	/**
	 * Return the delete channel button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function deleteCategory($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'avisota_newsletter_category_permissions')) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)) . ' ';
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

		$objAlias = $this->Database->prepare("SELECT id FROM tl_avisota_newsletter_category WHERE alias=?")
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


	public function getStylesheets($dc)
	{
		if (!in_array('layout_additional_sources', $this->Config->getActiveModules())) {
			return array();
		}

		$arrAdditionalSource = array();
		$objAdditionalSource = $this->Database->prepare("
				SELECT
					t.name,
					s.type,
					s.id,
					s.css_url,
					s.css_file
				FROM
					`tl_additional_source` s
				INNER JOIN
					`tl_theme` t
				ON
					t.id=s.pid
				WHERE
						`type`='css_url'
					OR  `type`='css_file'
				ORDER BY
					s.`sorting`")
			->execute($intTheme);
		while ($objAdditionalSource->next())
		{
			$strType = $objAdditionalSource->type;
			$label   = $objAdditionalSource->$strType;

			if ($objAdditionalSource->compress_yui) {
				$label .= '<span style="color: #009;">.yui</span>';
			}

			if ($objAdditionalSource->compress_gz) {
				$label .= '<span style="color: #009;">.gz</span>';
			}

			if (strlen($objAdditionalSource->cc)) {
				$label .= ' <span style="color: #B3B3B3;">[' . $objAdditionalSource->cc . ']</span>';
			}

			if (strlen($objAdditionalSource->media)) {
				$arrMedia = unserialize($objAdditionalSource->media);
				if (count($arrMedia)) {
					$label .= ' <span style="color: #B3B3B3;">[' . implode(', ', $arrMedia) . ']</span>';
				}
			}

			switch ($objAdditionalSource->type) {
				case 'js_file':
				case 'js_url':
					$image = 'iconJS.gif';
					break;

				case 'css_file':
				case 'css_url':
					$image = 'iconCSS.gif';
					break;

				default:
					$image = false;
					if (isset($GLOBALS['TL_HOOKS']['getAdditionalSourceIconImage']) && is_array($GLOBALS['TL_HOOKS']['getAdditionalSourceIconImage'])) {
						foreach ($GLOBALS['TL_HOOKS']['getAdditionalSourceIconImage'] as $callback)
						{
							$this->import($callback[0]);
							$image = $this->$callback[0]->$callback[1]($row);
							if ($image !== false) {
								break;
							}
						}
					}
			}

			if (!isset($arrAdditionalSource[$objAdditionalSource->name])) {
				$arrAdditionalSource[$objAdditionalSource->name] = array();
			}
			$arrAdditionalSource[$objAdditionalSource->name][$objAdditionalSource->id] = ($image ? $this->generateImage($image, $label, 'style="vertical-align:middle"') . ' ' : '') . $label;
		}
		return $arrAdditionalSource;
	}
}
