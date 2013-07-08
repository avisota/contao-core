<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL
 * @filesource
 */

namespace Avisota\Contao\DataContainer;

use Avisota\Contao\Theme as AvisotaTheme;

class Theme extends \Backend
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
		return; // TODO

		if ($this->User->isAdmin) {
			return;
		}

		// Set root IDs
		if (!is_array($this->User->avisota_newsletter_categories) || count(
			$this->User->avisota_newsletter_categories
		) < 1
		) {
			$root = array(0);
		}
		else {
			$root = $this->User->avisota_newsletter_categories;
		}

		$GLOBALS['TL_DCA']['orm_avisota_theme']['list']['sorting']['root'] = $root;

		// Check permissions to add channels
		if (!$this->User->hasAccess('create', 'avisota_newsletter_category_permissions')) {
			$GLOBALS['TL_DCA']['orm_avisota_theme']['config']['closed'] = true;
		}

		// Check current action
		switch ($this->Input->get('act')) {
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array($this->Input->get('id'), $root)) {
					$newRecord = $this->Session->get('new_records');

					if (is_array($newRecord['orm_avisota_theme']) && in_array(
						$this->Input->get('id'),
						$newRecord['orm_avisota_theme']
					)
					) {
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0]) {
							$user = $this->Database
								->prepare(
								"SELECT avisota_newsletter_categories, avisota_newsletter_category_permissions FROM tl_user WHERE id=?"
							)
								->limit(1)
								->execute($this->User->id);

							$newsletterCategoryPermissions = deserialize(
								$user->avisota_newsletter_category_permissions
							);

							if (is_array($newsletterCategoryPermissions) && in_array(
								'create',
								$newsletterCategoryPermissions
							)
							) {
								$newsletterCategories   = deserialize($user->avisota_newsletter_categories);
								$newsletterCategories[] = $this->Input->get('id');

								$this->Database
									->prepare("UPDATE tl_user SET avisota_newsletter_categories=? WHERE id=?")
									->execute(serialize($newsletterCategories), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0) {
							$group = $this->Database
								->prepare(
								"SELECT avisota_newsletter_categories, avisota_newsletter_category_permissions FROM tl_user_group WHERE id=?"
							)
								->limit(1)
								->execute($this->User->groups[0]);

							$newsletterCategoryPermissions = deserialize(
								$group->avisota_newsletter_category_permissions
							);

							if (is_array($newsletterCategoryPermissions) && in_array(
								'create',
								$newsletterCategoryPermissions
							)
							) {
								$newsletterCategories   = deserialize($group->avisota_newsletter_categories);
								$newsletterCategories[] = $this->Input->get('id');

								$this->Database
									->prepare("UPDATE tl_user_group SET avisota_newsletter_categories=? WHERE id=?")
									->execute(serialize($newsletterCategories), $this->User->groups[0]);
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
				if (!in_array($this->Input->get('id'), $root) || ($this->Input->get(
					'act'
				) == 'delete' && !$this->User->hasAccess('delete', 'avisota_newsletter_category_permissions'))
				) {
					$this->log(
						'Not enough permissions to ' . $this->Input->get(
							'act'
						) . ' avisota newsletter category ID "' . $this->Input->get('id') . '"',
						'orm_avisota_theme checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess(
					'delete',
					'avisota_newsletter_category_permissions'
				)
				) {
					$session['CURRENT']['IDS'] = array();
				}
				else {
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act'))) {
					$this->log(
						'Not enough permissions to ' . $this->Input->get('act') . ' avisota newsletter categories',
						'orm_avisota_theme checkPermission',
						TL_ERROR
					);
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
		return ($this->User->isAdmin || count(preg_grep('/^orm_avisota_theme::/', $this->User->alexf)) > 0)
			? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
				$title
			) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : '';
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
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'avisota_newsletter_category_permissions'))
			? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
				$title
			) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> '
			: $this->generateImage(
				preg_replace('/\.gif$/i', '_.gif', $icon)
			) . ' ';
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
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'avisota_newsletter_category_permissions'))
			? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
				$title
			) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> '
			: $this->generateImage(
				preg_replace('/\.gif$/i', '_.gif', $icon)
			) . ' ';
	}


	/**
	 * Autogenerate a news alias if it has not been set yet
	 *
	 * @param mixed $value
	 * @param \DataContainer $dc
	 *
	 * @return string
	 */
	public function generateAlias($value, $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($value)) {
			$autoAlias = true;
			$value  = standardize($dc->activeRecord->title);
		}

		$aliasResultSet = $this->Database
			->prepare("SELECT id FROM orm_avisota_theme WHERE alias=?")
			->execute($value);

		// Check whether the news alias exists
		if ($aliasResultSet->numRows > 1 && !$autoAlias) {
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $value));
		}

		// Add ID to alias
		if ($aliasResultSet->numRows && $autoAlias) {
			$value .= '-' . $dc->id;
		}

		return $value;
	}


	public function getStylesheets($dc)
	{
		$stylesheets = array();

		$theme = $this->Database->execute("SELECT * FROM tl_theme ORDER BY name");

		while ($theme->next()) {
			$stylesheet = $this->Database
				->prepare("SELECT * FROM tl_style_sheet WHERE pid=?")
				->execute($theme->id);
			while ($stylesheet->next()) {
				$stylesheets['system/scripts/' . $stylesheet->name . '.css'] = '<span style="color:#A6A6A6">' . $theme->name . ': </span>' . $stylesheet->name . '<span style="color:#A6A6A6">.css</span>';
			}

			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['avisotaCollectThemeCss']) && is_array(
				$GLOBALS['TL_HOOKS']['avisotaCollectThemeCss']
			)
			) {
				foreach ($GLOBALS['TL_HOOKS']['avisotaCollectThemeCss'] as $callback) {
					$this->import($callback[0]);
					$stylesheets = $this->$callback[0]->$callback[1]($stylesheets, $theme->row());
				}
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaCollectCss']) && is_array($GLOBALS['TL_HOOKS']['avisotaCollectCss'])) {
			foreach ($GLOBALS['TL_HOOKS']['avisotaCollectCss'] as $callback) {
				$this->import($callback[0]);
				$stylesheets = $this->$callback[0]->$callback[1]($stylesheets);
			}
		}

		return $stylesheets;
	}

	public function getHtmlTemplates()
	{
		return AvisotaTheme::getInstance()->getTemplateGroup('mail_html_', $this->Input->get('id'));
	}

	public function getPlainTemplates()
	{
		return AvisotaTheme::getInstance()->getTemplateGroup('mail_plain_', $this->Input->get('id'));
	}
}
