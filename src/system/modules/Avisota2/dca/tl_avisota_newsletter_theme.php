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
 * Table tl_avisota_newsletter_theme
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter_theme'] = array
(

	// Config
	'config'          => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onload_callback'             => array
		(
			array('tl_avisota_newsletter_theme', 'checkPermission')
		)
	),

	// List
	'list'            => array
	(
		'sorting'           => array
		(
			'mode'                    => 1,
			'flag'                    => 1,
			'fields'                  => array('title'),
			'panelLayout'             => 'limit'
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
			'edit'   => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy'   => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_avisota_newsletter_theme', 'copyCategory')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_avisota_newsletter_theme', 'deleteCategory')
			),
			'show'   => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		),
	),

	// Palettes
	'metapalettes'    => array
	(
		'default'                     => array
		(
			'theme'       => array('title', 'preview'),
			'structure'   => array('areas'),
			'template'    => array('stylesheets', 'template_html', 'template_plain'),
			'expert'      => array(':hide', 'templateDirectory')
		)
	),

	// Subpalettes
	'metasubpalettes' => array
	(
	),

	// Fields
	'fields'          => array
	(
		'title'               => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> true,
			                                   'maxlength'=> 255,
			                                   'tl_class' => 'w50')
		),
		'preview'             => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['preview'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('files'     => true,
			                                   'filesOnly' => true,
			                                   'fieldType' => 'radio',
			                                   'extensions'=> 'jpg,jpeg,png,gif',
			                                   'tl_class'  => 'clr')
		),

		'areas'               => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['areas'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> false,
			                                   'rgxp'     => 'extnd',
			                                   'nospace'  => true)
		),
		'stylesheets'         => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['stylesheets'],
			'inputType'               => 'checkboxWizard',
			'options_callback'        => array('tl_avisota_newsletter_theme', 'getStylesheets'),
			'eval'                    => array('tl_class'=> 'clr',
			                                   'multiple'=> true)
		),
		'template_html'       => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['template_html'],
			'default'                 => 'mail_html_default',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('tl_avisota_newsletter_theme', 'getHtmlTemplates'),
			'eval'                    => array('tl_class'=> 'w50')
		),
		'template_plain'      => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['template_plain'],
			'default'                 => 'mail_plain_default',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('tl_avisota_newsletter_theme', 'getPlainTemplates'),
			'eval'                    => array('tl_class'=> 'w50')
		),
		'templateDirectory'   => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_theme']['templateDirectory'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('tl_class' => 'clr',
			                                   'fieldType'=> 'radio',
			                                   'path'     => 'templates')
		)
	)
);

class tl_avisota_newsletter_theme extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		$this->import('AvisotaBase', 'Base');
	}


	/**
	 * Check permissions to edit table tl_newsletter_channel
	 */
	public function checkPermission()
	{
		return; // TODO

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

		$GLOBALS['TL_DCA']['tl_avisota_newsletter_theme']['list']['sorting']['root'] = $root;

		// Check permissions to add channels
		if (!$this->User->hasAccess('create', 'avisota_newsletter_category_permissions')) {
			$GLOBALS['TL_DCA']['tl_avisota_newsletter_theme']['config']['closed'] = true;
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

					if (is_array($arrNew['tl_avisota_newsletter_theme']) && in_array($this->Input->get('id'), $arrNew['tl_avisota_newsletter_theme'])) {
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
					$this->log('Not enough permissions to ' . $this->Input->get('act') . ' avisota newsletter category ID "' . $this->Input->get('id') . '"', 'tl_avisota_newsletter_theme checkPermission', TL_ERROR);
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
					$this->log('Not enough permissions to ' . $this->Input->get('act') . ' avisota newsletter categories', 'tl_avisota_newsletter_theme checkPermission', TL_ERROR);
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
		return ($this->User->isAdmin || count(preg_grep('/^tl_avisota_newsletter_theme::/', $this->User->alexf)) > 0) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : '';
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

		$objAlias = $this->Database->prepare("SELECT id FROM tl_avisota_newsletter_theme WHERE alias=?")
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
		$arrStylesheets = array();

		$objTheme = $this->Database->execute("SELECT * FROM tl_theme ORDER BY name");

		while ($objTheme->next()) {
			$objCss = $this->Database
				->prepare("SELECT * FROM tl_style_sheet WHERE pid=?")
				->execute($objTheme->id);
			while ($objCss->next()) {
				$arrStylesheets['system/scripts/' . $objCss->name . '.css'] = '<span style="color:#A6A6A6">' . $objTheme->name . ': </span>' . $objCss->name . '<span style="color:#A6A6A6">.css</span>';
			}

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['avisotaCollectThemeCss']) && is_array($GLOBALS['TL_HOOKS']['avisotaCollectThemeCss'])) {
				foreach ($GLOBALS['TL_HOOKS']['avisotaCollectThemeCss'] as $callback)
				{
					$this->import($callback[0]);
					$arrStylesheets = $this->$callback[0]->$callback[1]($arrStylesheets, $objTheme->row());
				}
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaCollectCss']) && is_array($GLOBALS['TL_HOOKS']['avisotaCollectCss'])) {
			foreach ($GLOBALS['TL_HOOKS']['avisotaCollectCss'] as $callback)
			{
				$this->import($callback[0]);
				$arrStylesheets = $this->$callback[0]->$callback[1]($arrStylesheets);
			}
		}

		return $arrStylesheets;
	}

	public function getHtmlTemplates()
	{
		return $this->Base->getTemplateGroup('mail_html_', $this->Input->get('id'));
	}

	public function getPlainTemplates()
	{
		return $this->Base->getTemplateGroup('mail_plain_', $this->Input->get('id'));
	}
}
