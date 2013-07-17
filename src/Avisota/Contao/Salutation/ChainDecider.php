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

namespace Avisota\Contao\Salutation;

use Avisota\Contao\Entity\Salutation;
use Avisota\Recipient\RecipientInterface;

class ChainDecider implements DeciderInterface
{
	/**
	 * @var DeciderInterface[]
	 */
	protected $deciders = array();

	public function accept(RecipientInterface $recipient, Salutation $salutation)
	{
		foreach ($this->deciders as $decider) {
			if (!$decider->accept($recipient, $salutation)) {
				return false;
			}
		}
		return true;
	}

	public function addDecider(DeciderInterface $decider)
	{
		$this->deciders[spl_object_hash($decider)] = $decider;
	}

	public function removeDecider(DeciderInterface $decider)
	{
		unset($this->deciders[spl_object_hash($decider)]);
	}
}
