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

namespace Avisota\Contao\Core\Message\Renderer;

use Avisota\Message\MessageInterface;
use Avisota\Renderer\DelegateMessageRenderer;
use Avisota\Renderer\MessageRendererInterface;

/**
 * Class DelegateMessageRenderer
 *
 * Implementation of a delegate message renderer.
 * Primary used as base class for custom implementations.
 */
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

	/**
	 * DelegateMessageRenderer constructor.
	 *
	 * @param MessageRendererInterface $delegate
	 * @param                          $replyTo
	 * @param                          $replyToName
	 */
	function __construct(MessageRendererInterface $delegate, $replyTo, $replyToName)
	{
		parent::__construct($delegate);
		$this->replyTo     = (string) $replyTo;
		$this->replyToName = (string) $replyToName;
	}

	/**
	 * @param string $replyTo
	 *
	 * @return $this
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
	 *
	 * @return $this
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
	 * Render a message and create a Swift_Message.
	 *
	 * @param MessageInterface $message
	 *
	 * @return \Swift_Message
     */
	public function renderMessage(MessageInterface $message)
	{
		$swiftMessage = $this->delegate->renderMessage($message);

		$swiftMessage->setReplyTo($this->replyTo, $this->replyToName);

		return $swiftMessage;
	}
}
