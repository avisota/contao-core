<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
	private static $objInstance = null;


	/**
	 * Get singleton instance.
	 */
	public static function getInstance()
	{
		if (self::$objInstance === null)
		{
			self::$objInstance = new AvisotaBase();
		}
		return self::$objInstance;
	}


	/**
	 * Singleton
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('AvisotaStatic', 'Static');
		if (TL_MODE == 'FE')
		{
			$this->import('FrontendUser', 'User');
		}
		else
		{
			$this->import('BackendUser', 'User');
			$this->User->authenticate();
		}
		$this->import('Database');
		$this->import('DomainLink');
	}


	public function getViewOnlinePage($objCategory = null, $arrRecipient = null)
	{
		if (is_null($objCategory))
		{
			$objCategory = $this->Static->getCategory();
		}

		if (is_null($arrRecipient))
		{
			$arrRecipient = $this->Static->getRecipient();
		}

		if ($arrRecipient && preg_match('#^list:(\d+)$#', $arrRecipient['outbox_source'], $arrMatch))
		{
			// the dummy list, used on preview
			if ($arrMatch[1] > 0)
			{
				$objRecipientList = $this->Database->prepare("
						SELECT
							*
						FROM
							`tl_avisota_mailing_list`
						WHERE
							`id`=?")
					->execute($arrMatch[1]);
				if ($objRecipientList->next())
				{
					return $this->getPageDetails($objRecipientList->viewOnlinePage);
				}
			}
		}

		if ($objCategory->viewOnlinePage > 0)
		{
			return $this->getPageDetails($objCategory->viewOnlinePage);
		}

		return null;
	}


	/**
	 * Test if backend sending is allowed.
	 */
	public function allowBackendSending()
	{
		if ($GLOBALS['TL_CONFIG']['avisota_backend_send'])
		{
			if ($GLOBALS['TL_CONFIG']['avisota_backend_send'] == 'disabled')
			{
				return false;
			}
			if ($GLOBALS['TL_CONFIG']['avisota_disable_backend_send'] == 'admin' && !$this->User->admin)
			{
				return false;
			}
		}
		return true;
	}


	/**
	 * Extend the url to an absolute url.
	 */
	public function extendURL($strUrl, $objPage = null, $objCategory = null, $arrRecipient = null)
	{
		if ($objPage == null)
		{
			$objPage = $this->getViewOnlinePage($objCategory, $arrRecipient);
		}

		return $this->DomainLink->absolutizeUrl($strUrl, $objPage);
	}


	/**
	 * Get a dummy recipient array.
	 */
	public function getPreviewRecipient()
	{
		$this->loadLanguageFile('tl_avisota_newsletter');

		list($strFirstName, $strLastName) = $this->splitFriendlyName($this->User->name);

		$objRecipient            = new AvisotaRecipient();
		$objRecipient->email     = $this->User->email;
		$objRecipient->firstname = $strFirstName;
		$objRecipient->lastname  = $strLastName;
		$objRecipient->source    = '0';

		return $objRecipient;
	}


	/**
	 * Update missing informations to the recipient array.
	 *
	 * @param array $arrRecipient
	 * @return string The personalized state.
	 */
	public function finalizeRecipientArray(&$arrRecipient)
	{
		// set the firstname and lastname field if missing
		if (empty($arrRecipient['firstname']) && empty($arrRecipient['lastname']) && !empty($arrRecipient['name']))
		{
			list($arrRecipient['firstname'], $arrRecipient['lastname']) = explode(' ', $arrRecipient['name'], 2);
		}

		// set the name field, if missing
		if (empty($arrRecipient['name']) && !(empty($arrRecipient['firstname']) && empty($arrRecipient['lastname'])))
		{
			$arrRecipient['name'] = trim($arrRecipient['firstname'] . ' ' . $arrRecipient['lastname']);
		}

		// set the fullname field, if missing
		if (empty($arrRecipient['fullname']) && !empty($arrRecipient['name']))
		{
			$arrRecipient['fullname'] = trim($arrRecipient['title'] . ' ' . $arrRecipient['name']);
		}

		// set the shortname field, if missing
		if (empty($arrRecipient['shortname']) && !empty($arrRecipient['firstname']))
		{
			$arrRecipient['shortname'] = $arrRecipient['firstname'];
		}

		// a recipient is anonymous, if he has no name
		if (!empty($arrRecipient['name']))
		{
			$personalized = 'private';
		}
		else
		{
			$personalized = 'anonymous';
		}

		// extend with maybe missing anonymous informations
		$this->extendArray($GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous'], $arrRecipient);

		// update salutation
		if (empty($arrRecipient['salutation']))
		{
			if (isset($GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_' . $arrRecipient['gender']]))
			{
				$arrRecipient['salutation'] = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_' . $arrRecipient['gender']];
			}
			else
			{
				$arrRecipient['salutation'] = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation'];
			}
		}

		// replace placeholders in salutation
		preg_match_all('#\{([^\}]+)\}#U', $arrRecipient['salutation'], $matches, PREG_SET_ORDER);
		foreach ($matches as $match)
		{
			$arrRecipient['salutation'] = str_replace($match[0], $arrRecipient[$match[1]], $arrRecipient['salutation']);
		}

		return $personalized;
	}


	/**
	 * Extend the target array with missing fields from the source array.
	 *
	 * @param array $arrSource
	 * @param array $arrTarget
	 */
	public function extendArray($arrSource, &$arrTarget)
	{
		if (is_array($arrSource))
		{
			foreach ($arrSource as $k=>$v)
			{
				if (   !empty($v)
					&& empty($arrTarget[$k])
					&& !in_array($k, array(
						// tl_avisota_recipient fields
						'id', 'pid', 'tstamp', 'confirmed', 'token', 'addedOn', 'addedBy',
						// tl_member fields
						'password', 'session')))
				{
					$arrTarget[$k] = $v;
				}
			}
		}
	}

	/**
	 * Find a particular template file and return its path
	 *
	 * @author     Leo Feyer <http://www.contao.org>
	 * @see Controll::getTemplate in Contao OpenSource CMS
	 * @param string
	 * @param string
	 * @return string
	 * @throws Exception
	 */
	public function getTemplate($strTemplate, $strFormat='html5')
	{
		$strTemplate = basename($strTemplate);
		$strFilename = $strTemplate . '.html5';

		/** @var AvisotaNewsletter $objNewsletter */
		global $objNewsletter;

		// Check for a theme folder
		if ($objNewsletter) {
			$strTemplateGroup = $objNewsletter->getTheme()->getTemplateDirectory();
		} else {
			$strTemplateGroup = '';
		}

		$strPath = TL_ROOT . '/templates';

		// Check the theme folder first
		if (TL_MODE == 'FE' && $strTemplateGroup != '')
		{
			$strFile = $strPath . '/' . $strTemplateGroup . '/' . $strFilename;

			if (file_exists($strFile))
			{
				return $strFile;
			}

			// Also check for .tpl files (backwards compatibility)
			$strFile = $strPath . '/' . $strTemplateGroup . '/' . $strTemplate . '.tpl';

			if (file_exists($strFile))
			{
				return $strFile;
			}
		}

		// Then check the global templates directory
		$strFile = $strPath . '/' . $strFilename;

		if (file_exists($strFile))
		{
			return $strFile;
		}

		// Also check for .tpl files (backwards compatibility)
		$strFile = $strPath . '/' . $strTemplate . '.tpl';

		if (file_exists($strFile))
		{
			return $strFile;
		}

		// At last browse all module folders in reverse order
		foreach (array_reverse($this->Config->getActiveModules()) as $strModule)
		{
			$strFile = TL_ROOT . '/system/modules/' . $strModule . '/templates/' . $strFilename;

			if (file_exists($strFile))
			{
				return $strFile;
			}

			// Also check for .tpl files (backwards compatibility)
			$strFile = TL_ROOT . '/system/modules/' . $strModule . '/templates/' . $strTemplate . '.tpl';

			if (file_exists($strFile))
			{
				return $strFile;
			}
		}

		throw new Exception('Could not find template file "' . $strFilename . '"');
	}


	/**
	 * Return all template files of a particular group as array
	 *
	 * @author     Leo Feyer <http://www.contao.org>
	 * @see Controll::getTemplate in Contao OpenSource CMS
	 * @param string
	 * @param integer
	 * @return array
	 * @throws Exception
	 */
	protected function getTemplateGroup($strPrefix, $intTheme=0)
	{
		$arrFolders = array();
		$arrTemplates = array();

		// Add the templates root directory
		$arrFolders[] = TL_ROOT . '/templates';

		// Add the theme templates folder
		if ($intTheme > 0)
		{
			$objTheme = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_theme WHERE id=?")
									   ->limit(1)
									   ->execute($intTheme);

			if ($objTheme->numRows > 0 && $objTheme->templateDirectory != '')
			{
				$arrFolders[] = TL_ROOT .'/'. $objTheme->templateDirectory;
			}
		}

		// Add the module templates folders if they exist
		foreach ($this->Config->getActiveModules() as $strModule)
		{
			$strFolder = TL_ROOT . '/system/modules/' . $strModule . '/templates';

			if (is_dir($strFolder))
			{
				$arrFolders[] = $strFolder;
			}
		}

		// Find all matching templates
		foreach ($arrFolders as $strFolder)
		{
			$arrFiles = preg_grep('/^' . preg_quote($strPrefix, '/') . '/i',  scan($strFolder));

			foreach ($arrFiles as $strTemplate)
			{
				$strName = basename($strTemplate);
				$arrTemplates[] = substr($strName, 0, strrpos($strName, '.'));
			}
		}

		natcasesort($arrTemplates);
		$arrTemplates = array_values(array_unique($arrTemplates));

		return $arrTemplates;
	}

	public function getCurrentTransport()
	{
		$objNewsletter = AvisotaStatic::getNewsletter();
		$objCategory = AvisotaStatic::getCategory();

		if ($objCategory && $objCategory->transport && $objCategory->setTransport == 'category') {
			$intId = $objCategory->transport;
		}
		else if ($objNewsletter && $objNewsletter->transport) {
			$intId = $objNewsletter->transport;
		}
		else if ($objCategory && $objCategory->transport) {
			$intId = $objCategory->transport;
		}
		else {
			$intId = 0;
		}

		return AvisotaTransport::getTransportModule($intId);
	}
}
