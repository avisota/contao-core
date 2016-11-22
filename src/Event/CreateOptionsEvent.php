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

/**
 * Create options event.
 */
class CreateOptionsEvent extends \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent
{
    protected $preventDefault = false;

    /**
     * Prevent default listeners.
     *
     * @return $this
     */
    public function preventDefault()
    {
        $this->preventDefault = true;
        return $this;
    }

    /**
     * Determine if default should prevented.
     *
     * @return bool
     */
    public function isDefaultPrevented()
    {
        return $this->preventDefault;
    }
}
