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

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\DataDefinition\Definition\Contao2BackendViewDefinitionInterface;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetBreadcrumbEvent;
use ContaoCommunityAlliance\DcGeneral\Factory\Event\BuildDataDefinitionEvent;
use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * The data container core event subscriber
 */
class Core implements EventSubscriberInterface
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
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            BuildDataDefinitionEvent::NAME => array(
                array('parseModelCommands')
            ),

            GetBreadcrumbEvent::NAME => array(
                array('getBreadCrumb', 100)
            ),

            ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER => array(
                array('removeHasteOnDeleteCallback')
            )
        );
    }

    /**
     * Parse model commands for edit and editheader.
     * Give in parent list view the header edit button the right action edit.
     *
     * @param BuildDataDefinitionEvent $event The event.
     *
     * @return void
     *
     * TODO remove this if dc general handle it.
     */
    public function parseModelCommands(BuildDataDefinitionEvent $event)
    {
        $container = $event->getContainer();

        if (false === strpos($container->getName(), 'orm_avisota_')) {
            return;
        }

        $backendView = $container->getDefinition(Contao2BackendViewDefinitionInterface::NAME);

        $modelCommands = $backendView->getModelCommands();
        if (false === $modelCommands->hasCommandNamed('editheader')) {
            return;
        }

        $editChildes = $modelCommands->getCommandNamed('edit');
        $editChildes->setName('editChildes');

        $editChildes = $modelCommands->getCommandNamed('editheader');
        $editChildes->setName('edit');
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
        $environment   = $event->getEnvironment();
        $inputProvider = $environment->getInputProvider();

        if (false === strpos($inputProvider->getParameter('do'), 'avisota_')) {
            return;
        }

        $elements = array(
            array(
                'icon' => 'assets/avisota/core/images/avisota-breadcrumb.png',
                'text' => 'Avisota',
                'url'  => $inputProvider->getRequestUrl()
            )
        );

        if (!in_array(
            $inputProvider->getParameter('do'),
            array(
                'avisota_transport',
                'avisota_queue',
                'avisota_salutation',
                'avisota_recipient_source',
                'avisota_mailing_list',
                'avisota_theme'
            ),
            null
        )
        ) {
            $event->setElements($elements);

            return;
        }

        $baseUrl = new UrlBuilder();
        $baseUrl->setPath('contao/main.php')
            ->setQueryParameter('do', 'avisota_config')
            ->setQueryParameter('ref', TL_REFERER_ID);

        $translator = $environment->getTranslator();

        $elements[] = array(
            'icon' => 'assets/avisota/core/images/settings.png',
            'text' => $translator->translate('avisota_config.0', 'MOD'),
            'url'  => $baseUrl->getUrl()
        );

        $event->setElements($elements);
    }

    /**
     * Fix for on delete callback.
     * Remove haste on delete callback.
     *
     * @param LoadDataContainerEvent $event The event.
     *
     * @return void
     *
     * Todo if pull request 94 for haste merged, can remove this event.
     * @see https://github.com/codefog/contao-haste/pull/94
     */
    public function removeHasteOnDeleteCallback(LoadDataContainerEvent $event)
    {
        if (!stristr($event->getName(), 'avisota')
            || !array_key_exists('ondelete_callback', $GLOBALS['TL_DCA'][$event->getName()]['config'])
        ) {
            return;
        }

        $callbacks = $GLOBALS['TL_DCA'][$event->getName()]['config']['ondelete_callback'];
        foreach ($callbacks as $index => $callback) {
            if (!in_array('Haste\Model\Relations', $callback)
                || !in_array('cleanRelatedRecords', $callback)
            ) {
                continue;
            }

            unset($GLOBALS['TL_DCA'][$event->getName()]['config']['ondelete_callback'][$index]);
        }
    }
}
