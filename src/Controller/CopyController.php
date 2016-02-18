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

namespace Avisota\Contao\Core\Controller;

use ContaoCommunityAlliance\DcGeneral\Data\ModelId;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CopyController
 *
 * @package Avisota\Contao\Core\Controller
 */
class CopyController implements EventSubscriberInterface
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
            DcGeneralEvents::ACTION => array(
                array('handleEdit', 20),
            ),
        );
    }

    /**
     * @param ActionEvent     $event
     * @param                 $name
     * @param EventDispatcher $eventDispatcher
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.LongVariables)
     */
    public function handleEdit(ActionEvent $event, $name, EventDispatcher $eventDispatcher)
    {
        if ($event->getAction()->getName() != 'edit' || \Input::get('pid')) {
            return;
        }

        $basicDefinition = $event->getEnvironment()->getDataDefinition()->getBasicDefinition();
        if (!$basicDefinition->getParentDataProvider()) {
            return;
        }

        $modelId                     = ModelId::fromSerialized(\Input::get('id'));
        $environment                 = $event->getEnvironment();
        $dataProvider                = $environment->getDataProvider($modelId->getDataProviderName());
        $model                       = $dataProvider->fetch($dataProvider->getEmptyConfig()->setId($modelId->getId()));
        $modelRelationshipDefinition = $environment->getDataDefinition()->getModelRelationshipDefinition();
        $childCondition              = $modelRelationshipDefinition->getChildCondition(
            $environment->getParentDataDefinition()->getName(),
            $modelId->getDataProviderName()
        );

        $parentProperty = null;
        foreach ($childCondition->getFilter($model) as $filter) {
            if ($filter['operation'] !== '=') {
                continue;
            }

            $parentProperty = $filter['property'];
        }

        $parentPropertyMethod   = 'get' . ucfirst($parentProperty);
        $parentDataProviderName = $model->getEntity()->$parentPropertyMethod()->entityTableName();
        $parentDataProvider     = $environment->getDataProvider($parentDataProviderName);
        $parentModel            = $parentDataProvider->fetch(
            $parentDataProvider->getEmptyConfig()->setId($model->getEntity()->$parentPropertyMethod()->getId())
        );
        $parentModelId = ModelId::fromModel($parentModel);

        \Input::setGet('pid', $parentModelId->getSerialized());
    }
}
