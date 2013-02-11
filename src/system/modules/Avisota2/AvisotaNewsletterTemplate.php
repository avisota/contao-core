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
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class AvisotaNewsletterTemplate
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaNewsletterTemplate extends Template
{

	/**
	 * Add a hook to modify the template output
	 *
	 * @return string
	 */
	public function parse()
	{
		$buffer = parent::parse();

		// HOOK: add custom parse filters
		if (isset($GLOBALS['TL_HOOKS']['parseAvisotaNewsletterTemplate']) && is_array(
			$GLOBALS['TL_HOOKS']['parseAvisotaNewsletterTemplate']
		)
		) {
			foreach ($GLOBALS['TL_HOOKS']['parseAvisotaNewsletterTemplate'] as $callback) {
				$this->import($callback[0]);
				$buffer = $this->$callback[0]->$callback[1]($buffer, $this->strTemplate);
			}
		}

		return $buffer;
	}


	/**
	 * Parse the template file, replace insert tags and print it to the screen
	 */
	public function output()
	{
		// Parse the template
		$buffer = str_replace(' & ', ' &amp; ', $this->parse());

		// HOOK: add custom output filters
		if (isset($GLOBALS['TL_HOOKS']['outputAvisotaNewsletterTemplate']) && is_array(
			$GLOBALS['TL_HOOKS']['outputAvisotaNewsletterTemplate']
		)
		) {
			foreach ($GLOBALS['TL_HOOKS']['outputAvisotaNewsletterTemplate'] as $callback) {
				$this->import($callback[0]);
				$buffer = $this->$callback[0]->$callback[1]($buffer, $this->strTemplate);
			}
		}

		$this->strBuffer = $buffer;

		echo $this->strBuffer;

		// Debug information
		if ($GLOBALS['TL_CONFIG']['debugMode']) {
			echo "\n\n" . '<pre id="debug" style="width:80%;overflow:auto;margin:24px auto;padding:9px;background:#fff;color:#000">' . "\n";
			echo "<strong>Debug information</strong>\n\n";
			print_r($GLOBALS['TL_DEBUG']);
			echo '</pre>';
		}
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
		$template = basename($template);
		$filename = $template . '.html5';

		/** @var AvisotaNewsletter $newsletter */
		$newsletter = AvisotaStatic::getNewsletter();

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
		foreach (array_reverse($this->Config->getActiveModules()) as $moduleName) {
			$pathname = TL_ROOT . '/system/modules/' . $moduleName . '/templates/' . $filename;

			if (file_exists($pathname)) {
				return $pathname;
			}

			// Also check for .tpl files (backwards compatibility)
			$pathname = TL_ROOT . '/system/modules/' . $moduleName . '/templates/' . $template . '.tpl';

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
	protected function getTemplateGroup($prefix, $themeId = 0)
	{
		$folders   = array();
		$templates = array();

		// Add the templates root directory
		$folders[] = TL_ROOT . '/templates';

		// Add the theme templates folder
		if ($themeId > 0) {
			$theme = $this->Database
				->prepare("SELECT * FROM tl_avisota_newsletter_theme WHERE id=?")
				->limit(1)
				->execute($themeId);

			if ($theme->numRows > 0 && $theme->templateDirectory != '') {
				$folders[] = TL_ROOT . '/' . $theme->templateDirectory;
			}
		}
		else {
			$newsletter = AvisotaStatic::getNewsletter();

			// Check for a theme folder
			if ($newsletter) {
				$folders[] = TL_ROOT . '/' . $newsletter
					->getTheme()
					->getTemplateDirectory();
			}
		}

		// Add the module templates folders if they exist
		foreach ($this->Config->getActiveModules() as $module) {
			$folder = TL_ROOT . '/system/modules/' . $module . '/templates';

			if (is_dir($folder)) {
				$folders[] = $folder;
			}
		}

		// Find all matching templates
		foreach ($folders as $folder) {
			$files = preg_grep('/^' . preg_quote($prefix, '/') . '/i', scan($folder));

			foreach ($files as $file) {
				$filename        = basename($file);
				$templates[] = substr($filename, 0, strrpos($filename, '.'));
			}
		}

		natcasesort($templates);
		$templates = array_values(array_unique($templates));

		return $templates;
	}
}
