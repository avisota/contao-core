<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\DataContainer;

use ContaoCommunityAlliance\DcGeneral\DC_General;

class Queue extends \Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param string $alias
	 * @param DC_General $dc
	 *
	 * @return mixed
	 */
	public function rememberAlias($alias, $dc)
	{
		$_SESSION['AVISOTA_QUEUE_ALIAS'][$dc->id] = $alias;
		return $alias;
	}
}
