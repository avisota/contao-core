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

class InitializeRendererEvent extends Event
{
	/**
	 * @var \Avisota\Contao\Message\Renderer
	 */
	protected $renderer;

	function __construct(Renderer $renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * @return \Avisota\Contao\Message\Renderer
	 */
	public function getRenderer()
	{
		return $this->renderer;
	}
}