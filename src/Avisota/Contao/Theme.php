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

namespace Avisota\Contao;

/**
 * Class Theme
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class Theme
{
	/**
	 * Singleton instance.
	 *
	 * @var AvisotaBase
	 */
	private static $instance = null;


	/**
	 * Get singleton instance.
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new Theme();
		}
		return self::$instance;
	}


	/**
	 * Singleton
	 */
	protected function __construct()
	{
	}

	/**
	 * Find a particular template file and return its path
	 *
	 * @author     Leo Feyer <http://www.contao.org>
	 * @see        Controll::getTemplate in Contao OpenSource CMS
	 *
	 * @param string
	 * @param string
	 *
	 * @return string
	 * @throws Exception
	 */
	public function getTemplate($template, $format = 'html5')
	{
		$config = \Config::getInstance();

		$template = basename($template);
		$filename = $template . '.html5';

		/** @var AvisotaNewsletter $newsletter */
		global $newsletter;

		// Check for a theme folder
		if ($newsletter) {
			$templateGroup = $newsletter
				->getTheme()
				->getTemplateDirectory();
		}
		else {
			$templateGroup = '';
		}

		$path = TL_ROOT . '/templates';

		// Check the theme folder first
		if (TL_MODE == 'FE' && $templateGroup != '') {
			$pathname = $path . '/' . $templateGroup . '/' . $filename;

			if (file_exists($pathname)) {
				return $pathname;
			}

			// Also check for .tpl files (backwards compatibility)
			$pathname = $path . '/' . $templateGroup . '/' . $template . '.tpl';

			if (file_exists($pathname)) {
				return $pathname;
			}
		}

		// Then check the global templates directory
		$pathname = $path . '/' . $filename;

		if (file_exists($pathname)) {
			return $pathname;
		}

		// Also check for .tpl files (backwards compatibility)
		$pathname = $path . '/' . $template . '.tpl';

		if (file_exists($pathname)) {
			return $pathname;
		}

		// At last browse all module folders in reverse order
		foreach (array_reverse($config->getActiveModules()) as $module) {
			$pathname = TL_ROOT . '/system/modules/' . $module . '/templates/' . $filename;

			if (file_exists($pathname)) {
				return $pathname;
			}

			// Also check for .tpl files (backwards compatibility)
			$pathname = TL_ROOT . '/system/modules/' . $module . '/templates/' . $template . '.tpl';

			if (file_exists($pathname)) {
				return $pathname;
			}
		}

		throw new Exception('Could not find template file "' . $filename . '"');
	}


	/**
	 * Return all template files of a particular group as array
	 *
	 * @author     Leo Feyer <http://www.contao.org>
	 * @see        Controll::getTemplate in Contao OpenSource CMS
	 *
	 * @param string
	 * @param integer
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getTemplateGroup($prefix, $themeId = 0)
	{
		$config   = \Config::getInstance();
		$database = \Database::getInstance();

		$folders   = array();
		$templates = array();

		// Add the templates root directory
		$folders[] = TL_ROOT . '/templates';

		// Add the theme templates folder
		if ($themeId > 0) {
			$theme = $database
				->prepare("SELECT * FROM orm_avisota_mailing_theme WHERE id=?")
				->limit(1)
				->execute($themeId);

			if ($theme->numRows > 0 && $theme->templateDirectory != '') {
				$folders[] = TL_ROOT . '/' . $theme->templateDirectory;
			}
		}

		// Add the module templates folders if they exist
		foreach ($config->getActiveModules() as $moduleName) {
			$folder = TL_ROOT . '/system/modules/' . $moduleName . '/templates';

			if (is_dir($folder)) {
				$folders[] = $folder;
			}
		}

		// Find all matching templates
		foreach ($folders as $folder) {
			$files = preg_grep('/^' . preg_quote($prefix, '/') . '/i', scan($folder));

			foreach ($files as $pathname) {
				$filename        = basename($pathname);
				$templates[] = substr($filename, 0, strrpos($filename, '.'));
			}
		}

		natcasesort($templates);
		$templates = array_values(array_unique($templates));

		return $templates;
	}
}
