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

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetBreadcrumbEvent;
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
            GetBreadcrumbEvent::NAME => array(
                array('getBreadCrumb', 100)
            )
        );
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
            return;
        }

        $baseUrl = new UrlBuilder();
        $baseUrl->setPath('contao/main.php')
            ->setQueryParameter('do', 'avisota_config')
            ->setQueryParameter('ref', TL_REFERER_ID);

        $translator = $environment->getTranslator();

        $event->setElements(
            array(
                array(
                    'icon' => 'assets/avisota/core/images/avisota-breadcrumb.png',
                    'text' => $translator->translate('avisota_config.0', 'MOD'),
                    'url'  => $baseUrl->getUrl()
                )
            )

        );
    }
}
