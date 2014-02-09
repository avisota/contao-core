<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  MEN AT WORK 2013
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Backend;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Core\Event\RemoveRecipientEvent;
use Avisota\Recipient\MutableRecipient;
use Avisota\RecipientSource\RecipientSourceInterface;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Query\QueryBuilder;

class Cron extends \Controller
{

	/**
	 *
	 * @var Cron 
	 */
	protected static $instance = null;
	
	/**
	 * @static
	 * @return Cron
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new Cron();
		}
		return self::$instance;
	}

	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	/**
	 * Send mail.
	 * 
	 * @param type $recipient
	 * @param type $mailBoilerplateId
	 * @param type $transportId
	 * @param type $newsletterData
	 * 
	 * @throws \RuntimeException
	 */
	protected function sendMessage($recipient, $mailBoilerplateId, $transportId, $newsletterData)
	{
		$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
		$messageEntity     = $messageRepository->find($mailBoilerplateId);

		if (!$messageEntity)
		{
			throw new \RuntimeException('Could not find message id ' . $mailBoilerplateId);
		}

		/** @var MessagePreRendererInterface $renderer */
		$renderer           = $GLOBALS['container']['avisota.renderer'];
		$preRenderedMessage = $renderer->renderMessage($messageEntity);
		$message            = $preRenderedMessage->render($recipient, $newsletterData);

		/** @var TransportInterface $transport */
		$transport = $GLOBALS['container']['avisota.transport.' . $transportId];

		$transport->send($message);
	}
}
