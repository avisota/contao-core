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

namespace Avisota\Contao\Backend;

use Avisota\Contao\Entity\Queue;
use Avisota\Contao\Message\Renderer;
use Avisota\Queue\QueueInterface;
use Contao\Doctrine\ORM\EntityHelper;

class Outbox extends \TwigBackendModule
{
	static public function isEmpty()
	{
		global $container;

		$length = 0;

		$queueRepository     = EntityHelper::getRepository('Avisota\Contao:Queue');
		$queueDataCollection = $queueRepository->findAll();

		/** @var QueueInterface $queue */
		foreach ($queueDataCollection as $queueData) {
			$serviceName = sprintf('avisota.queue.%s', $queueData->getId());
			$queue       = $container[$serviceName];

			$length += $queue->length();
		}

		return $length == 0;
	}

	/**
	 * @var string
	 */
	protected $strTemplate = 'avisota/backend/outbox';

	/**
	 * Compile the current element
	 */
	protected function compile()
	{
		global $container;

		$this->loadLanguageFile('avisota_outbox');

		$queueRepository     = EntityHelper::getRepository('Avisota\Contao:Queue');
		$queueDataCollection = $queueRepository->findAll();
		$items               = array();

		/** @var Queue $queueData */
		/** @var QueueInterface $queue */
		foreach ($queueDataCollection as $queueData) {
			$serviceName = sprintf('avisota.queue.%s', $queueData->getId());
			$queue       = $container[$serviceName];

			$item           = $queueData->toArray();
			$item['length'] = $queue->length();

			$items[] = $item;
		}

		$this->Template->queues = $items;
	}
}
