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

namespace Avisota\Contao\Core\Controller;

use Avisota\Contao\Core\Event\PreQueueExecuteEvent;
use Avisota\Contao\Core\Queue\AbstractQueueWebRunner;
use Avisota\Contao\Entity\Queue;
use Avisota\Queue\ExecutionConfig;
use Avisota\Queue\QueueInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * The queue execute controller.
 */
class QeueueExecuteController extends AbstractQueueWebRunner
{
    /**
     * Execute the queue.
     *
     * @param Request      $request   The request.
     * @param Queue        $queueData The queue data.
     * @param \BackendUser $user      The user.
     *
     * @return JsonResponse
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(Request $request, Queue $queueData, \BackendUser $user)
    {
        global $container;

        if (!$queueData->getAllowManualSending()) {
            $response = new JsonResponse(array('error' => 'manual sending is forbidden'), 403);
            $response->prepare($request);
            return $response;
        }

        $serviceName = sprintf('avisota.queue.%s', $queueData->getId());
        /** @var QueueInterface $queue */
        $queue = $container[$serviceName];

        $transportServiceName = sprintf(
            'avisota.transport.%s',
            $queueData
                ->getTransport()
                ->getId()
        );
        $transport            = $container[$transportServiceName];

        $config = new ExecutionConfig();
        if ($queueData->getMaxSendTime() > 0) {
            $config->setTimeLimit($queueData->getMaxSendTime());
        }
        if ($queueData->getMaxSendCount() > 0) {
            $config->setMessageLimit($queueData->getMaxSendCount());
        }

        $event = new PreQueueExecuteEvent($queue, $transport, $config);
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];
        $eventDispatcher->dispatch(PreQueueExecuteEvent::NAME, $event);

        $queue     = $event->getQueue();
        $transport = $event->getTransport();
        $config    = $event->getConfig();

        $status = $queue->execute($transport, $config);

        $jsonData = array(
            'success' => 0,
            'failed'  => 0,
        );
        foreach ($status as $stat) {
            $jsonData['success'] += $stat->getSuccessfullySend();
            $jsonData['failed'] += count($stat->getFailedRecipients());
        }

        $response = new JsonResponse($jsonData);
        $response->prepare($request);
        return $response;
    }
}
