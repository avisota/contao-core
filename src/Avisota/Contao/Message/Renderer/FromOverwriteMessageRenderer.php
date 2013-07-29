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

namespace Avisota\Contao\Message\Renderer;

use Avisota\Message\MessageInterface;
use Avisota\Renderer\DelegateMessageRenderer;
use Avisota\Renderer\MessageRendererInterface;

class FromOverwriteMessageRenderer extends DelegateMessageRenderer
{
	/**
	 * @var string
	 */
	protected $from;

	/**
	 * @var string
	 */
	protected $fromName;

	function __construct(MessageRendererInterface $delegate, $from, $fromName)
	{
		parent::__construct($delegate);
		$this->from     = (string) $from;
		$this->fromName = (string) $fromName;
	}

	/**
	 * @param string $from
	 */
	public function setFrom($from)
	{
		$this->from = (string) $from;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * @param string $fromName
	 */
	public function setFromName($fromName)
	{
		$this->fromName = (string) $fromName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFromName()
	{
		return $this->fromName;
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderMessage(MessageInterface $message)
	{
		$swiftMessage = $this->delegate->renderMessage($message);

		$swiftMessage->setFrom($this->from, $this->fromName);

		return $swiftMessage;
	}
}
