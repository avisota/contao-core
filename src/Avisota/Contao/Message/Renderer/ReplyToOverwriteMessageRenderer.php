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

class ReplyToOverwriteMessageRenderer extends DelegateMessageRenderer
{
	/**
	 * @var string
	 */
	protected $replyTo;

	/**
	 * @var string
	 */
	protected $replyToName;

	function __construct(MessageRendererInterface $delegate, $replyTo, $replyToName)
	{
		parent::__construct($delegate);
		$this->replyTo     = (string) $replyTo;
		$this->replyToName = (string) $replyToName;
	}

	/**
	 * @param string $replyTo
	 */
	public function setReplyTo($replyTo)
	{
		$this->replyTo = (string) $replyTo;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getReplyTo()
	{
		return $this->replyTo;
	}

	/**
	 * @param string $replyToName
	 */
	public function setReplyToName($replyToName)
	{
		$this->replyToName = (string) $replyToName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getReplyToName()
	{
		return $this->replyToName;
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderMessage(MessageInterface $message)
	{
		$swiftMessage = $this->delegate->renderMessage($message);

		$swiftMessage->setReplyTo($this->replyTo, $this->replyToName);

		return $swiftMessage;
	}
}
