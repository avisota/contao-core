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

use Avisota\Contao\Message\Renderer\MessageRendererInterface;
use Symfony\Component\EventDispatcher\Event;

class InitializeMessageRendererEvent extends Event
{
	/**
	 * @var MessageRendererInterface
	 */
	protected $renderer;

	function __construct(MessageRendererInterface $renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * @return MessageRendererInterface
	 */
	public function getRenderer()
	{
		return $this->renderer;
	}
}