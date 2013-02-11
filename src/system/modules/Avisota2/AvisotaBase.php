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
 * Class AvisotaBase
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBase extends Controller
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
			self::$instance = new AvisotaBase();
		}
		return self::$instance;
	}


	/**
	 * Singleton
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('AvisotaStatic', 'Static');
		if (TL_MODE == 'FE') {
			$this->import('FrontendUser', 'User');
		}
		else {
			$this->import('BackendUser', 'User');
			$this->User->authenticate();
		}
		$this->import('Database');
		$this->import('DomainLink');
	}


	public function getViewOnlinePage($category = null, $recipients = null)
	{
		if (is_null($category)) {
			$category = $this->Static->getCategory();
		}

		if (is_null($recipients)) {
			$recipients = $this->Static->getRecipient();
		}

		if ($recipients && preg_match('#^list:(\d+)$#', $recipients['outbox_source'], $matches)) {
			// the dummy list, used on preview
			if ($matches[1] > 0) {
				$recipientList = $this->Database
					->prepare(
					"
						SELECT
							*
						FROM
							`tl_avisota_mailing_list`
						WHERE
							`id`=?"
				)
					->execute($matches[1]);
				if ($recipientList->next()) {
					return $this->getPageDetails($recipientList->viewOnlinePage);
				}
			}
		}

		if ($category->viewOnlinePage > 0) {
			return $this->getPageDetails($category->viewOnlinePage);
		}

		return null;
	}


	/**
	 * Test if backend sending is allowed.
	 */
	public function allowBackendSending()
	{
		if ($GLOBALS['TL_CONFIG']['avisota_backend_send']) {
			if ($GLOBALS['TL_CONFIG']['avisota_backend_send'] == 'disabled') {
				return false;
			}
			if ($GLOBALS['TL_CONFIG']['avisota_disable_backend_send'] == 'admin' && !$this->User->admin) {
				return false;
			}
		}
		return true;
	}


	/**
	 * Extend the url to an absolute url.
	 */
	public function extendURL($url, $page = null, $category = null, $recipients = null)
	{
		if ($page == null) {
			$page = $this->getViewOnlinePage($category, $recipients);
		}

		return $this->DomainLink->absolutizeUrl($url, $page);
	}


	/**
	 * Get a dummy recipient array.
	 */
	public function getPreviewRecipient()
	{
		$this->loadLanguageFile('tl_avisota_newsletter');

		list($firstName, $lastName) = $this->splitFriendlyName($this->User->name);

		$recipient            = new AvisotaRecipient();
		$recipient->email     = $this->User->email;
		$recipient->firstname = $firstName;
		$recipient->lastname  = $lastName;
		$recipient->source    = '0';

		return $recipient;
	}


	/**
	 * Update missing informations to the recipient array.
	 *
	 * @param array $recipientData
	 *
	 * @return string The personalized state.
	 */
	public function finalizeRecipientArray(&$recipientData)
	{
		// set the firstname and lastname field if missing
		if (empty($recipientData['firstname']) && empty($recipientData['lastname']) && !empty($recipientData['name'])) {
			list($recipientData['firstname'], $recipientData['lastname']) = explode(' ', $recipientData['name'], 2);
		}

		// set the name field, if missing
		if (empty($recipientData['name']) && !(empty($recipientData['firstname']) && empty($recipientData['lastname']))) {
			$recipientData['name'] = trim($recipientData['firstname'] . ' ' . $recipientData['lastname']);
		}

		// set the fullname field, if missing
		if (empty($recipientData['fullname']) && !empty($recipientData['name'])) {
			$recipientData['fullname'] = trim($recipientData['title'] . ' ' . $recipientData['name']);
		}

		// set the shortname field, if missing
		if (empty($recipientData['shortname']) && !empty($recipientData['firstname'])) {
			$recipientData['shortname'] = $recipientData['firstname'];
		}

		// a recipient is anonymous, if he has no name
		if (!empty($recipientData['name'])) {
			$personalized = 'private';
		}
		else {
			$personalized = 'anonymous';
		}

		// extend with maybe missing anonymous informations
		$this->extendArray($GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous'], $recipientData);

		// update salutation
		if (empty($recipientData['salutation'])) {
			if (isset($GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_' . $recipientData['gender']])) {
				$recipientData['salutation'] = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_' . $recipientData['gender']];
			}
			else {
				$recipientData['salutation'] = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation'];
			}
		}

		// replace placeholders in salutation
		preg_match_all('#\{([^\}]+)\}#U', $recipientData['salutation'], $matches, PREG_SET_ORDER);
		foreach ($matches as $match) {
			$recipientData['salutation'] = str_replace($match[0], $recipientData[$match[1]], $recipientData['salutation']);
		}

		return $personalized;
	}


	/**
	 * Extend the target array with missing fields from the source array.
	 *
	 * @param array $source
	 * @param array $target
	 */
	public function extendArray($source, &$target)
	{
		if (is_array($source)) {
			foreach ($source as $k => $v) {
				if (!empty($v)
					&& empty($target[$k])
					&& !in_array(
						$k,
						array(
							// tl_avisota_recipient fields
							'id',
							'pid',
							'tstamp',
							'confirmed',
							'token',
							'addedOn',
							'addedBy',
							// tl_member fields
							'password',
							'session'
						)
					)
				) {
					$target[$k] = $v;
				}
			}
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
		foreach (array_reverse($this->Config->getActiveModules()) as $module) {
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

		// Add the module templates folders if they exist
		foreach ($this->Config->getActiveModules() as $moduleName) {
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

	public function getCurrentTransport()
	{
		$newsletter = AvisotaStatic::getNewsletter();
		$category   = AvisotaStatic::getCategory();

		if ($category && $category->transport && $category->setTransport == 'category') {
			$transportModuleId = $category->transport;
		}
		else if ($newsletter && $newsletter->transport) {
			$transportModuleId = $newsletter->transport;
		}
		else if ($category && $category->transport) {
			$transportModuleId = $category->transport;
		}
		else {
			$transportModuleId = 0;
		}

		return AvisotaTransport::getTransportModule($transportModuleId);
	}
}
