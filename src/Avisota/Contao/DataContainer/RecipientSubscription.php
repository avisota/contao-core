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

use Avisota\Contao\Entity\RecipientSource;
use Avisota\Contao\Event\ResolveSubscriptionNameEvent;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\Common\Persistence\Mapping\MappingException;

class RecipientSubscription extends \Backend
{
	/**
	 * @param array
	 */
	public function addRecipientSubscriptionRow($subscriptionData)
	{
		global $container;

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $container['event-dispatcher'];

		$subscriptionRepository = EntityHelper::getRepository('Avisota\Contao:RecipientSubscription');
		/** @var \Avisota\Contao\Entity\RecipientSubscription $subscription */
		$subscription = $subscriptionRepository->findOneBy(
			array(
				'recipient' => $subscriptionData['recipient'],
				'list'      => $subscriptionData['list'],
			)
		);

		$event = new ResolveSubscriptionNameEvent($subscription);
		$eventDispatcher->dispatch(ResolveSubscriptionNameEvent::NAME, $event);

		$label = $event->getSubscriptionName();

		return sprintf(
			'<div>%s</div>',
			$label
		);
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

		$subscriptionRepository = EntityHelper::getRepository('Avisota\Contao:RecipientSubscription');
		/** @var \Avisota\Contao\Entity\RecipientSubscription $subscription */

		if (strlen($input->get('tid'))) {
			$id = $input->get('tid');
			$confirmed = $input->get('state') == 1;

			/** @var \Avisota\Contao\Entity\RecipientSubscription $subscription */
			$subscription = $subscriptionRepository->find($id);

			$subscription->setConfirmed($confirmed);

			$entityManager = EntityHelper::getEntityManager();
			$entityManager->persist($subscription);
			$entityManager->flush($subscription);

			$this->redirect($this->getReferer());
		}

		$subscription = $subscriptionRepository->findOneBy(
			array(
				'recipient' => $row['recipient'],
				'list'      => $row['list'],
			)
		);

		$href .= '&amp;tid=' . $subscription->id() . '&amp;state=' . ($row['confirmed'] ? '' : 1);

		if (!$row['confirmed']) {
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars(
			$title
		) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}
}
