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

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\ActionHandler\CopyHandler;
use ContaoCommunityAlliance\DcGeneral\Data\ModelId;
use ContaoCommunityAlliance\DcGeneral\Data\ModelIdInterface;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use ContaoCommunityAlliance\UrlBuilder\Contao\BackendUrlBuilder;
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
                array('handleCopyChilds'),
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
        $parentModelId          = ModelId::fromModel($parentModel);

        \Input::setGet('pid', $parentModelId->getSerialized());
    }

    /**
     * @param ActionEvent     $event
     * @param                 $name
     * @param EventDispatcher $eventDispatcher
     */
    public function handleCopyChilds(ActionEvent $event, $name, EventDispatcher $eventDispatcher)
    {
        if ($event->getAction()->getName() !== 'copyChilds' || !\Input::get('ctable')) {
            return;
        }

        $environment   = $event->getEnvironment();
        $inputProvider = $environment->getInputProvider();
        $modelId       = ModelId::fromSerialized($inputProvider->getParameter('id'));
        if ($environment->getDataDefinition()->getName() !== $inputProvider->getParameter('ctable')
            && $environment->getParentDataDefinition()->getName() !== $modelId->getDataProviderName()
        ) {
            $this->redirectToChildTable($environment);
        }

        $copiedParentModel = $this->copyParent($modelId, $environment);

        $this->copyEachChilds($modelId, $copiedParentModel, $environment);

        $this->redirectToChildTable($environment, true);
    }

    /**
     * @param EnvironmentInterface $environment
     * @param bool                 $doNotCopy
     */
    protected function redirectToChildTable(EnvironmentInterface $environment, $doNotCopy = false)
    {
        $redirectUrl = new BackendUrlBuilder();
        $redirectUrl
            ->setPath('contao/main.php')
            ->setQueryParameter('do', $environment->getInputProvider()->getParameter('do'));

        if ($doNotCopy === false) {
            $redirectUrl
                ->setQueryParameter('act', $environment->getInputProvider()->getParameter('act'))
                ->setQueryParameter('ctable', $environment->getInputProvider()->getParameter('ctable'))
                ->setQueryParameter('table', $environment->getInputProvider()->getParameter('ctable'))
                ->setQueryParameter('id', $environment->getInputProvider()->getParameter('id'))
                ->setQueryParameter('pid', $environment->getInputProvider()->getParameter('id'));
        }

        if ($doNotCopy) {
            $redirectUrl
                ->setQueryParameter('act', 'edit')
                ->setQueryParameter('id', $environment->getInputProvider()->getParameter('pid'));
        }

        $redirectEvent = new RedirectEvent($redirectUrl->getUrl());
        $environment->getEventDispatcher()->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $redirectEvent);
    }

    /**
     * @param ModelIdInterface     $modelId
     * @param EnvironmentInterface $environment
     *
     * @return ModelInterface
     */
    protected function copyParent(ModelIdInterface $modelId, EnvironmentInterface $environment)
    {
        $dataProvider       = $environment->getDataDefinition();
        $parentDataProvider = $environment->getParentDataDefinition();

        $copyEnvironment = clone $environment;
        $copyEnvironment->setDataDefinition($parentDataProvider);
        $copyEnvironment->setParentDataDefinition($dataProvider);

        $copyControllerEnvironment = $copyEnvironment->getController()->getEnvironment();
        $copyControllerEnvironment->setDataDefinition($parentDataProvider);
        $copyControllerEnvironment->setParentDataDefinition($dataProvider);

        return $this->copyHandler($modelId, $copyEnvironment);
    }

    /**
     * @param ModelIdInterface     $modelId
     * @param EnvironmentInterface $environment
     *
     * @return ModelInterface
     */
    protected function copyHandler(ModelIdInterface $modelId, EnvironmentInterface $environment)
    {
        $copyHandler = new CopyHandler();
        $copyHandler->setEnvironment($environment);

        return $copyHandler->copy($modelId);
    }

    /**
     * @param ModelIdInterface     $modelId
     * @param ModelInterface       $copiedModel
     * @param EnvironmentInterface $environment
     */
    protected function copyEachChilds(ModelIdInterface $modelId, ModelInterface $copiedModel, EnvironmentInterface $environment)
    {
        $childDataProviderName = $environment->getInputProvider()->getParameter('ctable');
        $dataProvider          = $environment->getDataProvider();
        $childDataProvider     = $environment->getDataProvider($childDataProviderName);
        $modelRelationship     = $environment->getParentDataDefinition()->getModelRelationshipDefinition();

        $childCondition = $modelRelationship->getChildCondition(
            $copiedModel->getProviderName(),
            $childDataProviderName
        );
        if (!$childCondition) {
            return;
        }

        $parentModel   = $dataProvider->fetch($dataProvider->getEmptyConfig()->setId($modelId->getId()));
        $parentFilters = $childCondition->getFilter($parentModel);
        $copiedFilters = $childCondition->getFilter($copiedModel);
        $filter        = array();
        // Todo can many filter has operation equal
        foreach ($parentFilters as $index => $parentFilter) {
            if ($parentFilter['operation'] !== '=') {
                continue;
            }

            $filter = array(
                'parent' => $parentFilter,
                'copied' => $copiedFilters[$index],
            );
        }

        $childModels = $childDataProvider->fetchAll(
            $childDataProvider->getEmptyConfig()->setFilter(array($filter['parent']))
        );
        if ($childModels->count() < 1) {
            return;
        }

        foreach ($childModels->getModelIds() as $index => $modelId) {
            $childModel  = $childModels->get($index);
            $copyModelId = ModelId::fromModel($childModel);

            $copiedChildModel = null;
            if ($environment->getDataDefinition()->getName() !== $copyModelId->getDataProviderName()) {
                $copiedChildModel = $this->copyParent($copyModelId, $environment);
            }

            if ($environment->getDataDefinition()->getName() === $copyModelId->getDataProviderName()) {
                $copiedChildModel = $this->copyHandler($copyModelId, $environment);
            }

            $copiedChildModel->setProperty(
                $filter['copied']['property'],
                $filter['copied']['value']
            );

            $childDataProvider->save($copiedChildModel);
        }
    }
}
