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

class NestedMenu extends \Controller
{
	/**
	 * @var Backend
	 */
	protected static $instance = null;

	/**
	 * @static
	 * @return Backend
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new NestedMenu();
		}
		return self::$instance;
	}

	protected function __construct()
	{
		parent::__construct();
	}

	public function hookNestedMenuPreContent($do)
	{
		if ($do == 'avisota_config') {
			return sprintf(
				'<div class="avisota-logo"><a href="http://avisota.org" target="_blank">%s</a></div>',
				$this->generateImage('system/modules/avisota/assets/images/logo.png', 'Avisota newsletter and mailing system')
			);
		}
	}

	public function hookNestedMenuPostContent($do)
	{
		if ($do == 'avisota_config') {
			$config = \Config::getInstance();
			$this->loadLanguageFile('avisota_promotion');

			$context = array(
				'donate' => $GLOBALS['TL_LANG']['avisota_promotion']['donate'],
				'copyright' => 'Avisota newsletter and mailing system &copy; 2013 bit3 UG and all <a href="https://github.com/avisota/contao/graphs/contributors" target="_blank">contributors</a>',
				'disclaimer' => 'Avisota use icons from the <a href="http://www.famfamfam.com/" target="_blank">famfamfam silk icons</a> and <a href="http://www.picol.org/" target="_blank">Picol Vector icons</a>.',
			);
			if (!in_array('avisota-business', $config->getActiveModules())) {
				$context['business'] = $GLOBALS['TL_LANG']['avisota_promotion']['business'];
			}

			$template = new \TwigTemplate('avisota/backend/config_footer', 'html5');
			return $template->parse($context);
		}
	}
}
