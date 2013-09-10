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

namespace Avisota\Contao\Message\Renderer\Backend;

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Entity\MessageContent;
use Avisota\Contao\Event\InitializeMessageRendererEvent;
use Avisota\Contao\Event\RenderMessageHeadersEvent;
use Avisota\Contao\Message\Renderer\MessageContentPreRendererChain;
use Avisota\Contao\Message\Renderer\MessageContentPreRendererInterface;
use Avisota\Contao\Message\Renderer\MessagePreRendererInterface;
use Avisota\Recipient\RecipientInterface;
use Bit3\TagReplacer\TagReplacer;
use Contao\Doctrine\ORM\EntityHelper;

class MessagePreRenderer implements MessagePreRendererInterface
{
	/**
	 * @var TagReplacer
	 */
	protected $tagReplacer;

	/**
	 * @var MessageContentRendererInterface
	 */
	protected $contentRenderer = null;

	function __construct()
	{
		$this->tagReplacer = new TagReplacer(TagReplacer::FLAG_ENABLE_ALL_INTERNALS);

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];
		$eventDispatcher->dispatch(InitializeMessageRendererEvent::NAME, new InitializeMessageRendererEvent($this));

		$this->tagReplacer->setUnknownDefaultMode(TagReplacer::MODE_SKIP);
		$this->tagReplacer->setUnknownTagMode(TagReplacer::MODE_SKIP);
		$this->tagReplacer->setUnknownTokenMode(TagReplacer::MODE_SKIP);
	}

	/**
	 * @return TagReplacer
	 */
	public function getTagReplacer()
	{
		return $this->tagReplacer;
	}

	/**
	 * @return mixed
	 */
	public function getContentRenderer()
	{
		if (!$this->contentRenderer) {
			$this->contentRenderer = new MessageContentPreRendererChain($GLOBALS['AVISOTA_CONTENT_RENDERER']['backend']);
		}
		return $this->contentRenderer;
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderMessage(Message $message)
	{
		throw new \RuntimeException('This renderer cannot render a complete message');
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderContent(MessageContent $content)
	{
		return $this->getContentRenderer()->renderContent($content, $recipient);
	}

	/**
	 * {@inheritdoc}
	 */
	public function canRenderMessage(Message $message)
	{
		return TL_MODE == 'BE';
	}

	/**
	 * {@inheritdoc}
	 */
	public function canRenderContent(MessageContent $content)
	{
		return TL_MODE == 'BE';
	}
}
