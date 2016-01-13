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
 * Event is the base class for classes containing event data.
 *
 * This class contains no event data. It is used by events that do not pass
 * state information to an event handler when an event is raised.
 *
 * You can call the method stopPropagation() to abort the execution of
 * further listeners in your event listener.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
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

    /**
     * PreQueueExecuteEvent constructor.
     *
     * @param QueueInterface     $queue
     * @param TransportInterface $transport
     * @param ExecutionConfig    $config
     */
    function __construct(QueueInterface $queue, TransportInterface $transport, ExecutionConfig $config)
    {
        $this->queue     = $queue;
        $this->transport = $transport;
        $this->config    = $config;
    }

    /**
     * @param \Avisota\Queue\QueueInterface $queue
     *
     * @return $this
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
     *
     * @return $this
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
     *
     * @return $this
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
