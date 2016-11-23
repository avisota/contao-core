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

use Bit3\StringBuilder\StringBuilder;
use Symfony\Component\EventDispatcher\Event;

/**
 * The mailing list create label event.
 */
class MailingListCreateLabelEvent extends Event
{
    const NAME = 'Avisota\Contao\Core\Event\MailingListCreateLabel';

    /**
     * The row date.
     *
     * @var \ArrayObject
     */
    protected $row;

    /**
     * The label.
     *
     * @var StringBuilder
     */
    protected $label;

    /**
     * MailingListCreateLabelEvent constructor.
     *
     * @param \ArrayObject  $row   The row data.
     * @param StringBuilder $label The label.
     */
    public function __construct(\ArrayObject $row, StringBuilder $label)
    {
        $this->row   = $row;
        $this->label = $label;
    }

    /**
     * Return the row data.
     *
     * @return \ArrayObject
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Return the label.
     *
     * @return StringBuilder
     */
    public function getLabel()
    {
        return $this->label;
    }
}
