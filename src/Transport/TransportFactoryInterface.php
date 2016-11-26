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

namespace Avisota\Contao\Core\Transport;

use Avisota\Contao\Entity\Transport;

/**
 * Interface TransportFactoryInterface
 *
 * @package Avisota\Contao\Core\Transport
 */
interface TransportFactoryInterface
{
    /**
     * @param Transport $transport The transport.
     *
     * @return mixed
     */
    public function createTransport(Transport $transport);
}
