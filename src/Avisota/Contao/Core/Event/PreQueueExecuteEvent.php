<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Event;

use Avisota\Contao\Entity\Recipient;
use Avisota\Queue\ExecutionConfig;
use Avisota\Queue\QueueInterface;
use Avisota\Transport\TransportInterface;
use Symfony\Component\EventDispatcher\Event;

class PreQueueExecuteEvent extends Event
{
    const NAME = 'Avisota\Contao\Core\Event\PreQueueExecute';

    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     * @var ExecutionConfig
     */
    protected $config;

    function __construct(QueueInterface $queue, TransportInterface $transport, ExecutionConfig $config)
    {
        $this->queue     = $queue;
        $this->transport = $transport;
        $this->config    = $config;
    }

    /**
     * @param \Avisota\Queue\QueueInterface $queue
     */
    public function setQueue(QueueInterface $queue)
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * @return \Avisota\Queue\QueueInterface
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @param \Avisota\Transport\TransportInterface $transport
     */
    public function setTransport(TransportInterface $transport)
    {
        $this->transport = $transport;
        return $this;
    }

    /**
     * @return \Avisota\Transport\TransportInterface
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param \Avisota\Queue\ExecutionConfig $config
     */
    public function setConfig(ExecutionConfig $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return \Avisota\Queue\ExecutionConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
}
