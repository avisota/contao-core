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

namespace Avisota\Contao\Core\Event;

/**
 *
 */
class CreateOptionsEvent extends \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent
{
	protected $preventDefault = false;

	/**
	 * Prevent default listeners.
	 *
	 * @return $this
	 */
	public function preventDefault()
	{
		$this->preventDefault = true;
		return $this;
	}

	/**
	 * Determine if default should prevented.
	 *
	 * @return bool
	 */
	public function isDefaultPrevented()
	{
		return $this->preventDefault;
	}
}
