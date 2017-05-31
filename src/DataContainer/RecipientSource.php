<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2017 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2017
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
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetBreadcrumbEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelId;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * The recipient source event subscriber.
 */
class RecipientSource implements EventSubscriberInterface
{
    /**
     * The recipient source instance.
     *
     * @var RecipientSource
     */
    static protected $instance;

    /**
     * Get the recipient source instance.
     *
     * @return RecipientSource
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
            EncodePropertyValueFromWidgetEvent::NAME => array(
                array('checkCsvColumnUnique'),
                array('checkCsvColumnEmail'),
            ),

            DcGeneralEvents::ACTION => array(
                array('handleAction'),
            ),

            GetBreadcrumbEvent::NAME => array(
                array('getBreadCrumb')
            )
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
     * Check if all columns in the csv file is unique.
     *
     * @param EncodePropertyValueFromWidgetEvent $event The event.
     *
     * @return void
     */
    public function checkCsvColumnUnique(EncodePropertyValueFromWidgetEvent $event)
    {
        $environment  = $event->getEnvironment();
        $dataProvider = $environment->getDataProvider();

        if (($dataProvider !== 'orm_avisota_recipient_source')
            || ($dataProvider === 'orm_avisota_recipient_source'
                && $event->getProperty() != 'csvColumnAssignment')
        ) {
            return;
        }

        $value = $event->getValue();

        if (!is_array($value)) {
            $value = deserialize($value, true);
        }

        $columns = array();
        $fields  = array();

        $translator = $environment->getTranslator();

        foreach ($value as $item) {
            if (in_array($item['column'], $columns)
                || in_array($item['field'], $fields)
            ) {
                throw new \RuntimeException(
                    $translator->translate('duplicated_column', 'orm_avisota_recipient_source')
                );
            }

            $columns[] = $item['column'];
            $fields[]  = $item['field'];
        }
    }

    /**
     * Check if column email exists in the csv file.
     *
     * @param EncodePropertyValueFromWidgetEvent $event The event.
     *
     * @return void
     */
    public function checkCsvColumnEmail(EncodePropertyValueFromWidgetEvent $event)
    {
        $environment  = $event->getEnvironment();
        $dataProvider = $environment->getDataProvider();

        if (($dataProvider !== 'orm_avisota_recipient_source')
            || ($dataProvider === 'orm_avisota_recipient_source'
                && $event->getProperty() != 'csvColumnAssignment')
        ) {
            return;
        }

        $value = $event->getValue();

        if (!is_array($value)) {
            $value = deserialize($value, true);
        }

        foreach ($value as $item) {
            if ($item['field'] == 'email') {
                return;
            }
        }

        $translator = $environment->getTranslator();

        throw new \RuntimeException(
            $translator->translate('missing_email_column', 'orm_avisota_recipient_source')
        );
    }

    /**
     * Handle action list recipient source.
     *
     * @param ActionEvent $event The event.
     *
     * @return void
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
     * Parse the mailing list list.
     *
     * @param EnvironmentInterface $environment The environment.
     *
     * @return string
     *
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
     * Get the recipient source column.
     *
     * @return array
     *
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
     * Get the recipient source columns filter.
     *
     * @return array
     *
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
     * Get the member columns filter.
     *
     * @return array
     *
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

    /**
     * Get the bread crumb elements.
     *
     * @param GetBreadcrumbEvent $event This event.
     *
     * @return void
     */
    public function getBreadCrumb(GetBreadcrumbEvent $event)
    {
        $environment    = $event->getEnvironment();
        $dataDefinition = $environment->getDataDefinition();
        $inputProvider  = $environment->getInputProvider();

        if ('orm_avisota_recipient_source' !== $dataDefinition->getName()) {
            return;
        }

        $elements = $event->getElements();

        $rootUrlBuilder = new UrlBuilder();
        $rootUrlBuilder->setPath('contao/main.php')
            ->setQueryParameter('do', $inputProvider->getParameter('do'))
            ->setQueryParameter('ref', TL_REFERER_ID);

        $translator = $environment->getTranslator();

        $elements[] = array(
            'icon' => 'assets/avisota/core/images/recipient_source.png',
            'text' => $translator->translate('avisota_recipient_source.0', 'MOD'),
            'url'  => $rootUrlBuilder->getUrl()
        );

        if (false === $inputProvider->hasParameter('id')) {
            $event->setElements($elements);

            return;
        }

        $modelId = ModelId::fromSerialized($inputProvider->getParameter('id'));

        $dataProvider = $environment->getDataProvider($modelId->getDataProviderName());
        $repository   = $dataProvider->getEntityRepository();

        $entity = $repository->findOneBy(array('id' => $modelId->getId()));

        $entityUrlBuilder = new UrlBuilder();
        $entityUrlBuilder->setPath('contao/main.php')
            ->setQueryParameter('do', $inputProvider->getParameter('do'))
            ->setQueryParameter('act', $inputProvider->getParameter('act'))
            ->setQueryParameter('id', $inputProvider->getParameter('id'))
            ->setQueryParameter('ref', TL_REFERER_ID);

        $elements[] = array(
            'icon' => 'assets/avisota/core/images/recipient_source.png',
            'text' => $entity->getTitle(),
            'url'  => $entityUrlBuilder->getUrl()
        );

        $event->setElements($elements);
    }
}
