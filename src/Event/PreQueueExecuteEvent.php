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

namespace Avisota\Contao\Core\Event;

use Avisota\Queue\ExecutionConfig;
use Avisota\Queue\QueueInterface;
use Avisota\Transport\TransportInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * The pre defined queue execute event.
 */
class PreQueueExecuteEvent extends Event
{
    const NAME = 'Avisota\Contao\Core\Event\PreQueueExecute';

    /**
     * The queue.
     *
     * @var QueueInterface
     */
    protected $queue;

    /**
     * The transport.
     *
     * @var TransportInterface
     */
    protected $transport;

    /**
     * The execution configuration.
     *
     * @var ExecutionConfig
     */
    protected $config;

    /**
     * PreQueueExecuteEvent constructor.
     *
     * @param QueueInterface     $queue     The queue.
     * @param TransportInterface $transport The transport.
     * @param ExecutionConfig    $config    The execution configuration.
     */
    public function __construct(QueueInterface $queue, TransportInterface $transport, ExecutionConfig $config)
    {
        $this->queue     = $queue;
        $this->transport = $transport;
        $this->config    = $config;
    }

    /**
     * Set the queue.
     *
     * @param QueueInterface $queue The queue.
     *
     * @return $this
     */
    public function setQueue(QueueInterface $queue)
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * Get the queue
     *
     * @return QueueInterface
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set the transport.
     *
     * @param TransportInterface $transport The transport.
     *
     * @return $this
     */
    public function setTransport(TransportInterface $transport)
    {
        $this->transport = $transport;
        return $this;
    }

    /**
     * Get the transport.
     *
     * @return TransportInterface
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set the execution configuration.
     *
     * @param ExecutionConfig $config The execution configuration.
     *
     * @return $this
     */
    public function setConfig(ExecutionConfig $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get the execution configuration.
     *
     * @return ExecutionConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
}
