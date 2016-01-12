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

namespace Avisota\Contao\Core\DataContainer;

use Avisota\Contao\Core\Event\MailingListCreateLabelEvent;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\ORM\Query;

class MailingList extends \Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array          $rowData
     * @param string         $label
     * @param \DataContainer $dc
     *
     * @return string
     */
    public function getLabel($rowData, $label, $dc)
    {
        $label = new \StringBuilder('<div style="padding: 3px 0;"><strong>' . $label . '</strong></div>');

        $event = new MailingListCreateLabelEvent(new \ArrayObject($rowData), $label);

        /** @var EventDispatcherInt $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];
        $eventDispatcher->dispatch(MailingListCreateLabelEvent::NAME, $event);

        return (string) $label;
    }
}
