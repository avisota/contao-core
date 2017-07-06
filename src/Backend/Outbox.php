<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2017 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2017
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author	   Oliver Willmes <info@oliverwillmes.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Backend;

use Avisota\Contao\Entity\Queue;
use Avisota\Queue\QueueInterface;
use Contao\Doctrine\ORM\EntityHelper;
use Contao\Environment;
use Contao\Request;
use Contao\RequestToken;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\InputProvider;
use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A BackendModule implementation that use Twig as template engine.
 */
class Outbox extends \TwigBackendModule
{
    /**
     * Is outbox empty.
     *
     * @return bool
     */
    public static function isEmpty()
    {
        global $container;

        $length = 0;

        $queueRepository     = EntityHelper::getRepository('Avisota\Contao:Queue');
        $queueDataCollection = $queueRepository->findAll();

        /** @var QueueInterface $queue */
        /** @var Queue $queueData */
        foreach ($queueDataCollection as $queueData) {
            $serviceName = sprintf('avisota.queue.%s', $queueData->getId());
            if ($container->offsetExists($serviceName)) {
                $queue  = $container[$serviceName];
                $length += $queue->length();
            }
        }

        return $length == 0;
    }

    /**
     * The template.
     *
     * @var string
     */
    protected $strTemplate = 'avisota/backend/outbox';

    /**
     * Compile the current element.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function compile()
    {
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('avisota_outbox')
        );

        $queueRepository = EntityHelper::getRepository('Avisota\Contao:Queue');

        $this->sendImmediate();
        $this->executeQueue($queueRepository);
        $this->addQueuesToTemplate($queueRepository);
    }

    protected function sendImmediate()
    {
        $translator = $GLOBALS['container']['translator'];
        $inputProvider = new InputProvider();

        if ((false === $inputProvider->hasParameter('send'))
            || ('immediate' !== $inputProvider->getParameter('send'))
        ) {
            return;
        }

        $requestUrlBuilder = new UrlBuilder();
        $requestUrlBuilder->setHost(Environment::get('url'))
            ->setPath('system/modules/avisota-message/web/send_immediate.php')
            ->setQueryParameter('id', $inputProvider->getParameter('id'))
            ->setQueryParameter('ref', RequestToken::get());
        foreach (array('action', 'turn', 'loop') as $requestParameter) {
            if (false === $inputProvider->hasParameter($requestParameter)) {
                continue;
            }

            $requestUrlBuilder->setQueryParameter($requestParameter, $inputProvider->getParameter($requestParameter));
        }

        $request = new Request();
        $request->send($requestUrlBuilder->getUrl());

        $redirectParameters = json_decode($request->response);

        $redirectUrlBuilder = new UrlBuilder();
        $redirectUrlBuilder->setPath('contao/main.php')
            ->setQueryParameter('do', $inputProvider->getParameter('do'))
            ->setQueryParameter('rf', RequestToken::get());

        foreach ($redirectParameters as $redirectParameter => $redirectValue) {
            if (in_array($redirectParameter, array('turnCount', 'maxCount'))) {
                continue;
            }

            $redirectUrlBuilder->setQueryParameter($redirectParameter, $redirectValue);
        }

        if (null === $redirectParameters->execute) {
            $redirectUrlBuilder->setQueryParameter('send', $inputProvider->getParameter('send'));

            $GLOBALS['TL_MOOTOOLS'][] = sprintf(
                '<script type="text/javascript">AjaxRequest.displayBox("%s")</script>',
                sprintf(
                    $translator->translate('avisota_outbox.newsletter.prepared.shipping'),
                    $redirectParameters->turnCount,
                    $redirectParameters->maxCount
                )
            );
        }

        $GLOBALS['TL_MOOTOOLS'][] = sprintf(
            '<script type="text/javascript">
                setTimeout(function() {
                    location.href = "%s"
                }, 600)
            </script>',
            $redirectUrlBuilder->getUrl()
        );
    }

    /**
     * Execute the queue.
     *
     * @param EntityRepository $queueRepository The queue repository.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function executeQueue(EntityRepository $queueRepository)
    {
        global $container;


        $executeId = \Input::get('execute');
        if ($executeId) {
            /** @var Queue $queueData */
            $queueData = $queueRepository->find($executeId);

            if (!$queueData->getAllowManualSending()) {
                return;
            }

            $serviceName = sprintf('avisota.queue.%s', $queueData->getId());
            /** @var QueueInterface $queue */
            $queue = $container[$serviceName];

            $this->Template->setName('avisota/backend/outbox_execute');
            $this->Template->queue  = $queue;
            $this->Template->config = $queueData;

            $GLOBALS['TL_CSS'][]        = 'assets/avisota/core/css/be_outbox.css';
            $GLOBALS['TL_JAVASCRIPT'][] = 'assets/avisota/core/js/Number.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'assets/avisota/core/js/be_outbox.js';
        }
    }

    /**
     * Add queues to the template.
     *
     * @param EntityRepository $queueRepository The queue repository.
     *
     * @return void
     */
    protected function addQueuesToTemplate(EntityRepository $queueRepository)
    {
        global $container;

        /** @var Queue[] $queueDataCollection */
        $queueDataCollection = $queueRepository->findAll();
        $items               = array();

        /** @var QueueInterface $queue */
        foreach ($queueDataCollection as $queueData) {
            $serviceName = sprintf('avisota.queue.%s', $queueData->getId());
            $queue       = $container[$serviceName];

            $item['meta']  = $queueData;
            $item['queue'] = $queue;

            $items[] = $item;
        }

        $this->Template->items = $items;
    }
}
