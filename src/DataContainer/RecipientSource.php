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

namespace Avisota\Contao\Core\DataContainer;

use Avisota\RecipientSource\RecipientSourceInterface;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\EncodePropertyValueFromWidgetEvent;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetSelectModeButtonsEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelId;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;

use Symfony\Component\EventDispatcher\EventDispatcher;
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
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RecipientSource implements EventSubscriberInterface
{
    static protected $instance;

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

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
            //Todo check who is this
            EncodePropertyValueFromWidgetEvent::NAME . '[orm_avisota_recipient_source][csvColumnAssignment]' => array(
                array('checkCsvColumnUnique'),
                array('checkCsvColumnEmail'),
            ),

            DcGeneralEvents::ACTION => 'handleAction',

            GetSelectModeButtonsEvent::NAME => array(
                array('deactivateSelectButtons'),
            ),
        );
    }

    /**
     * RecipientSource constructor.
     */
    public function __construct()
    {
        static::$instance = $this;
    }

    /**
     * @param EncodePropertyValueFromWidgetEvent $event
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function checkCsvColumnUnique(EncodePropertyValueFromWidgetEvent $event)
    {
        $value = $event->getValue();

        if (!is_array($value)) {
            $value = deserialize($value, true);
        }

        $columns = array();
        $fields  = array();

        foreach ($value as $item) {
            if (in_array($item['column'], $columns)
                || in_array($item['field'], $fields)
            ) {
                throw new \RuntimeException($GLOBALS['TL_LANG']['orm_avisota_recipient_source']['duplicated_column']);
            }

            $columns[] = $item['column'];
            $fields[]  = $item['field'];
        }

    }

    /**
     * @param EncodePropertyValueFromWidgetEvent $event
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function checkCsvColumnEmail(EncodePropertyValueFromWidgetEvent $event)
    {
        $value = $event->getValue();

        if (!is_array($value)) {
            $value = deserialize($value, true);
        }

        foreach ($value as $item) {
            if ($item['field'] == 'email') {
                return;
            }
        }

        throw new \RuntimeException($GLOBALS['TL_LANG']['orm_avisota_recipient_source']['missing_email_column']);
    }

    /**
     * @param ActionEvent $event
     */
    public function handleAction(ActionEvent $event)
    {
        $environment = $event->getEnvironment();

        if ($environment->getDataDefinition()->getName() != 'orm_avisota_recipient_source') {
            return;
        }

        $action = $event->getAction();

        if ($action->getName() == 'list') {
            $response = $this->handleListAction($environment);
            $event->setResponse($response);
        }
    }

    /**
     * @param EnvironmentInterface $environment
     *
     * @return string
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function handleListAction(EnvironmentInterface $environment)
    {
        $input      = $environment->getInputProvider();
        $id         = ModelId::fromSerialized($input->getParameter('id'));
        $repository = EntityHelper::getRepository('Avisota\Contao:RecipientSource');

        /** @var \Avisota\Contao\Entity\RecipientSource $recipientSourceEntity */
        $recipientSourceEntity = $repository->find($id->getId());

        /** @var RecipientSourceInterface $recipientSource */
        $recipientSource =
            $GLOBALS['container'][sprintf('avisota.recipientSource.%s', $recipientSourceEntity->getId())];

        if ($input->getValue('page')) {
            /** @var EventDispatcher $eventDispatcher */
            $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

            $addToUrlEvent = new AddToUrlEvent('page=' . (int) $input->getValue('page'));
            $eventDispatcher->dispatch(ContaoEvents::BACKEND_ADD_TO_URL, $addToUrlEvent);

            $redirectEvent = new RedirectEvent($addToUrlEvent->getUrl());
            $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $redirectEvent);
        }

        $total      = $recipientSource->countRecipients();
        $page       = $input->getParameter('page') ?: 0;
        $pages      = ceil($total / 30);
        $recipients = $recipientSource->getRecipients(30, $page * 30);

        $template             = new \TwigBackendTemplate('avisota/backend/recipient_source_list');
        $template->total      = $total;
        $template->page       = $page;
        $template->pages      = $pages;
        $template->recipients = $recipients;

        return $template->parse();
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getRecipientColumns()
    {
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('orm_avisota_recipient')
        );
        $eventDispatcher->dispatch(
            ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER,
            new LoadDataContainerEvent('orm_avisota_recipient')
        );

        $options = array();

        foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $k => $v) {
            if ($v['eval']['importable']) {
                $options[$k] = $v['label'][0];
            }
        }
        asort($options);

        return $options;
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getRecipientFilterColumns()
    {
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('orm_avisota_recipient')
        );
        $eventDispatcher->dispatch(
            ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER,
            new LoadDataContainerEvent('orm_avisota_recipient')
        );

        $options = array();

        foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $k => $v) {
            $options[$k] = $v['label'][0] . ' (' . $k . ')';
        }
        asort($options);

        return $options;
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getMemberFilterColumns()
    {
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('tl_member')
        );
        $eventDispatcher->dispatch(
            ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER,
            new LoadDataContainerEvent('tl_member')
        );

        $options = array();

        foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k => $v) {
            $options[$k] = $v['label'][0] . ' (' . $k . ')';
        }
        asort($options);

        return $options;
    }

    public function deactivateSelectButtons(GetSelectModeButtonsEvent $event)
    {
        if ($event->getEnvironment()->getInputProvider()->getParameter('act') !== 'select'
            || $event->getEnvironment()->getDataDefinition()->getName() !== 'orm_avisota_recipient_source'
        ) {
            return;
        }

        $buttons = $event->getButtons();

        foreach (
            array(
                'cut',
            ) as $button
        ) {
            unset($buttons[$button]);
        }

        $event->setButtons($buttons);
    }
}
