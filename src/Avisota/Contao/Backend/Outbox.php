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
use Avisota\Contao\Event\PreQueueExecuteEvent;
use Avisota\Contao\Message\Renderer;
use Avisota\Queue\ExecutionConfig;
use Avisota\Queue\QueueInterface;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
			try {
				$serviceName = sprintf('avisota.queue.%s', $queueData->getId());
				$queue       = $container[$serviceName];

				$length += $queue->length();
			}
			catch (\InvalidArgumentException $e) {
				// silently hide Identifier "..." is not defined
				trigger_error($e->getMessage(), E_USER_NOTICE);
			}
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
		$this->loadLanguageFile('avisota_outbox');

		$queueRepository = EntityHelper::getRepository('Avisota\Contao:Queue');

		$this->executeQueue($queueRepository);
		$this->addQueuesToTemplate($queueRepository);
	}

	protected function executeQueue(EntityRepository $queueRepository)
	{
		global $container;

		$input = \Input::getInstance();

		$executeId = $input->get('execute');
		if ($executeId) {
			/** @var Queue $queueData */
			$queueData = $queueRepository->find($executeId);

			if (!$queueData->getAllowManualSending()) {
				return;
			}

			$serviceName = sprintf('avisota.queue.%s', $queueData->getId());
			/** @var QueueInterface $queue */
			$queue = $container[$serviceName];

			$this->Template->setName('avisota/backend/outbox_execute');
			$this->Template->queue  = $queue;
			$this->Template->config = $queueData->toArray();

			$GLOBALS['TL_CSS'][] = 'system/modules/avisota/assets/css/be_outbox.css';
			$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/avisota/assets/js/Number.js';
			$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/avisota/assets/js/be_outbox.js';
		}
	}

	protected function addQueuesToTemplate(EntityRepository $queueRepository)
	{
		global $container;

		/** @var Queue[] $queueDataCollection */
		$queueDataCollection = $queueRepository->findAll();
		$items               = array();

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
