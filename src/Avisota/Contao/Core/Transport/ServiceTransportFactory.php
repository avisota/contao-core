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

namespace Avisota\Contao\Core\Transport;

use Avisota\Contao\Entity\Transport;

/**
 * Class ServiceTransportFactory
 *
 * @package Avisota\Contao\Core\Transport
 */
class ServiceTransportFactory implements TransportFactoryInterface
{
	/**
	 * @param Transport $transport
	 *
	 * @return mixed
     */
    public function createTransport(Transport $transport)
	{
		global $container;

		return $container[$transport->getServiceName()];
	}
}
