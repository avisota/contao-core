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

namespace Avisota\Contao\Entity;

use Contao\Doctrine\ORM\Entity;

abstract class AbstractRecipient extends Entity
{
	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = strtolower($email);
	}
}
