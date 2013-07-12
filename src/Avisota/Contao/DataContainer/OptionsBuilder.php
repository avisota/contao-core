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
 * @license    LGPL
 * @filesource
 */

namespace Avisota\Contao\DataContainer;

use Contao\Doctrine\ORM\EntityHelper;

class OptionsBuilder
{
	static function getMailingListOptions()
	{
		$mailingListRepository = EntityHelper::getRepository('Avisota\Contao:MailingList');
		$mailingLists = $mailingListRepository->findBy(array(), array('title' => 'ASC'));
		$options = array();
		/** @var \Avisota\Contao\Entity\MailingList $mailingList */
		foreach ($mailingLists as $mailingList) {
			$options[$mailingList->getId()] = $mailingList->getTitle();
		}
		return $options;
	}

	static function getMessageOptions()
	{
		$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
		$messages = $messageRepository->findBy(array(), array('sendOn' => 'DESC'));
		$options = array();
		/** @var \Avisota\Contao\Entity\Message $message */
		foreach ($messages as $message) {
			$options[$message->getCategory()->getTitle()][$message->getId()] = sprintf(
				'[%s] %s',
				$message->getSendOn() ? $message->getSendOn()->format($GLOBALS['TL_CONFIG']['datimFormat']) : '-',
				$message->getSubject()
			);
		}
		return $options;
	}

	static function getMessageCategoryOptions()
	{
		$messageCategoryRepository = EntityHelper::getRepository('Avisota\Contao:MessageCategory');
		$messageCategories = $messageCategoryRepository->findBy(array(), array('title' => 'ASC'));
		$options = array();
		/** @var \Avisota\Contao\Entity\MessageCategory $messageCategory */
		foreach ($messageCategories as $messageCategory) {
			$options[$messageCategory->getId()] = $messageCategory->getTitle();
		}
		return $options;
	}

	static function getQueueOptions()
	{
		$queueRepository = EntityHelper::getRepository('Avisota\Contao:Queue');
		$queues = $queueRepository->findBy(array(), array('title' => 'ASC'));
		$options = array();
		/** @var \Avisota\Contao\Entity\Queue $queue */
		foreach ($queues as $queue) {
			$options[$queue->getId()] = $queue->getTitle();
		}
		return $options;
	}

	static function getRecipientOptions()
	{
		$recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');
		$recipients = $recipientRepository->findBy(array(), array('firstname' => 'ASC', 'lastname' => 'ASC', 'email' => 'ASC'));
		$options = array();
		/** @var \Avisota\Contao\Entity\Recipient $recipient */
		foreach ($recipients as $recipient) {
			if ($recipient->getFirstname() && $recipient->getLastname()) {
				$options[$recipient->getId()] = sprintf(
					'%s, %s &lt;%s&gt;',
					$recipient->getLastname(),
					$recipient->getFirstname(),
					$recipient->getEmail()
				);
			}
			else if ($recipient->getFirstname()) {
				$options[$recipient->getId()] = sprintf(
					'%s &lt;%s&gt;',
					$recipient->getFirstname(),
					$recipient->getEmail()
				);
			}
			else if ($recipient->getLastname()) {
				$options[$recipient->getId()] = sprintf(
					'%s &lt;%s&gt;',
					$recipient->getLastname(),
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
		$recipientSources = $recipientSourceRepository->findBy(array(), array('title' => 'ASC'));
		$options = array();
		/** @var \Avisota\Contao\Entity\RecipientSource $recipientSource */
		foreach ($recipientSources as $recipientSource) {
			$options[$recipientSource->getId()] = $recipientSource->getTitle();
		}
		return $options;
	}

	static function getThemeOptions()
	{
		$themeRepository = EntityHelper::getRepository('Avisota\Contao:Theme');
		$themes = $themeRepository->findBy(array(), array('title' => 'ASC'));
		$options = array();
		/** @var \Avisota\Contao\Entity\Theme $theme */
		foreach ($themes as $theme) {
			$options[$theme->getId()] = $theme->getTitle();
		}
		return $options;
	}

	static function getTransportOptions()
	{
		$transportRepository = EntityHelper::getRepository('Avisota\Contao:Transport');
		$transports = $transportRepository->findBy(array(), array('title' => 'ASC'));
		$options = array();
		/** @var \Avisota\Contao\Entity\Transport $transport */
		foreach ($transports as $transport) {
			$options[$transport->getId()] = $transport->getTitle();
		}
		return $options;
	}
}
