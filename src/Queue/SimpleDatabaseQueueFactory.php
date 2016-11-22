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

namespace Avisota\Contao\Core\Queue;

use Avisota\Contao\Entity\Queue;
use Avisota\Queue\SimpleDatabaseQueue;

/**
 * The simple database queue factory.
 */
class SimpleDatabaseQueueFactory implements QueueFactoryInterface
{
    /**
     * Create the simple queue database.
     *
     * @param Queue $queue
     *
     * @return SimpleDatabaseQueue
     */
    public function createQueue(Queue $queue)
    {
        global $container;

        return new SimpleDatabaseQueue(
            $container['doctrine.connection.default'],
            $queue->getSimpleDatabaseQueueTable(),
            true,
            $container['avisota.logger.queue'],
            $container['event-dispatcher']
        );
    }
}
