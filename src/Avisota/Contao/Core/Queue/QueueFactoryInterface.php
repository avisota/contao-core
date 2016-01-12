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

namespace Avisota\Contao\Core\Queue;

use Avisota\Contao\Entity\Queue;

/**
 * Interface QueueFactoryInterface
 *
 * @package Avisota\Contao\Core\Queue
 */
interface QueueFactoryInterface
{
	/**
	 * @param Queue $queueEntity
	 *
	 * @return mixed
     */
    public function createQueue(Queue $queueEntity);
}
