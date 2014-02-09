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

use Avisota\Contao\Entity\Queue;
use Avisota\Contao\Core\Queue\QueueFactoryInterface;
use Avisota\Contao\Core\RecipientSource\RecipientSourceFactoryInterface;
use Contao\Doctrine\ORM\EntityHelper;

class ServiceFactory
{
	public function createService($type, $id)
	{
		switch ($type) {
			case 'queue';
				return $this->createQueue($id);
				break;
			case 'recipientSource';
				return $this->createRecipientSource($id);
				break;
			case 'transport';
				return $this->createTransport($id);
				break;
		}
	}

	public function createQueue($id)
	{
		$queueRepository = EntityHelper::getRepository('Avisota\Contao:Queue');
		/** @var Queue $queue */
		$queue = $queueRepository->find($id);

		if (!$queue) {
			return null;
		}

		$queueFactoryClass = new \ReflectionClass($GLOBALS['AVISOTA_QUEUE'][$queue->getType()]);
		/** @var QueueFactoryInterface $queueFactory */
		$queueFactory = $queueFactoryClass->newInstance();

		return $queueFactory->createQueue($queue);
	}

	public function createRecipientSource($id)
	{
		$recipientSourceRepository = EntityHelper::getRepository('Avisota\Contao:RecipientSource');
		$recipientSource = $recipientSourceRepository->find($id);

		if (!$recipientSource) {
			return null;
		}

		$recipientSourceFactoryClass = new \ReflectionClass($GLOBALS['AVISOTA_RECIPIENT_SOURCE'][$recipientSource->getType()]);
		/** @var RecipientSourceFactoryInterface $recipientSourceFactory */
		$recipientSourceFactory = $recipientSourceFactoryClass->newInstance();

		return $recipientSourceFactory->createRecipientSource($recipientSource);
	}

	public function createTransport($id)
	{
		$transportRepository = EntityHelper::getRepository('Avisota\Contao:Transport');
		$transport = $transportRepository->find($id);

		if (!$transport) {
			return null;
		}

		$transportFactoryClass = new \ReflectionClass($GLOBALS['AVISOTA_TRANSPORT'][$transport->getType()]);
		/** @var TransportFactoryInterface $transportFactory */
		$transportFactory = $transportFactoryClass->newInstance();

		return $transportFactory->createTransport($transport);
	}
}
