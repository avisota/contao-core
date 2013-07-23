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

namespace Avisota\Contao\DataContainer;

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
	 * @param \DataContainer $dc
	 */
	public function onload_callback($dc)
	{
	}

	/**
	 * @param \DataContainer $dc
	 */
	public function onsubmit_callback($dc)
	{
	}

	public function rememberAlias($alias, $dc)
	{
		$_SESSION['AVISOTA_QUEUE_ALIAS'][$dc->id] = $alias;
		return $alias;
	}


	/**
	 * Check permissions to edit table orm_avisota_transport
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// TODO
	}
}
