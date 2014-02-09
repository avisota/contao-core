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
use Avisota\Contao\Core\Message\PreRenderedMessageTemplateInterface;
use Avisota\Recipient\RecipientInterface;
use Symfony\Component\EventDispatcher\Event;

class PostRenderMessageTemplatePreviewEvent extends Event
{
	const NAME = 'avisota.contao.post-render-message-template-preview';

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
	 * @var string
	 */
	protected $preview;

	function __construct(
		Message $contaoMessage,
		PreRenderedMessageTemplateInterface $messageTemplate,
		RecipientInterface $recipient = null,
		array $additionalData = array(),
		$preview
	) {
		$this->contaoMessage   = $contaoMessage;
		$this->messageTemplate = $messageTemplate;
		$this->recipient       = $recipient;
		$this->additionalData  = $additionalData;
		$this->preview         = $preview;
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
	 * @param string $preview
	 */
	public function setPreview($preview)
	{
		$this->preview = $preview;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPreview()
	{
		return $this->preview;
	}
}