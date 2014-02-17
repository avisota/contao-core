<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core;

use Avisota\Contao\Core\RecipientSource\RecipientSourceFactoryInterface;
use Avisota\Contao\Core\Transport\TransportFactoryInterface;
use Avisota\Contao\Core\Queue\QueueFactoryInterface;
use Avisota\Contao\Entity\Transport;
use Avisota\Contao\Entity\Queue;
use Contao\Doctrine\ORM\EntityHelper;

class ServiceFactory
{
	/**
	 * @param \Pimple $container
	 */
	public function init($container)
	{
		$factory = $this;

		if (class_exists('Avisota\Contao\Entity\Queue')) {
			$queueRepository = EntityHelper::getRepository('Avisota\Contao:Queue');
			/** @var Queue[] $queues */
			$queues = $queueRepository->findAll();

			foreach ($queues as $queue) {
				$container[sprintf('avisota.queue.%s', $queue->getId())] = $container->share(
					function ($container) use ($queue, $factory) {
						return $factory->createQueue($queue);
					}
				);

				$container[sprintf('avisota.queue.%s', $queue->getId())] = function($container) use ($queue) {
					return $container[sprintf('avisota.queue.%s', $queue->getId())];
				};
			}
		}

		if (class_exists('Avisota\Contao\Entity\Transport')) {
			$transportRepository = EntityHelper::getRepository('Avisota\Contao:Transport');
			/** @var Transport[] $transports */
			$transports = $transportRepository->findAll();

			foreach ($transports as $transport) {
				$container[sprintf('avisota.transport.%s', $transport->getId())] = $container->share(
					function ($container) use ($transport, $factory) {
						return $factory->createQueue($transport);
					}
				);

				$container[sprintf('avisota.transport.%s', $transport->getId())] = function($container) use ($transport) {
					return $container[sprintf('avisota.transport.%s', $transport->getId())];
				};
			}
		}
	}

	public function createQueue(Queue $queue)
	{
		$queueFactoryClass = new \ReflectionClass($GLOBALS['AVISOTA_QUEUE'][$queue->getType()]);
		/** @var QueueFactoryInterface $queueFactory */
		$queueFactory = $queueFactoryClass->newInstance();

		return $queueFactory->createQueue($queue);
	}

	public function createTransport(Transport $transport)
	{
		$transportFactoryClass = new \ReflectionClass($GLOBALS['AVISOTA_TRANSPORT'][$transport->getType()]);
		/** @var TransportFactoryInterface $transportFactory */
		$transportFactory = $transportFactoryClass->newInstance();

		return $transportFactory->createTransport($transport);
	}
}
