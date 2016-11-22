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

/**
 * The queue factory interface.
 */
interface QueueFactoryInterface
{
    /**
     * Create the queue factory.
     *
     * @param Queue $queueEntity The queue entity.
     *
     * @return mixed
     */
    public function createQueue(Queue $queueEntity);
}
