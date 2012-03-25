<?php if (defined('TL_ROOT')) die('You can not access this file via contao!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


// run in FE mode
define('TL_MODE', 'FE');

// Define the static URL constants
define('TL_FILES_URL', '');
define('TL_SCRIPT_URL', '');
define('TL_PLUGINS_URL', '');

// initialize contao
include('../../initialize.php');

/**
 * Class AvisotaPreview
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaPreview extends Backend
{
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();
		$this->import('AvisotaBase', 'Base');
		$this->import('AvisotaContent', 'Content');
		$this->import('AvisotaStatic', 'Static');

		// force all URLs absolute
		$GLOBALS['TL_CONFIG']['forceAbsoluteDomainLink'] = true;

		// load default translations
		$this->loadLanguageFile('default');

		// HOTFIX Remove isotope frontend hook
		if (isset($GLOBALS['TL_HOOKS']['parseTemplate']) && is_array($GLOBALS['TL_HOOKS']['parseTemplate'])) {
			foreach ($GLOBALS['TL_HOOKS']['parseTemplate'] as $k=>$v) {
				if ($v[0] == 'IsotopeFrontend') {
					unset($GLOBALS['TL_HOOKS']['parseTemplate'][$k]);
				}
			}
		}
	}

	public function run()
	{
		// user have to be authenticated
		$this->User->authenticate();

		// read session data
		$arrSession = $this->Session->get('AVISOTA_PREVIEW');

		// get preview mode
		if ($this->Input->get('mode'))
		{
			$arrSession['mode'] = $this->Input->get('mode');
		}

		// fallback preview mode
		if (!$arrSession['mode'])
		{
			$arrSession['mode'] = NL_HTML;
		}

		// get personalized state
		if ($this->Input->get('personalized'))
		{
			$arrSession['personalized'] = $this->Input->get('personalized');
		}

		// fallback personalized state
		if (!$arrSession['personalized'])
		{
			$arrSession['personalized'] = 'anonymous';
		}

		// store session data
		$this->Session->set('AVISOTA_PREVIEW', $arrSession);

		// find the newsletter
		$intId = $this->Input->get('id');

		$objNewsletter = $this->Database->prepare("
						SELECT
							*
						FROM
							tl_avisota_newsletter
						WHERE
							id=?")
		->execute($intId);

		if (!$objNewsletter->next())
		{
			$this->redirect('contao/main.php?act=error');
		}

		// find the newsletter category
		$objCategory = $this->Database->prepare("
						SELECT
							*
						FROM
							tl_avisota_newsletter_category
						WHERE
							id=?")
		->execute($objNewsletter->pid);

		if (!$objCategory->next())
		{
			$this->redirect('contao/main.php?act=error');
		}

		// build the recipient data array
		$arrRecipient = $this->Base->getPreviewRecipient($arrSession['personalized']);

		$this->Static->set($objCategory, $objNewsletter, $arrRecipient);

		// generate the preview
		switch ($arrSession['mode'])
		{
			case NL_HTML:
				header('Content-Type: text/html; charset=utf-8');
				echo $this->replaceInsertTags($this->Content->prepareBeforeSending($this->Content->generateHtml($objNewsletter, $objCategory, $arrSession['personalized'])));
				exit(0);

			case NL_PLAIN:
				header('Content-Type: text/plain; charset=utf-8');
				echo $this->replaceInsertTags($this->Content->prepareBeforeSending($this->Content->generatePlain($objNewsletter, $objCategory, $arrSession['personalized'])));
				exit(0);

			default:
				$this->redirect('contao/main.php?act=error');
		}
	}
}

try {
	$objAvisotaPreview = new AvisotaPreview();
	$objAvisotaPreview->run();
} catch(Exception $e) {
	header('HTTP/1.0 500 Internal Server Error');
	header('Content-Type: text/plain');
	echo $e->getMessage();
	echo "\n";
	echo $e->getTraceAsString();
}
