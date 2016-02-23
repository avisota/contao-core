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


/**
 * Class AvisotaBlacklistException
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
class AvisotaBlacklistException extends Exception
{
	protected $email;

	protected $lists;

	public function __construct($email = null, array $lists = array(), $message = '', $code = 0, $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->email = $email;
		$this->lists = $lists;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getLists()
	{
		return $this->lists;
	}
}
