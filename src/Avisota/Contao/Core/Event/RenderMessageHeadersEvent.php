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

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Core\Message\Renderer;
use Symfony\Component\EventDispatcher\Event;

class RenderMessageHeadersEvent extends Event
{
	const NAME = 'Avisota\Contao\Core\Event\RenderMessageHeaders';

	/**
	 * @var Renderer
	 */
	protected $renderer;

	/**
	 * @var Message
	 */
	protected $message;

	/**
	 * @var \ArrayObject
	 */
	protected $headers;

	function __construct($renderer, $message, $headers)
	{
		$this->renderer = $renderer;
		$this->message  = $message;
		$this->headers  = $headers;
	}

	/**
	 * @return \Avisota\Contao\Core\Message\Renderer
	 */
	public function getRenderer()
	{
		return $this->renderer;
	}

	/**
	 * @return \Avisota\Contao\Entity\Message
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @return \ArrayObject
	 */
	public function getHeaders()
	{
		return $this->headers;
	}
}