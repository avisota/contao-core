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


/**
 * Class AvisotaBase
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
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
							`orm_avisota_mailing_list`
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
		$this->loadLanguageFile('orm_avisota_message');

		list($forename, $surname) = $this->splitFriendlyName($this->User->name);

		$recipient            = new AvisotaRecipient();
		$recipient->email     = $this->User->email;
		$recipient->forename = $forename;
		$recipient->surname  = $surname;
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
		// set the forename and surname field if missing
		if (empty($recipientData['forename']) && empty($recipientData['surname']) && !empty($recipientData['name'])) {
			list($recipientData['forename'], $recipientData['surname']) = explode(' ', $recipientData['name'], 2);
		}

		// set the name field, if missing
		if (empty($recipientData['name']) && !(empty($recipientData['forename']) && empty($recipientData['surname']))) {
			$recipientData['name'] = trim($recipientData['forename'] . ' ' . $recipientData['surname']);
		}

		// set the fullname field, if missing
		if (empty($recipientData['fullname']) && !empty($recipientData['name'])) {
			$recipientData['fullname'] = trim($recipientData['title'] . ' ' . $recipientData['name']);
		}

		// set the shortname field, if missing
		if (empty($recipientData['shortname']) && !empty($recipientData['forename'])) {
			$recipientData['shortname'] = $recipientData['forename'];
		}

		// a recipient is anonymous, if he has no name
		if (!empty($recipientData['name'])) {
			$personalized = 'private';
		}
		else {
			$personalized = 'anonymous';
		}

		// extend with maybe missing anonymous informations
		$this->extendArray($GLOBALS['TL_LANG']['orm_avisota_message']['anonymous'], $recipientData);

		// update salutation
		if (empty($recipientData['salutation'])) {
			if (isset($GLOBALS['TL_LANG']['orm_avisota_message']['salutation_' . $recipientData['gender']])) {
				$recipientData['salutation'] = $GLOBALS['TL_LANG']['orm_avisota_message']['salutation_' . $recipientData['gender']];
			}
			else {
				$recipientData['salutation'] = $GLOBALS['TL_LANG']['orm_avisota_message']['salutation'];
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
							// orm_avisota_recipient fields
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
				->prepare("SELECT * FROM orm_avisota_message_theme WHERE id=?")
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
