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

use Avisota\RecipientSource\RecipientSourceInterface;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\EncodePropertyValueFromWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\IdSerializer;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use ContaoCommunityAlliance\DcGeneral\Event\PrePersistModelEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RecipientSource implements EventSubscriberInterface
{
	static protected $instance;

	/**
	 * @return mixed
	 */
	static public function getInstance()
	{
		if (static::$instance === null) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * {@inheritdoc}
	 */
	static public function getSubscribedEvents()
	{
		return array(
			EncodePropertyValueFromWidgetEvent::NAME . '[orm_avisota_recipient_source][csvColumnAssignment]' => array('checkCsvColumnUnique', 'checkCsvColumnEmail'),
			DcGeneralEvents::ACTION => 'handleAction',
		);
	}

	public function __construct()
	{
		static::$instance = $this;
	}

	public function checkCsvColumnUnique(EncodePropertyValueFromWidgetEvent $event)
	{
		$value = $event->getValue();

		if (!is_array($value)) {
			$value = deserialize($value, true);
		}

		$columns = array();
		$fields  = array();

		foreach ($value as $item) {
			if (
				in_array($item['column'], $columns)
				|| in_array($item['field'], $fields)
			) {
				throw new \RuntimeException($GLOBALS['TL_LANG']['orm_avisota_recipient_source']['duplicated_column']);
			}

			$columns[] = $item['column'];
			$fields[]  = $item['field'];
		}

	}

	public function checkCsvColumnEmail(EncodePropertyValueFromWidgetEvent $event)
	{
		$value = $event->getValue();

		if (!is_array($value)) {
			$value = deserialize($value, true);
		}

		foreach ($value as $item) {
			if ($item['field'] == 'email') {
				return;
			}
		}

		throw new \RuntimeException($GLOBALS['TL_LANG']['orm_avisota_recipient_source']['missing_email_column']);
	}

	public function handleAction(ActionEvent $event)
	{
		$environment = $event->getEnvironment();

		if ($environment->getDataDefinition()->getName() != 'orm_avisota_recipient_source') {
			return;
		}

		$action = $event->getAction();

		if ($action->getName() == 'list') {
			$input      = $environment->getInputProvider();
			$id         = IdSerializer::fromSerialized($input->getParameter('id'));
			$repository = EntityHelper::getRepository('Avisota\Contao:RecipientSource');

			/** @var \Avisota\Contao\Entity\RecipientSource $recipientSourceEntity */
			$recipientSourceEntity = $repository->find($id->getId());

			/** @var RecipientSourceInterface $recipientSource */
			$recipientSource = $GLOBALS['container'][sprintf('avisota.recipientSource.%s', $recipientSourceEntity->getId())];

			$recipients = $recipientSource->getRecipients(50);
			$total      = $recipientSource->countRecipients();

			$template = new \TwigBackendTemplate('avisota/backend/recipient_source_list');
			$template->recipients = $recipients;
			$template->total      = $total;

			$event->setResponse($template->parse());
		}
	}

	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		$input = \Input::getInstance();

		if (strlen($input->get('tid'))) {
			$this->toggleVisibility($input->get('tid'), ($input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('orm_avisota_recipient_source::disable', 'alexf')) {
			return '';
		}

		$href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['disable'] ? '' : '1');

		if ($row['disable']) {
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars(
			$title
		) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}


	/**
	 * Toggle the visibility of an element
	 *
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($id, $isVisible)
	{
		/*
		// Check permissions to edit
		$this->Input->setGet('id', $id);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('orm_avisota_recipient_source::disable', 'alexf')) {
			$this->log(
				'Not enough permissions to publish/unpublish newsletter recipient source ID "' . $id . '"',
				'orm_avisota_recipient_source toggleVisibility',
				TL_ERROR
			);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('orm_avisota_recipient_source', $id);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields']['disable']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields']['disable']['save_callback'] as $callback) {
				$this->import($callback[0]);
				$isVisible = $this->$callback[0]->$callback[1]($isVisible, $this);
			}
		}

		// Update the database
		\Database::getInstance()
			->prepare(
			"UPDATE orm_avisota_recipient_source SET tstamp=" . time() . ", disable='" . ($isVisible ? ''
				: 1) . "' WHERE id=?"
		)
			->execute($id);

		$this->createNewVersion('orm_avisota_recipient_source', $id);
		*/
	}

	public function getRecipientColumns()
	{
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$eventDispatcher->dispatch(
			ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
			new LoadLanguageFileEvent('orm_avisota_recipient')
		);
		$eventDispatcher->dispatch(
			ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER,
			new LoadDataContainerEvent('orm_avisota_recipient')
		);

		$options = array();

		foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $k => $v) {
			if ($v['eval']['importable']) {
				$options[$k] = $v['label'][0];
			}
		}
		asort($options);

		return $options;
	}

	public function getRecipientFilterColumns()
	{
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$eventDispatcher->dispatch(
			ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
			new LoadLanguageFileEvent('orm_avisota_recipient')
		);
		$eventDispatcher->dispatch(
			ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER,
			new LoadDataContainerEvent('orm_avisota_recipient')
		);

		$options = array();

		foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'] as $k => $v) {
			$options[$k] = $v['label'][0] . ' (' . $k . ')';
		}
		asort($options);

		return $options;
	}

	public function getMemberFilterColumns()
	{
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$eventDispatcher->dispatch(
			ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
			new LoadLanguageFileEvent('tl_member')
		);
		$eventDispatcher->dispatch(
			ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER,
			new LoadDataContainerEvent('tl_member')
		);

		$options = array();

		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k => $v) {
			$options[$k] = $v['label'][0] . ' (' . $k . ')';
		}
		asort($options);

		return $options;
	}
}
