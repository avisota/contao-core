<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Message\Renderer;

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
