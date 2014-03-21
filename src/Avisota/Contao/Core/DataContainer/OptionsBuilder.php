<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\DataContainer;

use Avisota\Contao\Core\CoreEvents;
use Avisota\Contao\Subscription\Event\CollectSubscriptionListsEvent;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\DC_General;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OptionsBuilder implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	static public function getSubscribedEvents()
	{
		return array(
			CoreEvents::CREATE_MAILING_LIST_OPTIONS              => 'createMailingListOptions',
			CoreEvents::CREATE_RECIPIENT_SOURCE_OPTIONS          => 'createRecipientSourceOptions',
			CoreEvents::CREATE_QUEUE_OPTIONS                     => 'createQueueOptions',
			CoreEvents::CREATE_TRANSPORT_OPTIONS                 => 'createTransportOptions',
			'avisota.create-gallery-options'                     => 'createGalleryTemplateOptions',
			'avisota.create-reader-module-template-options'      => 'createReaderModuleTemplateOptions',
		);
	}

	public function createMailingListOptions(CreateOptionsEvent $event)
	{
		$this->getMailingListOptions($event->getOptions());
	}

	public function getMailingListOptions($options = array())
	{
		$mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
		$mailingLists          = $mailingListRepository->findBy(array(), array('title' => 'ASC'));
		/** @var \Avisota\Contao\Entity\MailingList $mailingList */
		foreach ($mailingLists as $mailingList) {
			$options[$mailingList->getId()] = $mailingList->getTitle();
		}
		return $options;
	}

	public function createRecipientSourceOptions(CreateOptionsEvent $event)
	{
		$this->getRecipientSourceOptions($event->getOptions());
	}

	public function getRecipientSourceOptions($options = array())
	{
		$recipientSourceRepository = EntityHelper::getRepository('Avisota\Contao:RecipientSource');
		$recipientSources          = $recipientSourceRepository->findBy(array(), array('title' => 'ASC'));
		/** @var \Avisota\Contao\Entity\RecipientSource $recipientSource */
		foreach ($recipientSources as $recipientSource) {
			$options[$recipientSource->getId()] = $recipientSource->getTitle();
		}
		return $options;
	}

	public function createQueueOptions(CreateOptionsEvent $event)
	{
		$this->getQueueOptions($event->getOptions());
	}

	public function getQueueOptions($options = array())
	{
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

	public function createTransportOptions(CreateOptionsEvent $event)
	{
		$this->getTransportOptions($event->getOptions());
	}

	public function getTransportOptions($options = array())
	{
		$transportRepository = EntityHelper::getRepository('Avisota\Contao:Transport');
		$transports          = $transportRepository->findBy(array(), array('title' => 'ASC'));
		/** @var \Avisota\Contao\Entity\Transport $transport */
		foreach ($transports as $transport) {
			$options[$transport->getId()] = $transport->getTitle();
		}
		return $options;
	}

	public function createGalleryTemplateOptions(CreateOptionsEvent $event)
	{
		$this->getGalleryTemplateOptions($event->getDataContainer(), $event->getOptions());
	}

	/**
	 * Return all gallery templates as array
	 *
	 * @param object
	 *
	 * @return array
	 */
	public function getGalleryTemplateOptions(DC_General $dc, $options = array())
	{
		// Get the page ID
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
		$page = $this->getPageDetails($article->pid);

		// Get the theme ID
		$layout = \Database::getInstance()
			->prepare("SELECT pid FROM tl_layout WHERE id=?")
			->limit(1)
			->execute($page->layout);

		// Return all gallery templates
		$templateGroup = $this->getTemplateGroup('nl_gallery_', $layout->pid);

		foreach ($templateGroup as $key => $value) {
			$options[$key] = $value;
		}

		return $options;
	}

	public function createReaderModuleTemplateOptions(CreateOptionsEvent $event)
	{
		$options   = $event->getOptions();
		$templates = \TwigHelper::getTemplateGroup('avisota_reader_');

		foreach ($templates as $key => $value) {
			$options[$key] = $value;
		}
	}
}
