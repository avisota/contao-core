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

namespace Avisota\Contao\Message;

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Entity\MessageContent;
use Avisota\Contao\ReplaceInsertTagsHook;
use Avisota\Message\NativeMessage;
use Avisota\Recipient\RecipientInterface;
use Bit3\TagReplacer\TagReplacer;

abstract class AbstractPostRenderingMessageTemplate implements PreRenderedMessageTemplateInterface
{
	/**
	 * @var Message
	 */
	protected $message;

	protected function __construct(Message $message)
	{
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	protected function parseContent(RecipientInterface $recipient, array $additionalData = array())
	{
		$content = $this->getContent();

		if (is_string($content)) {
			$replaceInsertTagsHook = new ReplaceInsertTagsHook();

			$tagReplacer = new TagReplacer(TagReplacer::FLAG_ENABLE_ALL_INTERNALS);
			$tagReplacer->setUnknownDefaultMode(TagReplacer::MODE_SKIP);
			$tagReplacer->setTokens($additionalData);
			$tagReplacer->setToken('message', $this->message);
			$tagReplacer->setToken('recipient', $recipient);
			$content = $tagReplacer->replace(
				$content,
				function($tag) use ($replaceInsertTagsHook) {
					return $replaceInsertTagsHook->replaceInsertTags('{{' . $tag . '}}');
				}
			);
		}

		return $content;
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderPreview(RecipientInterface $recipient, array $additionalData = array())
	{
		return $this->parseContent($recipient, $additionalData);
	}

	/**
	 * {@inheritdoc}
	 */
	public function render(RecipientInterface $recipient = null, array $additionalData = array())
	{
		$content = $this->parseContent($recipient, $additionalData);

		$swiftMessage = new \Swift_Message();

		$name = trim($recipient->get('forename') . ' ' . $recipient->get('surname'));

		$swiftMessage->setTo($recipient->getEmail(), $name);
		$swiftMessage->setSubject($this->message->getSubject());
		$swiftMessage->setBody($content, $this->getContentType(), $this->getContentEncoding());
		$swiftMessage->setDescription($this->message->getDescription());

		return new NativeMessage($swiftMessage);
	}
}
