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
use Avisota\Contao\Core\Message\ContaoAwareNativeMessage;
use Avisota\Contao\Core\Message\PreRenderedMessageTemplateInterface;
use Avisota\Recipient\RecipientInterface;
use Symfony\Component\EventDispatcher\Event;

class PostRenderMessageTemplateEvent extends Event
{
	const NAME = 'avisota.contao.post-render-message-template';

	/**
	 * @var Message
	 */
	protected $contaoMessage;

	/**
	 * @var PreRenderedMessageTemplateInterface
	 */
	protected $messageTemplate;

	/**
	 * @var RecipientInterface
	 */
	protected $recipient;

	/**
	 * @var array
	 */
	protected $additionalData;

	/**
	 * @var ContaoAwareNativeMessage
	 */
	protected $message;

	function __construct(
		Message $contaoMessage,
		PreRenderedMessageTemplateInterface $messageTemplate,
		RecipientInterface $recipient = null,
		array $additionalData = array(),
		ContaoAwareNativeMessage $message
	) {
		$this->contaoMessage   = $contaoMessage;
		$this->messageTemplate = $messageTemplate;
		$this->recipient       = $recipient;
		$this->additionalData  = $additionalData;
		$this->message         = $message;
	}

	/**
	 * @return Message
	 */
	public function getContaoMessage()
	{
		return $this->contaoMessage;
	}

	/**
	 * @return PreRenderedMessageTemplateInterface
	 */
	public function getMessageTemplate()
	{
		return $this->messageTemplate;
	}

	/**
	 * @return RecipientInterface
	 */
	public function getRecipient()
	{
		return $this->recipient;
	}

	/**
	 * @return array
	 */
	public function getAdditionalData()
	{
		return $this->additionalData;
	}

	/**
	 * @return ContaoAwareNativeMessage
	 */
	public function getMessage()
	{
		return $this->message;
	}
}