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

namespace Avisota\Contao\Core;

use Avisota\Contao\Core\Event\CreateFakeRecipientEvent;
use Avisota\Contao\Core\Event\CreatePublicEmptyRecipientEvent;
use Avisota\Recipient\Fake\FakeRecipient;
use Avisota\Recipient\MutableRecipient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventSubscriber implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	static public function getSubscribedEvents()
	{
		return array(
			CoreEvents::CREATE_FAKE_RECIPIENT         => 'createFakeRecipient',
			CoreEvents::CREATE_PUBLIC_EMPTY_RECIPIENT => 'createPublicEmptyRecipient',
		);
	}

	/**
	 * Create a new fake recipient, if no one is created yet.
	 *
	 * @param CreateFakeRecipientEvent $event
	 */
	public function createFakeRecipient(CreateFakeRecipientEvent $event)
	{
		if ($event->getRecipient()) {
			return;
		}

		$locale = null;
		if ($event->getMessage()) {
			$locale = $event->getMessage()->getLanguage();
		}

		$event->setRecipient(new FakeRecipient($locale));
	}

	public function createPublicEmptyRecipient(CreatePublicEmptyRecipientEvent $event)
	{
		if ($event->getRecipient()) {
			return;
		}

		$environment = \Environment::getInstance();

		$event->setRecipient(new MutableRecipient('noreply@' . $environment->host));
	}
}
