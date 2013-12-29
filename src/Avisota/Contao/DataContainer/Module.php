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
 * @license    LGPL-3.0+
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

		return \TwigHelper::getTemplateGroup($templatePrefix, $dc->activeRecord->pid);
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
