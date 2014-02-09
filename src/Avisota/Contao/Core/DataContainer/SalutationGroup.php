<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\DataContainer;

use Avisota\Contao\Entity\RecipientSource;
use Avisota\Contao\Entity\Salutation;
use Contao\Doctrine\ORM\EntityHelper;

class SalutationGroup extends \Controller
{
	/**
	 * Check permissions to edit table tl_newsletter_channel
	 */
	public function checkPermission()
	{
		return;

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

		$GLOBALS['TL_DCA']['orm_avisota_message_category']['list']['sorting']['root'] = $root;

		// Check permissions to add channels
		if (!$this->User->hasAccess('create', 'avisota_newsletter_category_permissions')) {
			$GLOBALS['TL_DCA']['orm_avisota_message_category']['config']['closed'] = true;
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

					if (is_array($newRecord['orm_avisota_message_category']) && in_array(
							$this->Input->get('id'),
							$newRecord['orm_avisota_message_category']
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
						'orm_avisota_message_category checkPermission',
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
						'orm_avisota_message_category checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}

	public function generate()
	{
		$entityManager = EntityHelper::getEntityManager();

		$this->loadLanguageFile('avisota_salutation');
		$this->loadLanguageFile('orm_avisota_salutation_group');

		$predefinedSalutations = $GLOBALS['AVISOTA_SALUTATION'];

		$salutationGroup = new \Avisota\Contao\Entity\SalutationGroup();
		$salutationGroup->setTitle('Default group generated at ' . date($GLOBALS['TL_CONFIG']['datimFormat']));
		$sorting = 64;
		foreach ($predefinedSalutations as $index => $predefinedSalutation) {
			$salutation = new Salutation();
			$salutation->fromArray($predefinedSalutation);
			$salutation->setSalutation($GLOBALS['TL_LANG']['avisota_salutation'][$index]);
			$salutation->setSalutationGroup($salutationGroup);
			$salutation->setSorting($sorting);
			$salutationGroup->addSalutation($salutation);
			$sorting *= 2;
		}

		$entityManager->persist($salutationGroup);
		$entityManager->flush($salutationGroup);

		$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['orm_avisota_salutation_group']['group_generated'];

		$this->redirect('contao/main.php?do=avisota_salutation');
	}
}
