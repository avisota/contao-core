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

namespace Avisota\RecipientSource;

/**
 * Class AvisotaRecipientSource
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
interface RecipientSourceInterface
{
	/**
	 * Get all selectable recipient options for this source.
	 * Every option can be an individuell ID.
	 *
	 * @abstract
	 * @return array
	 */
	public function getRecipientOptions();

	/**
	 * Get recipient IDs of a list of options.
	 *
	 * @abstract
	 *
	 * @param array $varOption
	 *
	 * @return array
	 */
	public function getRecipients($options);

	/**
	 * Get the recipient details.
	 *
	 * @param string $id
	 *
	 * @return array
	 */
	public function getRecipientDetails($id);
}
