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

namespace Avisota\Contao\Core\Salutation;

use Avisota\Contao\Entity\Salutation;
use Avisota\Contao\Entity\SalutationGroup;
use Avisota\Recipient\RecipientInterface;

class Selector
{
	/**
	 * @var DeciderInterface
	 */
	protected $decider;

	/**
	 * Set the salutation decider.
	 *
	 * @param \Avisota\Contao\Core\Salutation\DeciderInterface $decider
	 */
	public function setDecider(DeciderInterface $decider)
	{
		$this->decider = $decider;
		return $this;
	}

	/**
	 * Get the salutation decider.
	 *
	 * @return \Avisota\Contao\Core\Salutation\DeciderInterface
	 */
	public function getDecider()
	{
		return $this->decider;
	}

	/**
	 * Select a salutation from group.
	 *
	 * @param SalutationGroup $group
	 *
	 * @return null|Salutation
	 */
	public function selectSalutation(RecipientInterface $recipient, SalutationGroup $group)
	{
		$salutations = $group->getSalutations();

		foreach ($salutations as $salutation) {
			if ($this->decider->accept($recipient, $salutation)) {
				return $salutation;
			}
		}

		return null;
	}
}
