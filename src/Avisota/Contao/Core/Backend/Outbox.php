<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Backend;

use Avisota\Contao\Entity\Queue;
use Avisota\Contao\Core\Message\Renderer;
use Avisota\Queue\QueueInterface;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A BackendModule implementation that use Twig as template engine.
 *
 * @package ContaoTwig
 * @author  Tristan Lins <tristan.lins@bit3.de>
 */
class Outbox extends \TwigBackendModule
{
	/**
	 * @return bool
     */
	static public function isEmpty()
	{
		global $container;

		$length = 0;

		$queueRepository     = EntityHelper::getRepository('Avisota\Contao:Queue');
		$queueDataCollection = $queueRepository->findAll();

		/** @var QueueInterface $queue */
		/** @var Queue $queueData */
		foreach ($queueDataCollection as $queueData) {
			$serviceName = sprintf('avisota.queue.%s', $queueData->getId());
			if ($container->offsetExists($serviceName)) {
				$queue = $container[$serviceName];
				$length += $queue->length();
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
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$eventDispatcher->dispatch(
			ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
			new LoadLanguageFileEvent('avisota_outbox')
		);

		$queueRepository = EntityHelper::getRepository('Avisota\Contao:Queue');

		$this->executeQueue($queueRepository);
		$this->addQueuesToTemplate($queueRepository);
	}

	/**
	 * @param EntityRepository $queueRepository
     */
	protected function executeQueue(EntityRepository $queueRepository)
	{
		global $container;



		$executeId = \Input::get('execute');
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
			$this->Template->config = $queueData;

			$GLOBALS['TL_CSS'][]        = 'assets/avisota/core/css/be_outbox.css';
			$GLOBALS['TL_JAVASCRIPT'][] = 'assets/avisota/core/js/Number.js';
			$GLOBALS['TL_JAVASCRIPT'][] = 'assets/avisota/core/js/be_outbox.js';
		}
	}

	/**
	 * @param EntityRepository $queueRepository
     */
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

			$item['meta']  = $queueData;
			$item['queue'] = $queue;

			$items[] = $item;
		}

		$this->Template->items = $items;
	}
}
