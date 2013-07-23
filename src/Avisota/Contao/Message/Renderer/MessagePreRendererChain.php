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

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Entity\MessageContent;
use Avisota\Recipient\RecipientInterface;

class MessagePreRendererChain implements MessagePreRendererInterface
{
	protected $messageRendererChain = array();

	function __construct(array $messageRenderers = null)
	{
		if ($messageRenderers) {
			foreach ($messageRenderers as $messageRenderer) {
				if (is_array($messageRenderer)) {
					list($messageRenderer, $priority) = $messageRenderer;
				}
				else {
					$priority = 0;
				}
				if (is_string($messageRenderer)) {
					$childRendererClass = new \ReflectionClass($messageRenderer);
					$childRendererInstance = $childRendererClass->newInstance();
					$this->addRenderer($childRendererInstance, $priority);
				}
				else if ($messageRenderer instanceof MessagePreRendererInterface) {
					$this->addRenderer($childRendererInstance, $priority);
				}
				else {
					throw new \RuntimeException('Illegal message renderer ' . (is_object($messageRenderer) ? get_class(
						$messageRenderer
					) : $messageRenderer));
				}
			}
		}
	}

	/**
	 * Add a new renderer to this renderer chain.
	 *
	 * @param MessageRendererInterface $renderer The message renderer to add.
	 * @param int                      $priority Higher value means, that the renderer is more prefered.
	 */
	public function addRenderer(MessagePreRendererInterface $renderer, $priority = 0)
	{
		$this->removeRenderer($renderer);

		$hash = spl_object_hash($renderer);

		if (!isset($this->messageRendererChain[$priority])) {
			$this->messageRendererChain[$priority] = array($hash => $renderer);
		}
		else {
			$this->messageRendererChain[$priority][$hash] = $renderer;
		}
		krsort($this->messageRendererChain);
	}

	/**
	 * Remove a renderer from this renderer chain
	 *
	 * @param MessageRendererInterface $renderer The message renderer to remove.
	 */
	public function removeRenderer(MessagePreRendererInterface $renderer)
	{
		$hash = spl_object_hash($renderer);
		foreach ($this->messageRendererChain as &$list) {
			unset($list[$hash]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderMessage(Message $message)
	{
		foreach ($this->messageRendererChain as $list) {
			/** @var MessageRendererInterface $renderer */
			foreach ($list as $renderer) {
				if ($renderer->canRenderMessage($message, $recipient)) {
					return $renderer->renderMessage($message, $recipient);
				}
			}
		}

		throw new \RuntimeException('Could not render message ' . $message->getId());
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderContent(MessageContent $content)
	{
		foreach ($this->messageRendererChain as $list) {
			foreach ($list as $renderer) {
				if ($renderer->canRenderMessage($content->getMessage(), $recipient)) {
					return $renderer->renderContent($content, $recipient);
				}
			}
		}

		throw new \RuntimeException('Could not render message content ' . $content->getId());
	}

	/**
	 * {@inheritdoc}
	 */
	public function canRenderMessage(Message $message)
	{
		foreach ($this->messageRendererChain as $list) {
			foreach ($list as $renderer) {
				if ($renderer->canRenderMessage($message, $recipient)) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function canRenderContent(MessageContent $content)
	{
		foreach ($this->messageRendererChain as $list) {
			foreach ($list as $renderer) {
				if ($renderer->canRenderContent($content, $recipient)) {
					return true;
				}
			}
		}
		return false;
	}
}
