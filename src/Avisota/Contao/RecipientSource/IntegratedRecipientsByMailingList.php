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

namespace Avisota\Contao\RecipientSource;

use Avisota\Contao\Entity\Recipient;
use Contao\Doctrine\ORM\EntityHelper;

/**
 * Class AvisotaRecipientSourceIntegratedRecipients
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class IntegratedRecipientsByMailingList extends AbstractIntegratedRecipients
{
	/**
	 * {@inheritdoc}
	 */
	protected function loadRecipients()
	{
		$entityManager = EntityHelper::getEntityManager();
		$queryBuilder = $entityManager->createQueryBuilder();

		return $queryBuilder
			->select('r')
			->from('Avisota\Contao:Recipient', 'r')
			->innerJoin('Avisota\Contao:RecipientSubscription', 's.recipient=r.id')
			->where($queryBuilder->expr()->in('s.list', '?1'))
			->setParameter(1, deserialize($this->config->lists))
			->getQuery()
			->getResult();
	}
}
