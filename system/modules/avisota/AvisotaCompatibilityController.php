<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
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
		$template->mysqlVersion = \Database::getInstance()->query('SHOW VARIABLES WHERE Variable_name = \'version\'')->Value;
		$template->output();
	}
}

$controller = new AvisotaCompatibilityController();
$controller->run();
