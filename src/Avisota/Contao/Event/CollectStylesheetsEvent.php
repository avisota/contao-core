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

namespace Avisota\Contao\Event;

use Avisota\Contao\Message\Renderer;
use Symfony\Component\EventDispatcher\Event;

class CollectStylesheetsEvent extends Event
{
	/**
	 * @var \ArrayObject
	 */
	protected $stylesheets;

	function __construct(\ArrayObject $stylesheets)
	{
		$this->stylesheets = $stylesheets;
	}

	/**
	 * @return \ArrayObject
	 */
	public function getStylesheets()
	{
		return $this->stylesheets;
	}
}