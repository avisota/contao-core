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

use Avisota\Contao\Message\Renderer\MessageContentRendererInterface;
use Symfony\Component\EventDispatcher\Event;

class InitializeMessageContentRendererEvent extends Event
{
	/**
	 * @var MessageContentRendererInterface
	 */
	protected $renderer;

	function __construct(MessageContentRendererInterface $renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * @return MessageContentRendererInterface
	 */
	public function getRenderer()
	{
		return $this->renderer;
	}
}