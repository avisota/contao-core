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

namespace Avisota\Contao\Core\DataContainer;

/**
 * Class Transport
 *
 * @package Avisota\Contao\Core\DataContainer
 */
class Transport extends \Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param \DataContainer $dc
     */
    public function onload_callback($dc)
    {
    }

    /**
     * @param \DataContainer $dc
     */
    public function onsubmit_callback($dc)
    {
    }
}
