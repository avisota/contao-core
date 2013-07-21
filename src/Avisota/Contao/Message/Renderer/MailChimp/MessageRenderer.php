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

namespace Avisota\Contao\Message\Renderer\MailChimp;

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Entity\MessageContent;
use Avisota\Contao\Event\InitializeMessageRendererEvent;
use Avisota\Contao\Event\RendererHeadersEvent;
use Avisota\Contao\Message\Renderer\MessageContentRendererChain;
use Avisota\Contao\Message\Renderer\MessageRendererInterface;
use Avisota\Recipient\RecipientInterface;
use Bit3\TagReplacer\TagReplacer;
use Contao\Doctrine\ORM\EntityHelper;

class MessageRenderer implements MessageRendererInterface
{
	const MODE_HTML = 'html';

	const MODE_PLAIN = 'plain';

	static public $elementInstances = array();

	protected $tagReplacer;

	protected $contentRenderer;

	function __construct()
	{
		$this->tagReplacer = new TagReplacer(TagReplacer::FLAG_ENABLE_ALL_INTERNALS);

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];
		$eventDispatcher->dispatch('avisota-renderer-initialize', new InitializeMessageRendererEvent($this));

		$this->tagReplacer->setUnknownDefaultMode(TagReplacer::MODE_SKIP);
		$this->tagReplacer->setUnknownTagMode(TagReplacer::MODE_SKIP);
		$this->tagReplacer->setUnknownTokenMode(TagReplacer::MODE_SKIP);
	}

	/**
	 * @return mixed
	 */
	public function getTagReplacer()
	{
		return $this->tagReplacer;
	}

	/**
	 * @return \Avisota\Contao\Message\Renderer\MessageContentRendererChain
	 */
	public function getContentRenderer()
	{
		if (!$this->contentRenderer) {
			$this->contentRenderer = new MessageContentRendererChain($GLOBALS['AVISOTA_CONTENT_RENDERER']['mailChimp']);
		}
		return $this->contentRenderer;
	}

	public function renderMessage(Message $message, RecipientInterface $recipient = null)
	{
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$layout       = $message->getLayout();

		list($templateGroup, $templateName) = explode(':', $this->getMailchimpTemplate());
		if (!isset($GLOBALS['AVISOTA_MAILCHIMP_TEMPLATE'][$templateGroup][$templateName])) {
			throw new \RuntimeException('Mailchimp template ' . $templateGroup . '/' . $templateName . ' was not found!');
		}
		$mailChimpTemplate = $GLOBALS['AVISOTA_MAILCHIMP_TEMPLATE'][$templateGroup][$templateName];
		$cells        = $mailChimpTemplate['cells'];
		$rows         = isset($mailChimpTemplate['rows']) ? $mailChimpTemplate['rows'] : array();
		$cellContents = array();
		$mode         = $mailChimpTemplate['mode'];

		$repeatableCells = array();
		foreach ($rows as $row) {
			$repeatableCells = array_merge($repeatableCells, $row['affectedCells']);
		}

		$reflectionClass = new \ReflectionClass($this);
		$constants       = $reflectionClass->getConstants();
		$constantsKeys   = array_keys($constants);
		$constantsKeys   = array_filter(
			$constantsKeys,
			function ($key) {
				return substr($key, 0, 5) == 'MODE_';
			}
		);
		$constantsKeys   = array_flip($constantsKeys);
		$constants       = array_intersect_key($constants, $constantsKeys);

		if (!in_array($mode, $constants)) {
			throw new \RuntimeException('Render mode ' . strtoupper(
				$mode
			) . ' not supported by this renderer of type ' . $reflectionClass->getName());
		}

		foreach ($cells as $cellName => $cellConfig) {
			if (isset($cellConfig['content'])) {
				$cellContents[$cellName] = array($cellConfig['content']);
			}
			else {
				for (
					$i = 1;
					$i == 1 ||
					in_array($cellName, $repeatableCells) &&
					$cellContents[sprintf('%s[%d]', $cellName, $i - 1)]->count();
					$i++
				) {
					$cellContents[$cellName] = $this->renderCell($mode, $message, sprintf('%s[%d]', $cellName, $i));
				}
			}
		}

		$template = file_get_contents(TL_ROOT . '/' . $mailChimpTemplate['template']);

		$template = str_replace('mc:', 'mc__', $template);

		$document               = new \DOMDocument();
		$document->formatOutput = true;
		$document->loadHTML($template);

		$xpath = new \DOMXPath($document);

		if ($layout->getClearStyles()) {
			$styles = $xpath->query('/html/head/style');
			for ($i = 0; $i < $styles->length; $i++) {
				$style = $styles->item($i);
				$style->parentNode->removeChild($style);
			}
		}

		foreach ($cells as $cellName => $cellConfig) {
			$replace = isset($cellConfig['replace']) && $cellConfig['replace'];

			// remove empty nodes
			if ((is_array($cellContents[$cellName]) && empty($cellContents[$cellName]) ||
				$cellContents[$cellName] instanceof \ArrayObject && $cellContents[$cellName]->count() == 0)
			) {
				if (isset($cellConfig['ifEmptyRemove'])) {
					$expression = $cellConfig['ifEmptyRemove'];
				}
				else {
					$expression = $cellConfig['xpath'];
				}

				$nodes = $xpath->query(str_replace('mc:', 'mc__', $expression), $document->documentElement);

				if (!$nodes->length) {
					throw new \RuntimeException('Node ' . $expression . ' not found in ' . $mailChimpTemplate['template']);
				}

				for ($i = 0; $i < $nodes->length; $i++) {
					$node = $nodes->item(0);

					if ($replace) {
						$node->parentNode->removeChild($node);
					}
					else {
						while ($node->childNodes->length) {
							$childNode = $node->childNodes->item(0);
							$node->removeChild($childNode);
						}
					}
				}
			}
			else {
				/** @var \StringBuilder $cellContentRow */
				foreach ($cellContents[$cellName] as $index => $cellContentRow) {
					$cellContentRowDoc = new \DOMDocument();
					$cellContentRowDoc->loadXML('<html>' . $cellContentRow . '</html>');

					if (isset($cellConfig['wrapRow'])) {
						$wrapRowDoc = new \DOMDocument();
						$wrapRowDoc->loadXML('<html>' . $cellConfig['wrapRow'] . '</html>');

						$wrapElement = $wrapRowDoc->documentElement;
						while ($wrapElement->firstChild) {
							$wrapElement = $wrapElement->firstChild;
						}

						for ($i = 0; $i < $cellContentRowDoc->documentElement->childNodes->length; $i++) {
							$childNode = $cellContentRowDoc->documentElement->childNodes->item($i);
							$childNode = $wrapRowDoc->importNode($childNode, true);
							$wrapElement->appendChild($childNode);
						}

						$cellContentRowDoc = $wrapRowDoc;
					}

					$cellContents[$cellName][$index] = $cellContentRowDoc;
				}

				$cellContentDoc = new \DOMDocument();
				$cellContentDoc->appendChild($cellContentDoc->createElement('html'));
				foreach ($cellContents[$cellName] as $cellContentRowDoc) {
					for ($i = 0; $i < $cellContentRowDoc->documentElement->childNodes->length; $i++) {
						$childNode = $cellContentRowDoc->documentElement->childNodes->item($i);
						$childNode = $cellContentDoc->importNode($childNode, true);
						$cellContentDoc->documentElement->appendChild($childNode);
					}
				}

				if (isset($cellConfig['wrapContent'])) {
					$wrapContentDoc = new \DOMDocument();
					$wrapContentDoc->loadXML('<html>' . $cellConfig['wrapContent'] . '</html>');

					$wrapElement = $wrapContentDoc->documentElement;
					while ($wrapElement->firstChild) {
						$wrapElement = $wrapElement->firstChild;
					}

					for ($i = 0; $i < $cellContentDoc->documentElement->childNodes->length; $i++) {
						$childNode = $cellContentDoc->documentElement->childNodes->item($i);
						$childNode = $wrapContentDoc->importNode($childNode, true);
						$wrapContentDoc->appendChild($childNode);
					}

					$cellContentDoc = $wrapContentDoc;
				}

				$expression  = $cellConfig['xpath'];
				$targetNodes = $xpath->query(str_replace('mc:', 'mc__', $expression), $document->documentElement);

				if (!$targetNodes->length) {
					throw new \RuntimeException('Node ' . $expression . ' not found in ' . $mailChimpTemplate['template']);
				}

				for ($i = 0; $i < $targetNodes->length; $i++) {
					$targetNode = $targetNodes->item($i);

					if ($targetNode->nodeType == XML_ATTRIBUTE_NODE) {
						$cellContent = $cellContentDoc->saveHTML();
						$cellContent = trim($cellContent);
						$cellContent = preg_replace('#^<html>#', '', $cellContent);
						$cellContent = preg_replace('#</html>$#', '', $cellContent);

						/** @var \DOMAttr $targetNode */
						$targetNode->value = $cellContent;
					}
					else {
						// if not replace, empty target node
						if (!$replace) {
							while ($targetNode->childNodes->length) {
								$childNode = $targetNode->childNodes->item(0);
								$targetNode->removeChild($childNode);
							}
						}

						for ($j = 0; $j < $cellContentDoc->documentElement->childNodes->length; $j++) {
							$childNode = $cellContentDoc->documentElement->childNodes->item($j);
							$childNode = $document->importNode($childNode, true);

							// if replace, insert before target node
							if ($replace) {
								$targetNode->parentNode->insertBefore($childNode, $targetNode);
							}

							// if not replace, append into target node
							else {
								$targetNode->appendChild($childNode);
							}
						}

						// if replace it, remove the target node
						if ($replace) {
							$targetNode->parentNode->removeChild($targetNode);
						}
					}
				}
			}
		}

		$headers = new \ArrayObject();

		$styles      = new \StringBuilder();
		$stylesheets = $layout->getStylesheetPaths();
		foreach ($stylesheets as $stylesheet) {
			$file = new \File($stylesheet);
			$css  = $file->getContent();
			$styles
				->append($css)
				->append("\n");
		}
		$styles->trim();
		if ($styles->length()) {
			$styles->insert(0, "<style>\n");
			$styles->append("\n</style>");
			$headers['styles'] = $styles;
		}

		$eventDispatcher->dispatch('avisota-renderer-headers', new RendererHeadersEvent($this, $message, $headers));

		$headElements = $xpath->query('/html/head', $document->documentElement);
		$headElement  = $headElements->item(0);

		$headerCode = trim(implode("\n", $headers->getArrayCopy()));
		if ($headerCode) {
			$headerDoc = new \DOMDocument();
			$headerDoc->loadXML('<html>' . $headerCode . '</html>');

			for ($i = 0; $i < $headerDoc->documentElement->childNodes->length; $i++) {
				$childNode = $headerDoc->documentElement->childNodes->item($i);
				$childNode = $document->importNode($childNode, true);
				$headElement->appendChild($childNode);
			}
		}

		$html = $document->saveHTML();

		$this->tagReplacer->setToken('category', $message->getCategory());
		$this->tagReplacer->setToken('message', $message);
		$this->tagReplacer->setToken('content', null);
		$this->tagReplacer->setToken('recipient', $recipient);

		$html = $this->tagReplacer->replace($html);

		return $html;
	}

	/**
	 * Render content for a specific cell.
	 *
	 * @param Message $message
	 * @param string  $cell
	 *
	 * @return \StringBuilder
	 */
	public function renderCell($mode, Message $message, $cell, RecipientInterface $recipient = null)
	{
		$entityHelper = EntityHelper::getEntityManager();
		$queryBuilder = $entityHelper->createQueryBuilder();
		$contents     = $queryBuilder
			->select('c')
			->from('Avisota\Contao:MessageContent', 'c')
			->where('c.message=:message')
			->andWhere('c.cell=:cell')
			->setParameter(':message', $message->getId())
			->setParameter(':cell', $cell)
			->orderBy('c.sorting')
			->getQuery()
			->getResult();

		$elementContents = new \ArrayObject();
		foreach ($contents as $content) {
			$elementContents->append(new \StringBuilder($this->renderElement($mode, $content, $recipient)));
		}
		return $elementContents;
	}

	/**
	 * Render a single message content element.
	 *
	 * @param MessageContent     $content
	 * @param RecipientInterface $recipient
	 *
	 * @return string
	 */
	public function renderContent(MessageContent $content, RecipientInterface $recipient = null)
	{
		// TODO: Implement renderContent() method.
	}

	public function canRenderMessage(Message $message, RecipientInterface $recipient = null)
	{
		return $message->getLayout()->getType() == 'mailChimp';
	}

	/**
	 * Check if this renderer can render the given message content element.
	 *
	 * @param MessageContent     $content
	 * @param RecipientInterface $recipient
	 *
	 * @return bool
	 */
	public function canRenderContent(MessageContent $content, RecipientInterface $recipient = null)
	{
		return $this->getContentRenderer()->canRenderContent($content, $recipient);
	}
}
