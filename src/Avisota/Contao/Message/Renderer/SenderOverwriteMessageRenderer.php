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

class SenderOverwriteMessageRenderer extends DelegateMessageRenderer
{
	/**
	 * @var string
	 */
	protected $sender;

	/**
	 * @var string
	 */
	protected $senderName;

	function __construct(MessageRendererInterface $delegate, $sender, $senderName)
	{
		parent::__construct($delegate);
		$this->sender     = (string) $sender;
		$this->senderName = (string) $senderName;
	}

	/**
	 * @param string $sender
	 */
	public function setSender($sender)
	{
		$this->sender = (string) $sender;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSender()
	{
		return $this->sender;
	}

	/**
	 * @param string $senderName
	 */
	public function setSenderName($senderName)
	{
		$this->senderName = (string) $senderName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSenderName()
	{
		return $this->senderName;
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderMessage(MessageInterface $message)
	{
		$swiftMessage = $this->delegate->renderMessage($message);

		$swiftMessage->setSender($this->sender, $this->senderName);

		return $swiftMessage;
	}
}
