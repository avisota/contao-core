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
