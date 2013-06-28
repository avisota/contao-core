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
 * Class Backend
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class Backend extends \Controller
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
			self::$instance = new Backend();
		}
		return self::$instance;
	}

	protected function __construct()
	{
		parent::__construct();
	}

	public function hookNestedMenuPreContent($do, $groups)
	{
		if ($do == 'avisota_config') {
			return sprintf(
				'<div class="avisota-logo"><a href="http://avisota.org" target="_blank">%s</a></div>',
				$this->generateImage('system/modules/avisota/assets/images/logo.png', 'Avisota Newsletter & Mailingsystem')
			);
		}
	}

	public function hookNestedMenuPostContent($do, $groups)
	{
		if ($do == 'avisota_config') {
			$config = \Config::getInstance();
			$this->loadLanguageFile('avisota_promotion');

			$template = new \TwigBackendTemplate('be_config_footer');
			$template->donate = $GLOBALS['TL_LANG']['avisota_promotion']['donate'];
			if (!in_array('avisota-business', $config->getActiveModules())) {
				$template->business = $GLOBALS['TL_LANG']['avisota_promotion']['business'];
			}
			$template->copyright = 'Avisota newsletter and mailing system &copy; 2013 bit3 UG and all <a href="https://github.com/avisota/contao/graphs/contributors" target="_blank">contributors</a>';
			$template->disclaimer = 'Avisota use icons from the <a href="http://www.famfamfam.com/" target="_blank">famfamfam silk icons</a> and <a href="http://www.picol.org/" target="_blank">Picol Vector icons</a>.';
			return $template->parse();
		}
	}

	public function regenerateDynamics()
	{
		$database = \Database::getInstance();
		$dynamics = array();

		foreach (
			array(
				'tl_avisota_mailing_list',
				'tl_avisota_recipient_source',
				'tl_avisota_newsletter_theme',
				'tl_avisota_transport',
				'tl_avisota_queue'
			) as $table
		) {
			$key = substr($table, 11);
			$resultSet = $database->query('SELECT * FROM ' . $table);
			while ($resultSet->next()) {
				$dynamics[$key][$resultSet->id] = $resultSet->alias;
			}
		}

		$array = var_export($dynamics, true);

		$fileContents = <<<EOF
<?php

return $array;


EOF;

		$tempFile = new \File('system/modules/avisota/config/dynamics.php');
		$tempFile->write($fileContents);
		$tempFile->close();
	}
}
