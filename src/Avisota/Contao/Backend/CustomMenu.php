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

namespace Avisota\Contao\Backend;

use BackendTemplate;
use Contao\Doctrine\ORM\EntityHelper;

class CustomMenu extends \BackendModule
{
	/**
	 * Lazy initialize
	 */
	static public function lazyInit()
	{
		spl_autoload_register(
			'Avisota\Contao\Backend\CustomMenu::autoload',
			true,
			true
		);
		if (version_compare(VERSION, '3', '<')) {
			spl_autoload_register('__autoload');
		}
	}

	/**
	 * Initialize
	 * @param $className
	 *
	 * @return bool
	 */
	static public function autoload($className)
	{
		if ($className == 'RequestToken') {
			static::init();
			spl_autoload_unregister('Avisota\Contao\Backend\CustomMenu::autoload');
		}
		return false;
	}

	static public function init()
	{
		$beModules = array();
		if (isset($GLOBALS['AVISOTA_DYNAMICS']['category']) && is_array($GLOBALS['AVISOTA_DYNAMICS']['category'])) {
			foreach ($GLOBALS['AVISOTA_DYNAMICS']['category'] as $category) {
				$beModules['avisota_category_' . $category['id']] = array(
					'callback' => 'Avisota\Contao\Backend\CustomMenu',
					'icon'     => $category['icon'] ?: 'system/modules/avisota/html/newsletter.png',
				);
			}
		}

		if (count($beModules)) {
			$GLOBALS['BE_MOD']['avisota'] = array_merge(
				array_slice($GLOBALS['BE_MOD']['avisota'], 0, 1),
				$beModules,
				array_slice($GLOBALS['BE_MOD']['avisota'], 1)
			);
		}
	}

	static public function hookLoadLanguageFile($file)
	{
		if (isset($GLOBALS['AVISOTA_DYNAMICS']['category']) && is_array($GLOBALS['AVISOTA_DYNAMICS']['category']) && $file == 'modules') {
			foreach ($GLOBALS['AVISOTA_DYNAMICS']['category'] as $category) {
				$GLOBALS['TL_LANG']['MOD']['avisota_category_' . $category['id']] = $category['label'];
			}
		}
	}

	static public function hookGetUserNavigation(array $navigation, $showAll)
	{
		if (TL_MODE == 'BE') {
			try {
				$GLOBALS['TL_CSS']['avisota-be-global'] = 'system/modules/avisota/assets/css/be_global.css';

				if (Outbox::isEmpty()) {
					$navigation['avisota']['modules']['avisota_outbox']['class'] .= ' avisota_outbox_empty';
				}

				if (!$showAll) {
					$input = \Input::getInstance();
					$do    = $input->get('do');
					$table = $input->get('table');
					$id = $input->get('id');

					if ($do == 'avisota_newsletter') {
						if ($table == 'orm_avisota_message_category') {
							// the $id is already the category id
						}
						else if ($table == 'orm_avisota_message') {
							if ($input->get('key') == 'send') {
								$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
								$message = $messageRepository->find($id);
								$id = $message->getCategory()->id();
							}
						}
						else if ($table == 'orm_avisota_message_content') {
							$act = $input->get('act');
							if ($act == 'create') {
								$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
								$message = $messageRepository->find($input->get('pid'));
								$id = $message->getCategory()->id();
							}
							else if ($act) {
								$contentRepository = EntityHelper::getRepository('Avisota\Contao:MessageContent');
								$content = $contentRepository->find($id);
								$id = $content->getMessage()->getCategory()->id();
							}
							else {
								$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
								$message = $messageRepository->find($id);
								$id = $message->getCategory()->id();
							}
						}
						else {
							return $navigation;
						}

						$foundCustomEntry = false;

						$menu = &$navigation['avisota'];
						foreach ($menu['modules'] as $name => &$module) {
							if ($name == 'avisota_category_' . $id) {
								$module['class'] .= ' active';
								$foundCustomEntry = true;
							}
						}

						if ($foundCustomEntry) {
							$classes = explode(' ', $menu['modules']['avisota_newsletter']['class']);
							$classes = array_map('trim', $classes);
							$pos = array_search('active', $classes);
							if ($pos !== false) {
								unset($classes[$pos]);
							}
							$menu['modules']['avisota_newsletter']['class'] = implode(' ', $classes);
						}
					}
				}
			}
			catch (\Exception $exception) {
				// silently ignore
			}
		}
		return $navigation;
	}

	public function generate()
	{
		$do = \Input::getInstance()->get('do');
		$id = preg_replace('#^avisota_category_(.*)$#', '$1', $do);

		$this->redirect('contao/main.php?do=avisota_newsletter&table=orm_avisota_message&id=' . $id);
	}

	/**
	 * Compile the current element
	 */
	protected function compile()
	{
	}
}
