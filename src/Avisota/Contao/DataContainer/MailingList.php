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

class MailingList extends \Backend
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
		if (!is_array($this->User->avisota_recipient_lists) || count($this->User->avisota_recipient_lists) < 1) {
			$root = array(0);
		}
		else {
			$root = $this->User->avisota_recipient_lists;
		}

		$GLOBALS['TL_DCA']['orm_avisota_mailing_list']['list']['sorting']['root'] = $root;

		// Check permissions to add recipient lists
		if (!$this->User->hasAccess('create', 'avisota_recipient_list_permissions')) {
			$GLOBALS['TL_DCA']['orm_avisota_mailing_list']['config']['closed'] = true;
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
					$newRecords = $this->Session->get('new_records');

					if (is_array($newRecords['orm_avisota_mailing_list']) && in_array(
							$this->Input->get('id'),
							$newRecords['orm_avisota_mailing_list']
						)
					) {
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0]) {
							$user = $this->Database
								->prepare(
									"SELECT avisota_recipient_lists, avisota_recipient_list_permissions FROM tl_user WHERE id=?"
								)
								->limit(1)
								->execute($this->User->id);

							$newsletterCategoryPermissions = deserialize(
								$user->avisota_recipient_list_permissions
							);

							if (is_array($newsletterCategoryPermissions) && in_array(
									'create',
									$newsletterCategoryPermissions
								)
							) {
								$newsletterCategories   = deserialize($user->avisota_recipient_lists);
								$newsletterCategories[] = $this->Input->get('id');

								$this->Database
									->prepare("UPDATE tl_user SET avisota_recipient_lists=? WHERE id=?")
									->execute(serialize($newsletterCategories), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0) {
							$group = $this->Database
								->prepare(
									"SELECT avisota_recipient_lists, avisota_recipient_list_permissions FROM tl_user_group WHERE id=?"
								)
								->limit(1)
								->execute($this->User->groups[0]);

							$newsletterCategoryPermissions = deserialize(
								$group->avisota_recipient_list_permissions
							);

							if (is_array($newsletterCategoryPermissions) && in_array(
									'create',
									$newsletterCategoryPermissions
								)
							) {
								$newsletterCategories   = deserialize($group->avisota_recipient_lists);
								$newsletterCategories[] = $this->Input->get('id');

								$this->Database
									->prepare("UPDATE tl_user_group SET avisota_recipient_lists=? WHERE id=?")
									->execute(serialize($newsletterCategories), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[]                              = $this->Input->get('id');
						$this->User->avisota_recipient_lists = $root;
					}
				}
			// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $root) || ($this->Input->get(
							'act'
						) == 'delete' && !$this->User->hasAccess('delete', 'avisota_recipient_list_permissions'))
				) {
					$this->log(
						'Not enough permissions to ' . $this->Input->get(
							'act'
						) . ' avisota newsletter category ID "' . $this->Input->get('id') . '"',
						'orm_avisota_mailing_list checkPermission',
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
						'avisota_recipient_list_permissions'
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
						'orm_avisota_mailing_list checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}

	/**
	 * @param array          $rowData
	 * @param string         $label
	 * @param \DataContainer $dc
	 *
	 * @return string
	 */
	public function getLabel($rowData, $label, $dc)
	{
		$label = '<div style="padding: 3px 0;"><strong>' . $label . '</strong></div>';

		if (isset($GLOBALS['TL_HOOKS']['avisotaMailingListLabel']) && is_array(
				$GLOBALS['TL_HOOKS']['avisotaMailingListLabel']
			)
		) {
			foreach ($GLOBALS['TL_HOOKS']['avisotaMailingListLabel'] as $callback) {
				$this->import($callback[0]);
				$label = $this->$callback[0]->$callback[1]($rowData, $label, $dc);
			}
		}
		return $label;
	}


	/**
	 * Return the edit list button
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
	public function editList($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^orm_avisota_mailing_list::/', $this->User->alexf)) > 0)
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
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'avisota_recipient_list_permissions'))
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
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'avisota_recipient_list_permissions'))
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
			$value     = standardize($dc->activeRecord->title);
		}

		$aliasResultSet = $this->Database
			->prepare("SELECT id FROM orm_avisota_mailing_list WHERE alias=?")
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
}
