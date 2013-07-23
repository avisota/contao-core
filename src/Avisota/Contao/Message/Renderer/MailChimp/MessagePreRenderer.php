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

namespace Avisota\Contao\Message\Renderer\MailChimp;

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Entity\MessageContent;
use Avisota\Contao\Event\InitializeMessageRendererEvent;
use Avisota\Contao\Event\RendererHeadersEvent;
use Avisota\Contao\Message\Renderer\MessageContentPreRendererChain;
use Avisota\Contao\Message\Renderer\MessagePreRendererInterface;
use Avisota\Contao\Message\MutablePreRenderedMessageTemplate;
use Avisota\Recipient\RecipientInterface;
use Contao\Doctrine\ORM\EntityHelper;

class MessagePreRenderer implements MessagePreRendererInterface
{
	const MODE_HTML = 'html';

	const MODE_PLAIN = 'plain';

	static public $elementInstances = array();

	protected $contentRenderer;

	function __construct()
	{
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];
		$eventDispatcher->dispatch('avisota-renderer-initialize', new InitializeMessageRendererEvent($this));
	}

	/**
	 * @return \Avisota\Contao\Message\Renderer\MessageContentRendererChain
	 */
	public function getContentRenderer()
	{
		if (!$this->contentRenderer) {
			$this->contentRenderer = new MessageContentPreRendererChain($GLOBALS['AVISOTA_CONTENT_RENDERER']['mailChimp']);
		}
		return $this->contentRenderer;
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderMessage(Message $message)
	{
		try {
			$libxmlUseInternalErrors = libxml_use_internal_errors(true);

			/** @var EventDispatcher $eventDispatcher */
			$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

			$environment = \Environment::getInstance();

			$layout       = $message->getLayout();

			list($templateGroup, $templateName) = explode(':', $layout->getMailchimpTemplate());
			if (!isset($GLOBALS['AVISOTA_MAILCHIMP_TEMPLATE'][$templateGroup][$templateName])) {
				throw new \RuntimeException('Mailchimp template ' . $templateGroup . '/' . $templateName . ' was not found!');
			}
			$mailChimpTemplate = $GLOBALS['AVISOTA_MAILCHIMP_TEMPLATE'][$templateGroup][$templateName];
			$cells        = $mailChimpTemplate['cells'];
			$rows         = isset($mailChimpTemplate['rows']) ? $mailChimpTemplate['rows'] : array();
			$cellContents = array();

			$repeatableCells = array();
			foreach ($rows as $row) {
				$repeatableCells = array_merge($repeatableCells, $row['affectedCells']);
			}

			foreach ($cells as $cellName => $cellConfig) {
				if (isset($cellConfig['content'])) {
					$indexedCellName = sprintf('%s[1]', $cellName);
					$cellContents[$indexedCellName] = array($cellConfig['content']);
				}
				else {
					for (
						$i = 1;
						$i == 1 ||
						in_array($cellName, $repeatableCells) &&
						$cellContents[sprintf('%s[%d]', $cellName, $i - 1)]->count();
						$i++
					) {
						$indexedCellName = sprintf('%s[%d]', $cellName, $i);
						$cellContents[$indexedCellName] = $this->renderCell($message, $indexedCellName);
					}
				}
			}

			$template = file_get_contents(TL_ROOT . '/' . $mailChimpTemplate['template']);
			$template = str_replace('mc:', 'mc__', $template);
			$template = mb_convert_encoding($template, 'HTML-ENTITIES', 'UTF-8');

			$document               = new \DOMDocument('1.0', 'UTF-8');
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

				for ($i=1; ; $i++) {
					$indexedCellName = sprintf('%s[%d]', $cellName, $i);

					// remove empty nodes
					if ((is_array($cellContents[$indexedCellName]) && empty($cellContents[$indexedCellName]) ||
						$cellContents[$indexedCellName] instanceof \ArrayObject && $cellContents[$indexedCellName]->count() == 0)
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
					else if (empty($cellContents[$indexedCellName])) {
						break;
					}
					else {
						/** @var \StringBuilder $cellContentRow */
						foreach ($cellContents[$indexedCellName] as $index => $cellContentRow) {
							$cellContentRow = mb_convert_encoding($cellContentRow, 'HTML-ENTITIES', 'UTF-8');
							$cellContentRowDoc = new \DOMDocument('1.0', 'UTF-8');
							$cellContentRowDoc->loadHTML('<html><body>' . $cellContentRow . '</body></html>');

							if (isset($cellConfig['wrapRow'])) {
								$cellConfig['wrapRow'] = mb_convert_encoding($cellConfig['wrapRow'], 'HTML-ENTITIES', 'UTF-8');
								$wrapRowDoc = new \DOMDocument('1.0', 'UTF-8');
								$wrapRowDoc->loadHTML('<html><body>' . $cellConfig['wrapRow'] . '</body></html>');

								$wrapElement = $wrapRowDoc->documentElement;
								while ($wrapElement->firstChild) {
									$wrapElement = $wrapElement->firstChild;
								}

								for ($i = 0; $i < $cellContentRowDoc->documentElement->firstChild->childNodes->length; $i++) {
									$childNode = $cellContentRowDoc->documentElement->firstChild->childNodes->item($i);
									$childNode = $wrapRowDoc->importNode($childNode, true);
									$wrapElement->appendChild($childNode);
								}

								$cellContentRowDoc = $wrapRowDoc;
							}

							$cellContents[$indexedCellName][$index] = $cellContentRowDoc;
						}

						$cellContentDoc = new \DOMDocument('1.0', 'UTF-8');
						$cellContentDoc->appendChild($cellContentDoc->createElement('html'));
						$cellContentDoc->documentElement->appendChild($cellContentDoc->createElement('body'));
						foreach ($cellContents[$indexedCellName] as $cellContentRowDoc) {
							for ($i = 0; $i < $cellContentRowDoc->documentElement->firstChild->childNodes->length; $i++) {
								$childNode = $cellContentRowDoc->documentElement->firstChild->childNodes->item($i);
								$childNode = $cellContentDoc->importNode($childNode, true);
								$cellContentDoc->documentElement->firstChild->appendChild($childNode);
							}
						}

						if (isset($cellConfig['wrapContent'])) {
							$cellConfig['wrapContent'] = mb_convert_encoding($cellConfig['wrapContent'], 'HTML-ENTITIES', 'UTF-8');
							$wrapContentDoc = new \DOMDocument('1.0', 'UTF-8');
							$wrapContentDoc->loadHTML('<html><body>' . $cellConfig['wrapContent'] . '</body></html>');

							$wrapElement = $wrapContentDoc->documentElement;
							while ($wrapElement->firstChild) {
								$wrapElement = $wrapElement->firstChild;
							}

							for ($i = 0; $i < $cellContentDoc->documentElement->firstChild->childNodes->length; $i++) {
								$childNode = $cellContentDoc->documentElement->firstChild->childNodes->item($i);
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
								$cellContent = preg_replace('#^<html><body>#', '', $cellContent);
								$cellContent = preg_replace('#</body></html>$#', '', $cellContent);

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

								for ($j = 0; $j < $cellContentDoc->documentElement->firstChild->childNodes->length; $j++) {
									$childNode = $cellContentDoc->documentElement->firstChild->childNodes->item($j);
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
				$headerCode = mb_convert_encoding($headerCode, 'HTML-ENTITIES', 'UTF-8');
				$headerDoc = new \DOMDocument('1.0', 'UTF-8');
				$headerDoc->loadHTML('<html><head>' . $headerCode . '</head></html>');

				for ($i = 0; $i < $headerDoc->documentElement->firstChild->childNodes->length; $i++) {
					$childNode = $headerDoc->documentElement->firstChild->childNodes->item($i);
					$childNode = $document->importNode($childNode, true);
					$headElement->appendChild($childNode);
				}
			}

			$baseUrl = $environment->base;
			$links = $xpath->query('//@href|//@src');
			for ($i=0; $i<$links->length; $i++) {
				/** @var \DOMAttr $link */
				$link = $links->item($i);
				if (!preg_match('~^(\w+:|#[^#])~', $link->value)) {
					$link->value = $baseUrl . $link->value;
				}
			}

			$html = $document->saveHTML();

			$response = new MutablePreRenderedMessageTemplate(
				$message,
				$html,
				standardize($message->getSubject()) . '.html',
				'text/html',
				'utf-8'
			);

			libxml_use_internal_errors($libxmlUseInternalErrors);
			return $response;
		}
		catch (\Exception $exception) {
			libxml_use_internal_errors($libxmlUseInternalErrors);
			throw $exception;
		}
	}

	/**
	 * Render content for a specific cell.
	 *
	 * @param Message $message
	 * @param string  $cell
	 *
	 * @return \StringBuilder
	 */
	protected function renderCell(Message $message, $cell)
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
			$elementContents->append($this->renderContent($content));
		}
		return $elementContents;
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderContent(MessageContent $content)
	{
		return $this->getContentRenderer()->renderContent($content);
	}

	/**
	 * {@inheritdoc}
	 */
	public function canRenderMessage(Message $message)
	{
		return $message->getLayout()->getType() == 'mailChimp';
	}

	/**
	 * {@inheritdoc}
	 */
	public function canRenderContent(MessageContent $content)
	{
		return $this->getContentRenderer()->canRenderContent($content);
	}
}
