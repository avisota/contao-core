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
		$strBuffer = parent::parse();

		// HOOK: add custom parse filters
		if (isset($GLOBALS['TL_HOOKS']['parseAvisotaNewsletterTemplate']) && is_array(
			$GLOBALS['TL_HOOKS']['parseAvisotaNewsletterTemplate']
		)
		) {
			foreach ($GLOBALS['TL_HOOKS']['parseAvisotaNewsletterTemplate'] as $callback) {
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($strBuffer, $this->strTemplate);
			}
		}

		return $strBuffer;
	}


	/**
	 * Parse the template file, replace insert tags and print it to the screen
	 */
	public function output()
	{
		global $objPage;

		// Parse the template
		$strBuffer = str_replace(' & ', ' &amp; ', $this->parse());

		// HOOK: add custom output filters
		if (isset($GLOBALS['TL_HOOKS']['outputAvisotaNewsletterTemplate']) && is_array(
			$GLOBALS['TL_HOOKS']['outputAvisotaNewsletterTemplate']
		)
		) {
			foreach ($GLOBALS['TL_HOOKS']['outputAvisotaNewsletterTemplate'] as $callback) {
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($strBuffer, $this->strTemplate);
			}
		}

		$this->strBuffer = $strBuffer;

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
	public function getTemplate($strTemplate, $strFormat = 'html5')
	{
		$strTemplate = basename($strTemplate);
		$strFilename = $strTemplate . '.html5';

		/** @var AvisotaNewsletter $objNewsletter */
		$objNewsletter = AvisotaStatic::getNewsletter();

		// Check for a theme folder
		if ($objNewsletter) {
			$strTemplateGroup = $objNewsletter
				->getTheme()
				->getTemplateDirectory();
		}
		else {
			$strTemplateGroup = '';
		}

		$strPath = TL_ROOT . '/templates';

		// Check the theme folder first
		if (TL_MODE == 'FE' && $strTemplateGroup != '') {
			$strFile = $strPath . '/' . $strTemplateGroup . '/' . $strFilename;

			if (file_exists($strFile)) {
				return $strFile;
			}

			// Also check for .tpl files (backwards compatibility)
			$strFile = $strPath . '/' . $strTemplateGroup . '/' . $strTemplate . '.tpl';

			if (file_exists($strFile)) {
				return $strFile;
			}
		}

		// Then check the global templates directory
		$strFile = $strPath . '/' . $strFilename;

		if (file_exists($strFile)) {
			return $strFile;
		}

		// Also check for .tpl files (backwards compatibility)
		$strFile = $strPath . '/' . $strTemplate . '.tpl';

		if (file_exists($strFile)) {
			return $strFile;
		}

		// At last browse all module folders in reverse order
		foreach (array_reverse($this->Config->getActiveModules()) as $strModule) {
			$strFile = TL_ROOT . '/system/modules/' . $strModule . '/templates/' . $strFilename;

			if (file_exists($strFile)) {
				return $strFile;
			}

			// Also check for .tpl files (backwards compatibility)
			$strFile = TL_ROOT . '/system/modules/' . $strModule . '/templates/' . $strTemplate . '.tpl';

			if (file_exists($strFile)) {
				return $strFile;
			}
		}

		throw new Exception('Could not find template file "' . $strFilename . '"');
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
	protected function getTemplateGroup($strPrefix, $intTheme = 0)
	{
		$arrFolders   = array();
		$arrTemplates = array();

		// Add the templates root directory
		$arrFolders[] = TL_ROOT . '/templates';

		// Add the theme templates folder
		if ($intTheme > 0) {
			$objTheme = $this->Database
				->prepare("SELECT * FROM tl_avisota_newsletter_theme WHERE id=?")
				->limit(1)
				->execute($intTheme);

			if ($objTheme->numRows > 0 && $objTheme->templateDirectory != '') {
				$arrFolders[] = TL_ROOT . '/' . $objTheme->templateDirectory;
			}
		}
		else {
			$objNewsletter = AvisotaStatic::getNewsletter();

			// Check for a theme folder
			if ($objNewsletter) {
				$arrFolders[] = TL_ROOT . '/' . $objNewsletter
					->getTheme()
					->getTemplateDirectory();
			}
		}

		// Add the module templates folders if they exist
		foreach ($this->Config->getActiveModules() as $strModule) {
			$strFolder = TL_ROOT . '/system/modules/' . $strModule . '/templates';

			if (is_dir($strFolder)) {
				$arrFolders[] = $strFolder;
			}
		}

		// Find all matching templates
		foreach ($arrFolders as $strFolder) {
			$arrFiles = preg_grep('/^' . preg_quote($strPrefix, '/') . '/i', scan($strFolder));

			foreach ($arrFiles as $strTemplate) {
				$strName        = basename($strTemplate);
				$arrTemplates[] = substr($strName, 0, strrpos($strName, '.'));
			}
		}

		natcasesort($arrTemplates);
		$arrTemplates = array_values(array_unique($arrTemplates));

		return $arrTemplates;
	}
}
