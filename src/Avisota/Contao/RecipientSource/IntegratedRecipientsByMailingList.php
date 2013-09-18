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

namespace Avisota\Contao\RecipientSource;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\DBAL\Query\QueryBuilder;

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
	 * @var MailingList[]
	 */
	protected $mailingLists;

	/**
	 * @param MailingList[] $mailingLists
	 */
	public function __construct($mailingLists)
	{
		$this->mailingLists = $mailingLists;
	}

	/**
	 * @return QueryBuilder
	 */
	protected function prepareQuery(\Doctrine\ORM\QueryBuilder $queryBuilder)
	{
		$mailingListIds = array('0');
		foreach ($this->mailingLists as $mailingList) {
			$mailingListIds[] = 'mailing_list:' . $mailingList->id();
		}

		$queryBuilder
			->innerJoin('Avisota\Contao:RecipientSubscription', 's', 'WITH', 's.recipient=r.id')
			->where($queryBuilder->expr()->in('s.list', '?1'))
			->andWhere('s.confirmed=?2')
			->setParameter(1, $mailingListIds)
			->setParameter(2, true);
	}

}
