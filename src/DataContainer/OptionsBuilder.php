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

use Avisota\Contao\Core\CoreEvents;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\DC_General;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * The options build event subscriber.
 */
class OptionsBuilder implements EventSubscriberInterface
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
            CoreEvents::CREATE_MAILING_LIST_OPTIONS         => 'createMailingListOptions',
            CoreEvents::CREATE_RECIPIENT_SOURCE_OPTIONS     => 'createRecipientSourceOptions',
            CoreEvents::CREATE_QUEUE_OPTIONS                => 'createQueueOptions',
            CoreEvents::CREATE_TRANSPORT_OPTIONS            => 'createTransportOptions',
            'avisota.create-gallery-options'                => 'createGalleryTemplateOptions',
            'avisota.create-reader-module-template-options' => 'createReaderModuleTemplateOptions',
        );
    }

    /**
     * Create the mailing list options.
     *
     * @param CreateOptionsEvent $event The event.
     *
     * @return void
     */
    public function createMailingListOptions(CreateOptionsEvent $event)
    {
        $this->getMailingListOptions($event->getOptions());
    }

    /**
     * Get the mailing list options.
     *
     * @param array $options The options.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function getMailingListOptions($options = array())
    {
        if (!is_array($options) && !$options instanceof \ArrayAccess) {
            $options = array();
        }
        $mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
        $mailingLists          = $mailingListRepository->findBy(array(), array('title' => 'ASC'));
        /** @var \Avisota\Contao\Entity\MailingList $mailingList */
        foreach ($mailingLists as $mailingList) {
            $options[$mailingList->getId()] = $mailingList->getTitle();
        }
        return $options;
    }

    /**
     * Create the recipient source options.
     *
     * @param CreateOptionsEvent $event The event.
     *
     * @return void
     */
    public function createRecipientSourceOptions(CreateOptionsEvent $event)
    {
        $this->getRecipientSourceOptions($event->getOptions());
    }

    /**
     * Get the recipient source options.
     *
     * @param array $options The options.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function getRecipientSourceOptions($options = array())
    {
        if (!is_array($options) && !$options instanceof \ArrayAccess) {
            $options = array();
        }
        $recipientSourceRepository = EntityHelper::getRepository('Avisota\Contao:RecipientSource');
        $recipientSources          = $recipientSourceRepository->findBy(array(), array('title' => 'ASC'));
        /** @var \Avisota\Contao\Entity\RecipientSource $recipientSource */
        foreach ($recipientSources as $recipientSource) {
            $options[$recipientSource->getId()] = $recipientSource->getTitle();
        }
        return $options;
    }

    /**
     * Create the queue options.
     *
     * @param CreateOptionsEvent $event The event.
     *
     * @return void
     */
    public function createQueueOptions(CreateOptionsEvent $event)
    {
        $this->getQueueOptions($event->getOptions());
    }

    /**
     * Get the queue options.
     *
     * @param array $options The options.
     *
     * @return array
     */
    public function getQueueOptions($options = array())
    {
        if (!is_array($options) && !$options instanceof \ArrayAccess) {
            $options = array();
        }
        $queueRepository = EntityHelper::getRepository('Avisota\Contao:Queue');
        $queues          = $queueRepository->findBy(array(), array('title' => 'ASC'));
        /** @var \Avisota\Contao\Entity\Queue $queue */
        foreach ($queues as $queue) {
            $options[$queue->getId()] = $queue->getTitle();
        }
        return $options;
    }

    public function bypassCreateTransportOptions()
    {
    }

    /**
     * Create the transport options.
     *
     * @param CreateOptionsEvent $event The event.
     *
     * @return void
     */
    public function createTransportOptions(CreateOptionsEvent $event)
    {
        $this->getTransportOptions($event->getOptions());
    }

    /**
     * Get the transport options.
     *
     * @param array $options The options.
     *
     * @return array
     */
    public function getTransportOptions($options = array())
    {
        if (!is_array($options) && !$options instanceof \ArrayAccess) {
            $options = array();
        }
        $transportRepository = EntityHelper::getRepository('Avisota\Contao:Transport');
        $transports          = $transportRepository->findBy(array(), array('title' => 'ASC'));
        /** @var \Avisota\Contao\Entity\Transport $transport */
        foreach ($transports as $transport) {
            $options[$transport->getId()] = $transport->getTitle();
        }
        return $options;
    }

    /**
     * Create the gallery template options.
     *
     * @param CreateOptionsEvent $event The event.
     *
     * @return void
     * Todo if this not in use (template engine contao => twig)
     */
    public function createGalleryTemplateOptions(CreateOptionsEvent $event)
    {
        $this->getGalleryTemplateOptions($event->getDataContainer(), $event->getOptions());
    }

    /**
     * Return all gallery templates as array
     *
     * @param DC_General|\DataContainer $dc
     * @param array                     $options
     *
     * @return array
     * @internal param $object
     * @SuppressWarnings(PHPMD.ShortVariable)
     * Todo if this not in use (template engine contao => twig)
     */
    public function getGalleryTemplateOptions(DC_General $dc, $options = array())
    {
        if (!is_array($options) && !$options instanceof \ArrayAccess) {
            $options = array();
        }

        // Get the page ID
        /** @noinspection PhpUndefinedMethodInspection */
        $article = \Database::getInstance()
            ->prepare("SELECT pid FROM tl_article WHERE id=?")
            ->limit(1)
            ->execute(
                $dc
                    ->getEnvironment()
                    ->getCurrentModel()
                    ->getProperty('pid')
            );

        // Inherit the page settings
        /** @noinspection PhpUndefinedMethodInspection */
        $page = $this->getPageDetails($article->pid);

        // Get the theme ID
        $layout = \Database::getInstance()
            ->prepare("SELECT pid FROM tl_layout WHERE id=?")
            ->limit(1)
            ->execute($page->layout);

        // Return all gallery templates
        /** @noinspection PhpUndefinedMethodInspection */
        $templateGroup = $this->getTemplateGroup('nl_gallery_', $layout->pid);

        foreach ($templateGroup as $key => $value) {
            $options[$key] = $value;
        }

        return $options;
    }

    /**
     * Create the render module template options.
     *
     * @param CreateOptionsEvent $event The event.
     *
     * @return void
     */
    public function createReaderModuleTemplateOptions(CreateOptionsEvent $event)
    {
        $options   = $event->getOptions();
        $templates = \TwigHelper::getTemplateGroup('avisota_reader_');

        foreach ($templates as $key => $value) {
            $options[$key] = $value;
        }
    }
}
