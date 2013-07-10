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

namespace Avisota\Contao\DataContainer;

class Transport extends \Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
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
