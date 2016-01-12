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

namespace Avisota\Contao\Core;

use Avisota\Contao\Core\Event\CreateFakeRecipientEvent;
use Avisota\Contao\Core\Event\CreatePublicEmptyRecipientEvent;
use Avisota\Recipient\Fake\FakeRecipient;
use Avisota\Recipient\MutableRecipient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    static public function getSubscribedEvents()
    {
        return array(
            CoreEvents::CREATE_FAKE_RECIPIENT         => 'createFakeRecipient',
            CoreEvents::CREATE_PUBLIC_EMPTY_RECIPIENT => 'createPublicEmptyRecipient',
        );
    }

    /**
     * Create a new fake recipient, if no one is created yet.
     *
     * @param CreateFakeRecipientEvent $event
     */
    public function createFakeRecipient(CreateFakeRecipientEvent $event)
    {
        if ($event->getRecipient()) {
            return;
        }

        $locale = null;
        if ($event->getMessage()) {
            $locale = $event->getMessage()->getLanguage();
        }

        $event->setRecipient(new FakeRecipient($locale));
    }

    public function createPublicEmptyRecipient(CreatePublicEmptyRecipientEvent $event)
    {
        if ($event->getRecipient()) {
            return;
        }

        $environment = \Environment::getInstance();

        $event->setRecipient(new MutableRecipient('noreply@' . $environment->host));
    }
}
