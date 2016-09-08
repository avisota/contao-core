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

namespace Avisota\Contao\Core\Backend;

use Avisota\Contao\Core\Event\MailingListCreateLabelEvent;

use Bit3\StringBuilder\StringBuilder;
use Doctrine\ORM\Query;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class MailingList
 *
 * @package Avisota\Contao\Core\DataContainer
 */
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
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * todo handle this by event
     */
    public function getLabel($rowData, $label, $dc)
    {
        $label = new StringBuilder('<div style="padding: 3px 0;"><strong>' . $label . '</strong></div>');

        $event = new MailingListCreateLabelEvent(new \ArrayObject($rowData), $label);

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];
        $eventDispatcher->dispatch(MailingListCreateLabelEvent::NAME, $event);

        return (string) $label;
    }
}
