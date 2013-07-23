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

	public function regenerateDynamics()
	{
		$database = \Database::getInstance();
		$dynamics = array();

		foreach (
			array(
				'orm_avisota_mailing_list',
				'orm_avisota_recipient_source',
				'orm_avisota_theme',
				'orm_avisota_transport',
				'orm_avisota_queue'
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
