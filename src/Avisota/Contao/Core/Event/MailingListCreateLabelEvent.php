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

use Avisota\Contao\Core\Message\Renderer;
use Symfony\Component\EventDispatcher\Event;

class MailingListCreateLabelEvent extends Event
{
    const NAME = 'Avisota\Contao\Core\Event\MailingListCreateLabel';

    /**
     * @var \ArrayObject
     */
    protected $row;

    /**
     * @var \StringBuilder
     */
    protected $label;

    function __construct(\ArrayObject $row, \StringBuilder $label)
    {
        $this->row   = $row;
        $this->label = $label;
    }

    /**
     * @return \ArrayObject
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @return \StringBuilder
     */
    public function getLabel()
    {
        return $this->label;
    }
}
