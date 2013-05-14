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

namespace Avisota\DataContainer;

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
	 * Check permissions to edit table tl_avisota_transport
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// TODO
	}

	/**
	 * Autogenerate a news alias if it has not been set yet
	 *
	 * @param mixed $value
	 * @param \DataContainer $dc
	 *
	 * @return string
	 */
	public function generateAlias($value, $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($value)) {
			$autoAlias = true;
			$value  = standardize($dc->activeRecord->title);
		}

		$aliasResultSet = $this->Database
			->prepare("SELECT id FROM tl_avisota_transport WHERE alias=?")
			->execute($value);

		// Check whether the news alias exists
		if ($aliasResultSet->numRows > 1 && !$autoAlias) {
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $value));
		}

		// Add ID to alias
		if ($aliasResultSet->numRows && $autoAlias) {
			$value .= '-' . $dc->id;
		}

		return $value;
	}
}
