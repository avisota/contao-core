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
use Avisota\Contao\Entity\RecipientSource;
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
		try {
			// preserve object initialisation order
			if (TL_MODE == 'FE') {
				\FrontendUser::getInstance();
			}
			else {
				\BackendUser::getInstance();
			}

			$factory = $this;

			// initialize the entity manager and class loaders
			$container['doctrine.orm.entityManager'];

			$verbose = TL_MODE == 'BE';

			if (class_exists('Avisota\Contao\Entity\RecipientSource')) {
				try {
					$recipientSourceRepository = EntityHelper::getRepository('Avisota\Contao:RecipientSource');
					/** @var RecipientSource[] $recipientSources */
					$recipientSources = $recipientSourceRepository->findAll();

					foreach ($recipientSources as $recipientSource) {
						$container[sprintf('avisota.recipientSource.%s', $recipientSource->getId())] = $container->share(
							function ($container) use ($recipientSource, $factory) {
								return $factory->createRecipientSource($recipientSource);
							}
						);

						$container[sprintf('avisota.recipientSource.%s', $recipientSource->getAlias())] = function($container) use ($recipientSource) {
							return $container[sprintf('avisota.recipientSource.%s', $recipientSource->getId())];
						};
					}
				}
				catch (\Exception $e) {
					$message = 'Could not create avisota recipient source service:' . PHP_EOL . $e->getMessage();

					if ($e instanceof \ReflectionException) {
						$message .= PHP_EOL . 'You may need to run the database update!';
					}

					if ($verbose) {
						$_SESSION['TL_RAW'][] = sprintf('<p class="tl_error">%s</p>', nl2br($message));
					}

					log_message($message . PHP_EOL . $e->getTraceAsString());
				}
			}

			if (class_exists('Avisota\Contao\Entity\Queue')) {
				try {
					$queueRepository = EntityHelper::getRepository('Avisota\Contao:Queue');
					/** @var Queue[] $queues */
					$queues = $queueRepository->findAll();

					foreach ($queues as $queue) {
						$container[sprintf('avisota.queue.%s', $queue->getId())] = $container->share(
							function ($container) use ($queue, $factory) {
								return $factory->createQueue($queue);
							}
						);

						$container[sprintf('avisota.queue.%s', $queue->getAlias())] = function($container) use ($queue) {
							return $container[sprintf('avisota.queue.%s', $queue->getId())];
						};
					}
				}
				catch (\Exception $e) {
					$message = 'Could not create avisota queue service: ' . $e->getMessage();

					if ($e instanceof \ReflectionException) {
						$message .= PHP_EOL . 'You may need to run the database update!';
					}

					if ($verbose) {
						$_SESSION['TL_RAW'][] = sprintf('<p class="tl_error">%s</p>', nl2br($message));
					}

					log_message($message . PHP_EOL . $e->getTraceAsString());
				}
			}

			if (class_exists('Avisota\Contao\Entity\Transport')) {
				try {
					$transportRepository = EntityHelper::getRepository('Avisota\Contao:Transport');
					/** @var Transport[] $transports */
					$transports = $transportRepository->findAll();

					foreach ($transports as $transport) {
						$container[sprintf('avisota.transport.%s', $transport->getId())] = $container->share(
							function ($container) use ($transport, $factory) {
								return $factory->createTransport($transport);
							}
						);

						$container[sprintf('avisota.transport.%s', $transport->getAlias())] = function($container) use ($transport) {
							return $container[sprintf('avisota.transport.%s', $transport->getId())];
						};
					}

					$container['avisota.transport.default'] = function($container) {
						return $container[sprintf('avisota.transport.%s', $GLOBALS['TL_CONFIG']['avisota_default_transport'])];
					};
				}
				catch (\Exception $e) {
					$message = 'Could not create avisota transport service: ' . $e->getMessage();

					if ($e instanceof \ReflectionException) {
						$message .= PHP_EOL . 'You may need to run the database update!';
					}

					if ($verbose) {
						$_SESSION['TL_RAW'][] = sprintf('<p class="tl_error">%s</p>', nl2br($message));
					}

					log_message($message . PHP_EOL . $e->getTraceAsString());
				}
			}
		}
		catch (\Exception $e) {
			$message = 'Could not create avisota services: ' . $e->getMessage();

			if ($e instanceof \ReflectionException) {
				$message .= PHP_EOL . 'You may need to run the database update!';
			}

			log_message($message . PHP_EOL . $e->getTraceAsString());
		}
	}

	public function createRecipientSource(RecipientSource $recipientSource)
	{
		$recipientSourceFactoryClass = new \ReflectionClass($GLOBALS['AVISOTA_RECIPIENT_SOURCE'][$recipientSource->getType()]);
		/** @var RecipientSourceFactoryInterface $recipientSourceFactory */
		$recipientSourceFactory = $recipientSourceFactoryClass->newInstance();

		return $recipientSourceFactory->createRecipientSource($recipientSource);
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
