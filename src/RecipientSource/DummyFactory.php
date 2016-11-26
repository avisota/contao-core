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

namespace Avisota\Contao\Core\RecipientSource;

use Avisota\Contao\Core\CoreEvents;
use Avisota\Contao\Core\Event\CreateRecipientSourceEvent;
use Avisota\Contao\Entity\RecipientSource;
use Avisota\RecipientSource\Dummy;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The dummy factory.
 */
class DummyFactory implements RecipientSourceFactoryInterface
{
    /**
     * Create the recipient source.
     *
     * @param RecipientSource $recipientSourceData The recipient source data.
     *
     * @return \Avisota\RecipientSource\RecipientSourceInterface
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function createRecipientSource(RecipientSource $recipientSourceData)
    {
        $recipientSourceDummy =
            new Dummy($recipientSourceData->getDummyMinCount(), $recipientSourceData->getDummyMaxCount());

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $event = new CreateRecipientSourceEvent($recipientSourceData, $recipientSourceDummy);
        $eventDispatcher->dispatch(CoreEvents::CREATE_RECIPIENT_SOURCE, $event);

        return $event->getRecipientSource();
    }
}
