<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\DataContainer;

use Avisota\Contao\Entity\SalutationGroup;
use Avisota\Contao\Event\CollectSubscriptionListsEvent;
use Contao\Doctrine\ORM\EntityHelper;
use DcGeneral\DC_General;
use Symfony\Component\EventDispatcher\EventDispatcher;

class OptionsBuilder extends \Controller
{
	static protected $instance;

	/**
	 * @return OptionsBuilder
	 */
	static public function getInstance()
	{
		if (!static::$instance) {
			static::$instance = new OptionsBuilder();
		}
		return static::$instance;
	}

	static function getLayoutOptions()
	{
		$layoutRepository = EntityHelper::getRepository('Avisota\Contao:Layout');
		$layouts          = $layoutRepository->findBy(array(), array('title' => 'ASC'));
		$options          = array();
		/** @var \Avisota\Contao\Entity\Layout $layout */
		foreach ($layouts as $layout) {
			$options[$layout
				->getTheme()
				->getTitle()][$layout->getId()] = $layout->getTitle();
		}
		return $options;
	}

	static function getMailingListOptions()
	{
		$mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
		$mailingLists          = $mailingListRepository->findBy(array(), array('title' => 'ASC'));
		$options               = array();
		/** @var \Avisota\Contao\Entity\MailingList $mailingList */
		foreach ($mailingLists as $mailingList) {
			$options[$mailingList->getId()] = $mailingList->getTitle();
		}
		return $options;
	}

	static function getMessageOptions()
	{
		$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
		$messages          = $messageRepository->findBy(array(), array('sendOn' => 'DESC'));
		$options           = array();
		/** @var \Avisota\Contao\Entity\Message $message */
		foreach ($messages as $message) {
			$options[$message
				->getCategory()
				->getTitle()][$message->getId()] = sprintf(
				'[%s] %s',
				$message->getSendOn() ? $message
					->getSendOn()
					->format($GLOBALS['TL_CONFIG']['datimFormat']) : '-',
				$message->getSubject()
			);
		}
		return $options;
	}

	static function getMessageCategoryOptions()
	{
		$messageCategoryRepository = EntityHelper::getRepository('Avisota\Contao:MessageCategory');
		$messageCategories         = $messageCategoryRepository->findBy(array(), array('title' => 'ASC'));
		$options                   = array();
		/** @var \Avisota\Contao\Entity\MessageCategory $messageCategory */
		foreach ($messageCategories as $messageCategory) {
			$options[$messageCategory->getId()] = $messageCategory->getTitle();
		}
		return $options;
	}

	static function getQueueOptions()
	{
		$queueRepository = EntityHelper::getRepository('Avisota\Contao:Queue');
		$queues          = $queueRepository->findBy(array(), array('title' => 'ASC'));
		$options         = array();
		/** @var \Avisota\Contao\Entity\Queue $queue */
		foreach ($queues as $queue) {
			$options[$queue->getId()] = $queue->getTitle();
		}
		return $options;
	}

	static function getRecipientOptions()
	{
		$recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');
		$recipients          = $recipientRepository->findBy(
			array(),
			array('forename' => 'ASC', 'surname' => 'ASC', 'email' => 'ASC')
		);
		$options             = array();
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

	static function getRecipientSourceOptions()
	{
		$recipientSourceRepository = EntityHelper::getRepository('Avisota\Contao:RecipientSource');
		$recipientSources          = $recipientSourceRepository->findBy(array(), array('title' => 'ASC'));
		$options                   = array();
		/** @var \Avisota\Contao\Entity\RecipientSource $recipientSource */
		foreach ($recipientSources as $recipientSource) {
			$options[$recipientSource->getId()] = $recipientSource->getTitle();
		}
		return $options;
	}

	static function getThemeOptions()
	{
		$themeRepository = EntityHelper::getRepository('Avisota\Contao:Theme');
		$themes          = $themeRepository->findBy(array(), array('title' => 'ASC'));
		$options         = array();
		/** @var \Avisota\Contao\Entity\Theme $theme */
		foreach ($themes as $theme) {
			$options[$theme->getId()] = $theme->getTitle();
		}
		return $options;
	}

	static function getTransportOptions()
	{
		$transportRepository = EntityHelper::getRepository('Avisota\Contao:Transport');
		$transports          = $transportRepository->findBy(array(), array('title' => 'ASC'));
		$options             = array();
		/** @var \Avisota\Contao\Entity\Transport $transport */
		foreach ($transports as $transport) {
			$options[$transport->getId()] = $transport->getTitle();
		}
		return $options;
	}

	static function getLayoutTypeOptions()
	{
		$options = array_keys($GLOBALS['AVISOTA_MESSAGE_RENDERER']);
		return array_values($options);
	}

	static function getMailChimpTemplateOptions()
	{
		static::getInstance()
			->loadLanguageFile('avisota_mailchimp_template');

		$options = array();
		foreach ($GLOBALS['AVISOTA_MAILCHIMP_TEMPLATE'] as $group => $mailChimpTemplates) {
			if (isset($GLOBALS['TL_LANG']['avisota_mailchimp_template'][$group])) {
				$groupLabel = $GLOBALS['TL_LANG']['avisota_mailchimp_template'][$group];
			}
			else {
				$groupLabel = $group;
			}
			foreach ($mailChimpTemplates as $name => $mailChimpTemplate) {
				if (isset($GLOBALS['TL_LANG']['avisota_mailchimp_template'][$name])) {
					$label = $GLOBALS['TL_LANG']['avisota_mailchimp_template'][$name];
				}
				else {
					$label = $name;
				}

				$label .= sprintf(' [%s]', strtoupper($mailChimpTemplate['mode']));

				$options[$groupLabel][$group . ':' . $name] = $label;
			}
		}
		return $options;
	}

	static function getRecipientFieldOptions()
	{
		static::getInstance()
			->loadLanguageFile('orm_avisota_recipient');
		static::getInstance()
			->loadDataContainer('orm_avisota_recipient');

		$options = array();
		foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $fieldName => $fieldConfig) {
			if (!empty($fieldConfig['inputType'])) {
				$options[$fieldName] = $fieldConfig['label'][0];
			}
		}
		return $options;
	}

	/**
	 * Return all newsletter elements as array
	 *
	 * @return array
	 */
	static public function getMessageContentTypes($dc)
	{
		$groups = array();
		if ($dc instanceof DC_General && $dc->getEnvironment()->getCurrentModel()) {
			/** @var \Avisota\Contao\Entity\MessageContent $content */
			$content = $dc
				->getEnvironment()
				->getCurrentModel()
				->getEntity();
			$cell = $content->getCell();

			if ($cell) {
				$cell = preg_replace('~\[\d+\]$~', '', $cell);
				$layout  = $content
					->getMessage()
					->getLayout();

				$allowedCellContents = $layout->getAllowedCellContents();

				foreach ($GLOBALS['TL_MCE'] as $elementGroup => $elements) {
					foreach ($elements as $elementType) {
						if (in_array($cell . ':' . $elementType, $allowedCellContents)) {
							$groups[$elementGroup][] = $elementType;
						}
					}
				}
			}
		}

		return $groups;
	}

	/**
	 * Get a list of areas from the parent category.
	 *
	 * @param DC_General $dc
	 */
	static public function getMessageContentCells($dc)
	{
		if ($dc instanceof DC_General && $dc->getEnvironment()->getCurrentModel()) {
			/** @var \Avisota\Contao\Entity\MessageContent $content */
			$content = $dc
				->getEnvironment()
				->getCurrentModel()
				->getEntity();
			$layout  = $content
				->getMessage()
				->getLayout();

			list($templateGroup, $templateName) = explode(':', $layout->getMailchimpTemplate());
			$mailChimpTemplate = $GLOBALS['AVISOTA_MAILCHIMP_TEMPLATE'][$templateGroup][$templateName];
			$cells        = $mailChimpTemplate['cells'];
			$rows         = isset($mailChimpTemplate['rows']) ? $mailChimpTemplate['rows'] : array();

			$repeatableCells = array();
			foreach ($rows as $row) {
				$repeatableCells = array_merge($repeatableCells, $row['affectedCells']);
			}

			$cellNames = array();
			foreach ($cells as $cellName => $cell) {
				if (!isset($cell['content'])) {
					$cellNames[] = $cellName . '[1]';
				}
			}

			return $cellNames;
		}

		return array();
	}

	/**
	 * Get a list of salutation groups.
	 *
	 * @param DC_General $dc
	 */
	static public function getSalutationGroups($dc)
	{
		if ($dc instanceof DC_General && $dc->getEnvironment()->getCurrentModel()) {
			$salutationGroupRepository = EntityHelper::getRepository('Avisota\Contao:SalutationGroup');
			/** @var SalutationGroup[] $salutationGroups */
			$salutationGroups = $salutationGroupRepository->findAll();

			$options = array();
			foreach ($salutationGroups as $salutationGroup) {
				$options[$salutationGroup->getId()] = $salutationGroup->getTitle();
			}
			return $options;
		}

		return array();
	}

	static public function getSubscriptionListOptions()
	{
		global $container;

		$options = new \ArrayObject();
		$options['global'] = 'global';

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $container['event-dispatcher'];

		$event = new CollectSubscriptionListsEvent($options);
		$eventDispatcher->dispatch(CollectSubscriptionListsEvent::NAME, $event);

		return $options->getArrayCopy();
	}
}
