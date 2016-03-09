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

namespace Avisota\Contao\Core;

use Avisota\Contao\Core\Event\CreateFakeRecipientEvent;
use Avisota\Contao\Core\Event\CreatePublicEmptyRecipientEvent;
use Avisota\Recipient\Fake\FakeRecipient;
use Avisota\Recipient\MutableRecipient;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetSelectModeButtonsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An EventSubscriber knows himself what events he is interested in.
 * If an EventSubscriber is added to an EventDispatcherInterface, the manager invokes
 * {@link getSubscribedEvents} and registers the subscriber as a listener for all
 * returned events.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EventSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            CoreEvents::CREATE_FAKE_RECIPIENT => array(
                array('createFakeRecipient'),
            ),

            CoreEvents::CREATE_PUBLIC_EMPTY_RECIPIENT => array(
                array('createPublicEmptyRecipient'),
            ),

            GetSelectModeButtonsEvent::NAME => array(
                array('deactivateButtonsForEditAll'),
            ),
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

    /**
     * @param CreatePublicEmptyRecipientEvent $event
     */
    public function createPublicEmptyRecipient(CreatePublicEmptyRecipientEvent $event)
    {
        if ($event->getRecipient()) {
            return;
        }

        $event->setRecipient(new MutableRecipient('noreply@' . \Environment::get('host')));
    }

    /**
     * @param GetSelectModeButtonsEvent $event
     *
     * Todo remove this if the deactivated buttons correct worked
     */
    public function deactivateButtonsForEditAll(GetSelectModeButtonsEvent $event)
    {
        if ($event->getEnvironment()->getInputProvider()->getParameter('act') !== 'select') {
            return;
        }

        $buttons = $event->getButtons();

        foreach (
            array(
                'override',
                'edit'
            ) as $button
        ) {
            unset($buttons[$button]);
        }

        $event->setButtons($buttons);
    }
}
