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

namespace Avisota\Contao\Core;

use Avisota\Contao\Core\RecipientSource\RecipientSourceFactoryInterface;
use Avisota\Contao\Core\Transport\TransportFactoryInterface;
use Avisota\Contao\Core\Queue\QueueFactoryInterface;
use Avisota\Contao\Entity\RecipientSource;
use Avisota\Contao\Entity\Transport;
use Avisota\Contao\Entity\Queue;
use Contao\Doctrine\ORM\EntityHelper;

/**
 * Class ServiceFactory
 *
 * @package Avisota\Contao\Core
 */
class ServiceFactory
{
    /**
     * @param \Pimple $container
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function init($container)
    {
        try {
            // preserve object initialisation order
            if (TL_MODE == 'FE') {
                \FrontendUser::getInstance();
            } else {
                \BackendUser::getInstance();
            }

            // initialize the entity manager and class loaders
            $container['doctrine.orm.entityManager'];

            $this->createRecipientSourceService();
            $this->createQueueService();
            $this->createTransportService();

        } catch (\Exception $e) {
            $message = 'Could not create avisota services: ' . $e->getMessage();

            if ($e instanceof \ReflectionException) {
                $message .= PHP_EOL . 'You may need to run the database update!';
            }

            log_message($message . PHP_EOL . $e->getTraceAsString());
        }
    }

    protected function createRecipientSourceService()
    {
        if (!class_exists('Avisota\Contao\Entity\RecipientSource')) {
            return;
        }

        global $container;
        $factory = $this;
        $verbose = TL_MODE == 'BE';
        $session = \Session::getInstance();

        try {
            $recipientSourceRepository = EntityHelper::getRepository('Avisota\Contao:RecipientSource');
            /** @var RecipientSource[] $recipientSources */
            $recipientSources = $recipientSourceRepository->findAll();

            foreach ($recipientSources as $recipientSource) {
                $container[sprintf('avisota.recipientSource.%s', $recipientSource->getId())] =
                    $container->share(
                        function ($container) use ($recipientSource, $factory) {
                            return $factory->createRecipientSource($recipientSource);
                        }
                    );

                $container[sprintf('avisota.recipientSource.%s', $recipientSource->getAlias())] =
                    function ($container) use ($recipientSource) {
                        return $container[sprintf('avisota.recipientSource.%s', $recipientSource->getId())];
                    };
            }
        } catch (\Exception $e) {
            $message = 'Could not create avisota recipient source service:' . PHP_EOL . $e->getMessage();

            if ($e instanceof \ReflectionException) {
                $message .= PHP_EOL . 'You may need to run the database update!';
            }

            if ($verbose) {
                $tlRaw   = $session->get('TL_RAW');
                $tlRaw[] = sprintf('<p class="tl_error">%s</p>', nl2br($message));
                $session->set('TL_RAW', $tlRaw);
            }

            log_message($message . PHP_EOL . $e->getTraceAsString());
        }
    }

    protected function createQueueService()
    {
        if (!class_exists('Avisota\Contao\Entity\Queue')) {
            return;
        }

        global $container;
        $factory = $this;
        $verbose = TL_MODE == 'BE';
        $session = \Session::getInstance();

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

                $container[sprintf('avisota.queue.%s', $queue->getAlias())] =
                    function ($container) use ($queue) {
                        return $container[sprintf('avisota.queue.%s', $queue->getId())];
                    };
            }
        } catch (\Exception $e) {
            $message = 'Could not create avisota queue service: ' . $e->getMessage();

            if ($e instanceof \ReflectionException) {
                $message .= PHP_EOL . 'You may need to run the database update!';
            }

            if ($verbose) {
                $tlRaw   = $session->get('TL_RAW');
                $tlRaw[] = sprintf('<p class="tl_error">%s</p>', nl2br($message));
                $session->set('TL_RAW', $tlRaw);
            }

            log_message($message . PHP_EOL . $e->getTraceAsString());
        }
    }

    protected function createTransportService()
    {
        global $container;
        $factory = $this;
        $verbose = TL_MODE == 'BE';
        $session = \Session::getInstance();

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

                $container[sprintf('avisota.transport.%s', $transport->getAlias())] =
                    function ($container) use ($transport) {
                        return $container[sprintf('avisota.transport.%s', $transport->getId())];
                    };
            }

            $container['avisota.transport.default'] = function ($container) {
                return $container[sprintf(
                    'avisota.transport.%s',
                    $GLOBALS['TL_CONFIG']['avisota_default_transport']
                )];
            };
        } catch (\Exception $e) {
            $message = 'Could not create avisota transport service: ' . $e->getMessage();

            if ($e instanceof \ReflectionException) {
                $message .= PHP_EOL . 'You may need to run the database update!';
            }

            if ($verbose) {
                $tlRaw   = $session->get('TL_RAW');
                $tlRaw[] = sprintf('<p class="tl_error">%s</p>', nl2br($message));
                $session->set('TL_RAW', $tlRaw);
            }

            log_message($message . PHP_EOL . $e->getTraceAsString());
        }
    }

    /**
     * @param RecipientSource $recipientSource
     *
     * @return mixed
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function createRecipientSource(RecipientSource $recipientSource)
    {
        $recipientSourceFactoryClass =
            new \ReflectionClass($GLOBALS['AVISOTA_RECIPIENT_SOURCE'][$recipientSource->getType()]);
        /** @var RecipientSourceFactoryInterface $recipientSourceFactory */
        $recipientSourceFactory = $recipientSourceFactoryClass->newInstance();

        return $recipientSourceFactory->createRecipientSource($recipientSource);
    }

    /**
     * @param Queue $queue
     *
     * @return mixed
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function createQueue(Queue $queue)
    {
        $queueFactoryClass = new \ReflectionClass($GLOBALS['AVISOTA_QUEUE'][$queue->getType()]);
        /** @var QueueFactoryInterface $queueFactory */
        $queueFactory = $queueFactoryClass->newInstance();

        return $queueFactory->createQueue($queue);
    }

    /**
     * @param Transport $transport
     *
     * @return mixed
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function createTransport(Transport $transport)
    {
        $transportFactoryClass = new \ReflectionClass($GLOBALS['AVISOTA_TRANSPORT'][$transport->getType()]);
        /** @var TransportFactoryInterface $transportFactory */
        $transportFactory = $transportFactoryClass->newInstance();

        return $transportFactory->createTransport($transport);
    }
}
