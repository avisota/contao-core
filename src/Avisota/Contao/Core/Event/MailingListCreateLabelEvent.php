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

use Avisota\Contao\Core\Message\Renderer;
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

    /**
     * MailingListCreateLabelEvent constructor.
     *
     * @param \ArrayObject   $row
     * @param \StringBuilder $label
     */
    public function __construct(\ArrayObject $row, \StringBuilder $label)
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
