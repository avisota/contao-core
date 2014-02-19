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

use Avisota\Contao\Core\Event\CollectSubscriptionListsEvent;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use DcGeneral\DC_General;
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
			'avisota.create-mailing-list-options'                => 'createMailingListOptions',
			'avisota.create-recipient-source-options'            => 'createRecipientSourceOptions',
			'avisota.create-queue-options'                       => 'createQueueOptions',
			'avisota.create-transport-options'                   => 'createTransportOptions',

			'avisota.create-editable-recipient-field-options'    => 'createEditableRecipientFieldOptions',
			'avisota.create-gallery-options'                     => 'createGalleryTemplateOptions',
			'avisota.create-importable-recipient-field-options'  => 'createImportableRecipientFieldOptions',
			'avisota.create-reader-module-template-options'      => 'createReaderModuleTemplateOptions',
			'avisota.create-recipient-field-options'             => 'createRecipientFieldOptions',
			'avisota.create-recipient-options'                   => 'createRecipientOptions',
			'avisota.create-subscribe-module-template-options'   => 'createSubscribeModuleTemplateOptions',
			'avisota.create-subscription-list-options'           => 'createSubscriptionListOptions',
			'avisota.create-subscription-template-options'       => 'createSubscriptionModuleTemplateOptions',
			'avisota.create-unsubscribe-module-template-options' => 'createUnsubscribeModuleTemplateOptions',
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



	public function createEditableRecipientFieldOptions(CreateOptionsEvent $event)
	{
		$this->getImportableRecipientFieldOptions($event->getOptions());
	}

	public function getEditableRecipientFieldOptions($options = array())
	{
		$this->loadLanguageFile('orm_avisota_recipient');
		$this->loadDataContainer('orm_avisota_recipient');

		foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $fieldName => $fieldConfig) {
			if ($fieldConfig['eval']['feEditable']) {
				$options[$fieldName] = $fieldConfig['label'][0];
			}
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
		$layout = $this->Database
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

	public function createImportableRecipientFieldOptions(CreateOptionsEvent $event)
	{
		$this->getImportableRecipientFieldOptions($event->getOptions());
	}

	public function getImportableRecipientFieldOptions($options = array())
	{
		$this->loadLanguageFile('orm_avisota_recipient');
		$this->loadDataContainer('orm_avisota_recipient');

		foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $fieldName => $fieldConfig) {
			if ($fieldConfig['eval']['importable']) {
				$options[$fieldName] = $fieldConfig['label'][0];
			}
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

	public function createRecipientFieldOptions(CreateOptionsEvent $event)
	{
		$this->getRecipientFieldOptions($event->getOptions());
	}

	public function getRecipientFieldOptions($options = array())
	{
		$this->loadLanguageFile('orm_avisota_recipient');
		$this->loadDataContainer('orm_avisota_recipient');

		foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $fieldName => $fieldConfig) {
			if (!empty($fieldConfig['inputType'])) {
				$options[$fieldName] = $fieldConfig['label'][0];
			}
		}

		return $options;
	}

	public function createRecipientOptions(CreateOptionsEvent $event)
	{
		$this->getRecipientOptions($event->getOptions());
	}

	public function getRecipientOptions($options = array())
	{
		$recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');
		$recipients          = $recipientRepository->findBy(
			array(),
			array('forename' => 'ASC', 'surname' => 'ASC', 'email' => 'ASC')
		);
		/** @var \Avisota\Contao\Entity\Recipient $recipient */
		foreach ($recipients as $recipient) {
			if ($recipient->getForename() && $recipient->getSurname()) {
				$options[$recipient->getId()] = sprintf(
					'%s, %s &lt;%s&gt;',
					$recipient->getSurname(),
					$recipient->getForename(),
					$recipient->getEmail()
				);
			}
			else if ($recipient->getForename()) {
				$options[$recipient->getId()] = sprintf(
					'%s &lt;%s&gt;',
					$recipient->getForename(),
					$recipient->getEmail()
				);
			}
			else if ($recipient->getSurname()) {
				$options[$recipient->getId()] = sprintf(
					'%s &lt;%s&gt;',
					$recipient->getSurname(),
					$recipient->getEmail()
				);
			}
			else {
				$options[$recipient->getId()] = $recipient->getEmail();
			}
		}
		return $options;
	}

	public function createSubscribeModuleTemplateOptions(CreateOptionsEvent $event)
	{
		$options   = $event->getOptions();
		$templates = \TwigHelper::getTemplateGroup('avisota_subscribe_');

		foreach ($templates as $key => $value) {
			$options[$key] = $value;
		}
	}

	public function createSubscriptionListOptions(CreateOptionsEvent $event)
	{
		$this->getSubscriptionListOptions($event->getOptions());
	}

	public function getSubscriptionListOptions($options = array())
	{
		global $container;

		if (!$options instanceof \ArrayObject) {
			$options = new \ArrayObject($options);
		}

		$options['global'] = 'global';

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $container['event-dispatcher'];

		$event = new CollectSubscriptionListsEvent($options);
		$eventDispatcher->dispatch(CollectSubscriptionListsEvent::NAME, $event);

		return $options->getArrayCopy();
	}

	public function createSubscriptionModuleTemplateOptions(CreateOptionsEvent $event)
	{
		$options   = $event->getOptions();
		$templates = \TwigHelper::getTemplateGroup('avisota_subscription_');

		foreach ($templates as $key => $value) {
			$options[$key] = $value;
		}
	}

	public function createUnsubscribeModuleTemplateOptions(CreateOptionsEvent $event)
	{
		$options   = $event->getOptions();
		$templates = \TwigHelper::getTemplateGroup('avisota_unsubscribe_');

		foreach ($templates as $key => $value) {
			$options[$key] = $value;
		}
	}
}
