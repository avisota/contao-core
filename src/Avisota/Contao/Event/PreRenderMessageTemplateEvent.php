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

namespace Avisota\Contao\Event;

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Message\PreRenderedMessageTemplateInterface;
use Avisota\Recipient\RecipientInterface;
use Symfony\Component\EventDispatcher\Event;

class PreRenderMessageTemplateEvent extends Event
{
	const NAME = 'avisota.contao.pre-render-message-template';

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

	function __construct(
		Message $contaoMessage,
		PreRenderedMessageTemplateInterface $messageTemplate,
		RecipientInterface $recipient = null,
		array $additionalData = array()
	) {
		$this->contaoMessage   = $contaoMessage;
		$this->messageTemplate = $messageTemplate;
		$this->recipient       = $recipient;
		$this->additionalData  = $additionalData;
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
	 * @param array $additionalData
	 */
	public function setAdditionalData($additionalData)
	{
		$this->additionalData = $additionalData;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getAdditionalData()
	{
		return $this->additionalData;
	}
}