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

namespace Avisota\Contao\Entity;

use Avisota\Recipient\RecipientInterface;
use Contao\Doctrine\ORM\Entity;

abstract class AbstractRecipient
	extends Entity
	implements RecipientInterface
{
	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var \DateTime
	 */
	protected $addedOn;

	function __construct()
	{
		$this->addedOn = new \DateTime();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = strtolower($email);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasDetails()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($name)
	{
		return $this->__get($name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDetails()
	{
		return $this->toArray();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getKeys()
	{
		$reflectionClass = new \ReflectionClass($this);
		$properties = $reflectionClass->getProperties();
		$keys = array();
		foreach ($properties as $property) {
			$keys[] = $property->getName();
		}
		return $keys;
	}
}
