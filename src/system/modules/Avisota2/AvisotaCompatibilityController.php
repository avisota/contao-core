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


define('TL_MODE', 'BE');
include('../../initialize.php');

/**
 * Class AlphaController
 */
class AvisotaCompatibilityController extends Backend
{
	/**
	 * @var Config
	 */
	protected $Config;

	/**
	 * Initialize the controller.
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		// load default translations
		$this->loadLanguageFile('default');
		$this->loadLanguageFile('avisotaCompatibilityController');
	}

	/**
	 * Run the controller.
	 */
	public function run()
	{
		// user have to be authenticated
		$this->User->authenticate();

		// disable Avisota
		if ($this->Input->get('disable')) {
			$inactiveModules   = deserialize($GLOBALS['TL_CONFIG']['inactiveModules'], true);
			$inactiveModules[] = 'Avisota';
			$this->Config->update("\$GLOBALS['TL_CONFIG']['inactiveModules']", serialize($inactiveModules));
			$this->Config->save();
			$_SESSION[TL_INFO][] = $GLOBALS['TL_LANG']['avisotaCompatibilityController']['disabled'];
			$this->redirect('contao/main.php');
		}

		$template               = new BackendTemplate('be_avisota_compatibility_controller');
		$template->theme        = $this->getTheme();
		$template->base         = $this->Environment->base;
		$template->language     = $GLOBALS['TL_LANGUAGE'];
		$template->title        = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$template->charset      = $GLOBALS['TL_CONFIG']['characterSet'];
		$template->request      = ampersand($this->Environment->request);
		$template->top          = $GLOBALS['TL_LANG']['MSC']['backToTop'];
		$template->mysqlVersion = $this->Database->query('SHOW VARIABLES WHERE Variable_name = \'version\'')->Value;
		$template->output();
	}
}

$controller = new AvisotaCompatibilityController();
$controller->run();
