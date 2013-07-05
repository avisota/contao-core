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

/**
 * Class Module
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class Module extends \Backend
{
	public function onload_callback()
	{
		\MetaPalettes::appendFields('tl_module', 'registration', 'config', array('avisota_selectable_lists'));
		\MetaPalettes::appendFields('tl_module', 'personalData', 'config', array('avisota_selectable_lists'));
	}

	/**
	 * Get the category options
	 *
	 * @return array
	 */
	public function getCategories()
	{
		$category = $this->Database
			->execute("SELECT * FROM orm_avisota_mailing_category ORDER BY title");
		$lists     = array();
		while ($category->next()) {
			$lists[$category->id] = $category->title;
		}
		return $lists;
	}

	/**
	 * Get the lists options.
	 *
	 * @return array
	 */
	public function getLists()
	{
		$list = $this->Database->execute(
			"
				SELECT
					*
				FROM
					`orm_avisota_mailing_list`
				ORDER BY
					`title`"
		);
		$lists = array();
		while ($list->next()) {
			$lists[$list->id] = $list->title;
		}
		return $lists;
	}

	public function getTemplates(\DataContainer $dc)
	{
		// Return all templates
		switch ($dc->field) {
			case 'avisota_reader_template':
				$templatePrefix = 'avisota_reader_';
				break;

			case 'avisota_list_template':
				$templatePrefix = 'avisota_list_';
				break;

			case 'avisota_template_subscribe':
				$templatePrefix = 'avisota_subscribe_';
				break;

			case 'avisota_template_unsubscribe':
				$templatePrefix = 'avisota_unsubscribe_';
				break;

			case 'avisota_template_subscription':
				$templatePrefix = 'avisota_subscription_';
				break;

			default:
				return array();
		}

		return $this->getTemplateGroup($templatePrefix, $dc->activeRecord->pid);
	}

	public function getEditableRecipientProperties()
	{
		$return = array();

		$this->loadLanguageFile('orm_avisota_recipient');
		$this->loadDataContainer('orm_avisota_recipient');

		foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $k => $v) {
			if ($v['eval']['feEditable']) {
				$return[$k] = $GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'][$k]['label'][0];
			}
		}

		return $return;
	}


	/**
	 * Return all subscription templates as array
	 *
	 * @param object
	 *
	 * @return array
	 */
	public function getSubscriptionTemplates(\DataContainer $dc)
	{
		$pid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll') {
			$pid = $this->Input->get('id');
		}

		return array_merge
		(
			array('mod_avisota_subscription'),
			$this->getTemplateGroup('subscription_', $pid)
		);
	}
}
