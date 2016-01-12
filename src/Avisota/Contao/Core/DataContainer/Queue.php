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

use Avisota\Queue\QueueInterface;
use Avisota\Transport\NullTransport;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Message\AddMessageEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\IdSerializer;
use ContaoCommunityAlliance\DcGeneral\DC_General;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An EventSubscriber knows himself what events he is interested in.
 * If an EventSubscriber is added to an EventDispatcherInterface, the manager invokes
 * {@link getSubscribedEvents} and registers the subscriber as a listener for all
 * returned events.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Queue extends \Backend implements EventSubscriberInterface
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
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		static::$instance = $this;
	}

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
	static public function getSubscribedEvents()
	{
		return array(
			DcGeneralEvents::ACTION => 'handleAction',
		);
	}

	/**
	 * @param string $alias
	 * @param DC_General $dc
	 *
	 * @return mixed
	 */
	public function rememberAlias($alias, $dc)
	{
		$_SESSION['AVISOTA_QUEUE_ALIAS'][$dc->id] = $alias;
		return $alias;
	}

	/**
	 * @param ActionEvent $event
     */
	public function handleAction(ActionEvent $event)
	{
		$environment = $event->getEnvironment();

		if ($environment->getDataDefinition()->getName() != 'orm_avisota_queue') {
			return;
		}

		$action = $event->getAction();

		if ($action->getName() == 'clear') {
			$input      = $environment->getInputProvider();
			$id         = IdSerializer::fromSerialized($input->getParameter('id'));
			$repository = EntityHelper::getRepository('Avisota\Contao:Queue');

			/** @var \Avisota\Contao\Entity\Queue $queueData */
			$queueData = $repository->find($id->getId());

			/** @var QueueInterface $queue */
			$queue = $GLOBALS['container'][sprintf('avisota.queue.%s', $queueData->getAlias())];

			$queue->execute(new NullTransport());

			$message = new AddMessageEvent(
				sprintf($GLOBALS['TL_LANG']['orm_avisota_queue']['queueCleared'], $queueData->getTitle()),
				AddMessageEvent::TYPE_CONFIRM
			);
			$event->getDispatcher()->dispatch(ContaoEvents::MESSAGE_ADD, $message);

			$redirect = new RedirectEvent('contao/main.php?do=avisota_queue&ref=' . TL_REFERER_ID);
			$event->getDispatcher()->dispatch(ContaoEvents::CONTROLLER_REDIRECT, $redirect);
		}
	}
}
