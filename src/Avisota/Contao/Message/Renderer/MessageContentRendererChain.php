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
 * @license    LGPL
 * @filesource
 */

namespace Avisota\Contao\Message\Renderer;

use Avisota\Contao\Entity\MessageContent;
use Avisota\Recipient\RecipientInterface;

class MessageContentRendererChain implements MessageContentRendererInterface
{
	protected $contentRendererChain = array();

	function __construct(array $contentRenderers = null)
	{
		if ($contentRenderers) {
			foreach ($contentRenderers as $contentRenderer) {
				if (is_array($contentRenderer)) {
					list($contentRenderer, $priority) = $contentRenderer;
				}
				else {
					$priority = 0;
				}
				if (is_string($contentRenderer)) {
					$childRendererClass    = new \ReflectionClass($contentRenderer);
					$childRendererInstance = $childRendererClass->newInstance();
					$this->addContentRenderer($childRendererInstance, $priority);
				}
				else if ($contentRenderer instanceof MessageRendererInterface) {
					$this->addContentRenderer($childRendererInstance, $priority);
				}
				else {
					throw new \RuntimeException('Illegal message content renderer ' . (is_object($contentRenderer)
						? get_class(
							$contentRenderer
						) : $contentRenderer));
				}
			}
		}
	}

	public function addContentRenderer(MessageContentRendererInterface $renderer, $priority = 0)
	{
		$this->removeContentRenderer($renderer);

		$hash = spl_object_hash($renderer);

		if (!isset($this->contentRendererChain[$priority])) {
			$this->contentRendererChain[$priority] = array($hash => $renderer);
		}
		else {
			$this->contentRendererChain[$priority][$hash] = $renderer;
		}
		krsort($this->contentRendererChain);
	}

	public function removeContentRenderer(MessageContentRendererInterface $renderer)
	{
		$hash = spl_object_hash($renderer);
		foreach ($this->contentRendererChain as &$list) {
			unset($list[$hash]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderContent(MessageContent $content, RecipientInterface $recipient = null)
	{
		foreach ($this->contentRendererChain as $list) {
			/** @var MessageContentRendererInterface $renderer */
			foreach ($list as $renderer) {
				if ($renderer->canRenderContent($content, $recipient)) {
					return $renderer->renderContent($content, $recipient);
				}
			}
		}

		throw new \RuntimeException('Could not render message content ' . $content->getId() . ' of type ' . $content->getType());
	}

	/**
	 * {@inheritdoc}
	 */
	public function canRenderContent(MessageContent $content, RecipientInterface $recipient = null)
	{
		foreach ($this->contentRendererChain as $list) {
			foreach ($list as $renderer) {
				if ($renderer->canRender($content, $recipient)) {
					return true;
				}
			}
		}
		return false;
	}
}
