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

namespace Avisota\Contao\Message;

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Entity\MessageContent;
use Contao\Doctrine\ORM\EntityHelper;

/**
 * Class Renderer
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class Renderer
{
	const MODE_HTML = 'html';

	const MODE_PLAIN = 'plain';

	public function render(Message $message)
	{
		$layout = $message->getLayout();
		list($baseTemplateGroup, $baseTemplateName) = explode(':', $layout->getBaseTemplate());
		$baseTemplate = $GLOBALS['AVISOTA_MESSAGE_BASE_TEMPLATE'][$baseTemplateGroup][$baseTemplateName];
		$cells        = $baseTemplate['cells'];
		$cellContents = array();

		foreach ($cells as $cellName => $cellConfig) {
			if (isset($cellConfig['content'])) {
				$cellContents[$cellName] = array($cellConfig['content']);
			}
			else {
				$cellContents[$cellName] = $this->renderCell($message, $cellName);
			}
		}

		$template = file_get_contents(TL_ROOT . '/' . $baseTemplate['template']);

		$template = str_replace('mc:', 'mc__', $template);

		$document               = new \DOMDocument();
		$document->formatOutput = true;
		$document->loadHTML($template);

		$xpath = new \DOMXPath($document);

		if ($layout->getClearStyles()) {
			$styles = $xpath->query('/html/head/style');
			for ($i=0; $i<$styles->length; $i++) {
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
					throw new \RuntimeException('Node ' . $expression . ' not found in ' . $baseTemplate['template']);
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
					throw new \RuntimeException('Node ' . $expression . ' not found in ' . $baseTemplate['template']);
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

		return $document->saveHTML();
	}

	/**
	 * Render content for a specific cell.
	 *
	 * @param Message $message
	 * @param string  $cell
	 *
	 * @return \StringBuilder
	 */
	public function renderCell(Message $message, $cell)
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
			$elementContents->append(new StringBuilder($this->renderElement($content)));
		}
		return $elementContents;
	}

	/**
	 * Render a content element.
	 *
	 * @param MessageContent $content
	 * @param string         $mode
	 */
	public function renderElement(MessageContent $content)
	{
		$elementClass = static::getElementClass($content->getType());
		$element      = $elementClass->newInstance($content);
		return $element->generate();
	}

	/**
	 * Return the element class.
	 *
	 * @param string $type
	 *
	 * @return \ReflectionClass
	 * @throws \RuntimeException
	 */
	static public function getElementClass($type)
	{
		foreach ($GLOBALS['TL_NLE'] as $group => $types) {
			if (isset($types[$type])) {
				return new \ReflectionClass($types[$type]);
			}
		}

		throw new \RuntimeException('Could not found message element type ' . $type);
	}
}
