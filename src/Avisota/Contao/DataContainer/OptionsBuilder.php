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
use Avisota\Contao\Event\CollectStylesheetsEvent;
use Avisota\Contao\Event\CollectSubscriptionListsEvent;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateOptionsEvent;
use DcGeneral\DC_General;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OptionsBuilder extends \Controller implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'avisota.create-article-options'                     => 'createArticleAliasOptions',
			'avisota.create-boilerplate-message-options'         => 'createBoilerplateMessages',
			'avisota.create-content-type-options'                => 'createContentTypeOptions',
			'avisota.create-editable-recipient-field-options'    => 'createEditableRecipientFieldOptions',
			'avisota.create-gallery-options'                     => 'createGalleryTemplateOptions',
			'avisota.create-importable-recipient-field-options'  => 'createImportableRecipientFieldOptions',
			'avisota.create-layout-options'                      => 'createLayoutOptions',
			'avisota.create-layout-stylesheet-options'           => 'crateLayoutStylesheetOptions',
			'avisota.create-layout-type-options'                 => 'createLayoutTypeOptions',
			'avisota.create-mailing-list-options'                => 'createMailingListOptions',
			'avisota.create-message-category-options'            => 'createMessageCategoryOptions',
			'avisota.create-message-content-cell-options'        => 'createMessageContentCellOptions',
			'avisota.create-message-content-type-options'        => 'createMessageContentTypeOptions',
			'avisota.create-message-options'                     => 'createMessageOptions',
			'avisota.create-non-boilerplate-message-options'     => 'createNonBoilerplateMessages',
			'avisota.create-queue-options'                       => 'createQueueOptions',
			'avisota.create-reader-module-template-options'      => 'createReaderModuleTemplateOptions',
			'avisota.create-recipient-field-options'             => 'createRecipientFieldOptions',
			'avisota.create-recipient-source-options'            => 'createRecipientSourceOptions',
			'avisota.create-recipient-options'                   => 'createRecipientOptions',
			'avisota.create-salutation-group-options'            => 'createSalutationGroups',
			'avisota.create-subscribe-module-template-options'   => 'createSubscribeModuleTemplateOptions',
			'avisota.create-subscription-list-options'           => 'createSubscriptionListOptions',
			'avisota.create-subscription-template-options'       => 'createSubscriptionModuleTemplateOptions',
			'avisota.create-theme-options'                       => 'createThemeOptions',
			'avisota.create-transport-options'                   => 'createTransportOptions',
			'avisota.create-unsubscribe-module-template-options' => 'createUnsubscribeModuleTemplateOptions',
		);
	}

	/**
	 * Get all articles and return them as array (article alias)
	 *
	 * @param object
	 *
	 * @return array
	 */
	public function createArticleAliasOptions(CreateOptionsEvent $event)
	{
		$this->getArticleAliasOptions($event->getOptions());
	}

	/**
	 * Get all articles and return them as array (article alias)
	 *
	 * @param object
	 *
	 * @return array
	 */
	public function getArticleAliasOptions($options = array())
	{
		$pids = array();

		$user = \BackendUser::getInstance();

		if (!$user->isAdmin) {
			foreach ($user->pagemounts as $id) {
				$pids[] = $id;
				$pids   = array_merge($pids, $this->getChildRecords($id, 'tl_page', true));
			}

			if (empty($pids)) {
				return $options;
			}

			$alias = $this->Database->execute(
				"SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(" . implode(
					',',
					array_map('intval', array_unique($pids))
				) . ") ORDER BY parent, a.sorting"
			);
		}
		else {
			$alias = $this->Database->execute(
				"SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting"
			);
		}

		if ($alias->numRows) {
			$this->loadLanguageFile('tl_article');

			while ($alias->next()) {
				$options[$alias->parent][$alias->id] = $alias->title . ' (' . (strlen(
						$GLOBALS['TL_LANG']['tl_article'][$alias->inColumn]
					) ? $GLOBALS['TL_LANG']['tl_article'][$alias->inColumn]
						: $alias->inColumn) . ', ID ' . $alias->id . ')';
			}
		}

		return $options;
	}

	public function createBoilerplateMessages(CreateOptionsEvent $event)
	{
		$this->getBoilerplateMessages($event->getOptions());
	}

	/**
	 * @return array
	 */
	public function getBoilerplateMessages($options = array())
	{
		$entityManager = EntityHelper::getEntityManager();
		$queryBuilder  = $entityManager->createQueryBuilder();

		/** @var Message[] $messages */
		$messages = $queryBuilder
			->select('m')
			->from('Avisota\Contao:Message', 'm')
			->innerJoin('Avisota\Contao:MessageCategory', 'c', 'c.id=m.category')
			->where('c.boilerplates=:boilerplate')
			->orderBy('c.title', 'ASC')
			->addOrderBy('m.subject', 'ASC')
			->setParameter(':boilerplate', true)
			->getQuery()
			->getResult();

		foreach ($messages as $message) {
			$options[$message->getCategory()
				->getTitle()][$message->getId()] = $message->getSubject();
		}

		return $options;
	}

	public function createContentTypeOptions(CreateOptionsEvent $event)
	{
		$this->getContentTypeOptions($event->getOptions());
	}

	public function getContentTypeOptions($options = array())
	{
		foreach ($GLOBALS['TL_MCE'] as $elementGroup => $elements) {
			if (isset($GLOBALS['TL_LANG']['MCE'][$elementGroup])) {
				$elementGroupLabel = $GLOBALS['TL_LANG']['MCE'][$elementGroup];
			}
			else {
				$elementGroupLabel = $elementGroup;
			}
			foreach ($elements as $elementType) {
				if (isset($GLOBALS['TL_LANG']['MCE'][$elementType])) {
					$elementLabel = $GLOBALS['TL_LANG']['MCE'][$elementType][0];
				}
				else {
					$elementLabel = $elementType;
				}

				$options[$elementGroupLabel][$elementType] = sprintf(
					'%s',
					$elementLabel
				);
			}
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

	public function createLayoutOptions(CreateOptionsEvent $event)
	{
		$this->getLayoutOptions($event->getOptions());
	}

	public function getLayoutOptions($options = array())
	{
		$layoutRepository = EntityHelper::getRepository('Avisota\Contao:Layout');
		$layouts          = $layoutRepository->findBy(array(), array('title' => 'ASC'));
		/** @var \Avisota\Contao\Entity\Layout $layout */
		foreach ($layouts as $layout) {
			$options[$layout
				->getTheme()
				->getTitle()][$layout->getId()] = $layout->getTitle();
		}
		return $options;
	}

	public function crateLayoutStylesheetOptions(CreateOptionsEvent $event)
	{
		$this->getLayoutStylesheetOptions($event->getOptions());
	}

	public function getLayoutStylesheetOptions($options = array())
	{
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		if ($options instanceof \ArrayObject) {
			$stylesheets = $options;
		}
		else {
			$stylesheets = new \ArrayObject();
		}

		$eventDispatcher->dispatch(CollectStylesheetsEvent::NAME, new CollectStylesheetsEvent($stylesheets));

		return $stylesheets->getArrayCopy();
	}

	public function createLayoutTypeOptions(CreateOptionsEvent $event)
	{
		static::getLayoutTypeOptions($event->getOptions());
	}

	public function getLayoutTypeOptions($options = array())
	{
		$options = array_merge(
			$options,
			array_values(array_keys($GLOBALS['AVISOTA_MESSAGE_RENDERER']))
		);
		return $options;
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

	public function createMessageCategoryOptions(CreateOptionsEvent $event)
	{
		$this->getMessageCategoryOptions($event->getOptions());
	}

	public function getMessageCategoryOptions($options = array())
	{
		$messageCategoryRepository = EntityHelper::getRepository('Avisota\Contao:MessageCategory');
		$messageCategories         = $messageCategoryRepository->findBy(array(), array('title' => 'ASC'));
		/** @var \Avisota\Contao\Entity\MessageCategory $messageCategory */
		foreach ($messageCategories as $messageCategory) {
			$options[$messageCategory->getId()] = $messageCategory->getTitle();
		}
		return $options;
	}

	/**
	 * Get a list of areas from the parent category.
	 *
	 * @param DC_General $dc
	 */
	public function createMessageContentCellOptions(CreateOptionsEvent $event)
	{
		$this->getMessageContentCellOptions($event->getOptions());
	}

	/**
	 * Get a list of areas from the parent category.
	 *
	 * @param DC_General $dc
	 */
	public function getMessageContentCellOptions($options = array())
	{
		return $options;
	}

	public function createMessageContentTypeOptions(CreateOptionsEvent $event)
	{
		$this->getMessageContentTypeOptions($event->getDataContainer(), $event->getOptions());
	}

	/**
	 * Return all newsletter elements as array
	 *
	 * @return array
	 */
	public function getMessageContentTypeOptions($dc, $options = array())
	{
		if ($dc instanceof DC_General && $dc->getEnvironment()
				->getCurrentModel()
		) {
			/** @var \Avisota\Contao\Entity\MessageContent $content */
			$content = $dc
				->getEnvironment()
				->getCurrentModel()
				->getEntity();
			$cell    = $content->getCell();

			if ($cell) {
				$cell   = preg_replace('~\[\d+\]$~', '', $cell);
				$layout = $content
					->getMessage()
					->getLayout();

				$allowedCellContents = $layout->getAllowedCellContents();

				foreach ($GLOBALS['TL_MCE'] as $elementGroup => $elements) {
					foreach ($elements as $elementType) {
						if (in_array($cell . ':' . $elementType, $allowedCellContents)) {
							$options[$elementGroup][] = $elementType;
						}
					}
				}
			}
		}

		return $options;
	}

	public function createMessageOptions(CreateOptionsEvent $event)
	{
		$this->getMessageOptions($event->getOptions());
	}

	public function getMessageOptions($options = array())
	{
		$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
		$messages          = $messageRepository->findBy(array(), array('sendOn' => 'DESC'));
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

	public function createNonBoilerplateMessages(CreateOptionsEvent $event)
	{
		$this->getNonBoilerplateMessages($event->getOptions());
	}

	/**
	 * @return array
	 */
	public function getNonBoilerplateMessages($options = array())
	{
		$entityManager = EntityHelper::getEntityManager();
		$queryBuilder  = $entityManager->createQueryBuilder();

		/** @var Message[] $messages */
		$messages = $queryBuilder
			->select('m')
			->from('Avisota\Contao:Message', 'm')
			->innerJoin('Avisota\Contao:MessageCategory', 'c', 'c.id=m.category')
			->where('c.boilerplates=:boilerplate')
			->orderBy('c.title', 'ASC')
			->addOrderBy('m.subject', 'ASC')
			->setParameter(':boilerplate', false)
			->getQuery()
			->getResult();

		foreach ($messages as $message) {
			$options[$message->getCategory()
				->getTitle()][$message->getId()] = $message->getSubject();
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

	public function createSalutationGroups(CreateOptionsEvent $event)
	{
		$this->getSalutationGroups($event->getDataContainer(), $event->getOptions());
	}

	/**
	 * Get a list of salutation groups.
	 *
	 * @param DC_General $dc
	 */
	public function getSalutationGroups($dc, $options = array())
	{
		if ($dc instanceof DC_General && $dc->getEnvironment()
				->getCurrentModel()
		) {
			$salutationGroupRepository = EntityHelper::getRepository('Avisota\Contao:SalutationGroup');
			/** @var SalutationGroup[] $salutationGroups */
			$salutationGroups = $salutationGroupRepository->findAll();

			foreach ($salutationGroups as $salutationGroup) {
				$options[$salutationGroup->getId()] = $salutationGroup->getTitle();
			}
			return $options;
		}

		return array();
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

	public function createThemeOptions(CreateOptionsEvent $event)
	{
		$this->getThemeOptions($event->getOptions());
	}

	public function getThemeOptions($options = array())
	{
		$themeRepository = EntityHelper::getRepository('Avisota\Contao:Theme');
		$themes          = $themeRepository->findBy(array(), array('title' => 'ASC'));
		/** @var \Avisota\Contao\Entity\Theme $theme */
		foreach ($themes as $theme) {
			$options[$theme->getId()] = $theme->getTitle();
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

	public function createUnsubscribeModuleTemplateOptions(CreateOptionsEvent $event)
	{
		$options   = $event->getOptions();
		$templates = \TwigHelper::getTemplateGroup('avisota_unsubscribe_');

		foreach ($templates as $key => $value) {
			$options[$key] = $value;
		}
	}
}
