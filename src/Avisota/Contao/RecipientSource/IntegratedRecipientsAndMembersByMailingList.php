<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     David Maack <david.maack@arcor.de>
 * @package    avisota
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\RecipientSource;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient;
use Avisota\Recipient\MutableRecipient;
use Avisota\RecipientSource\RecipientSourceInterface;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Query\QueryBuilder;


/**
 * Class AvisotaRecipientSourceIntegratedRecipients
 *
 *
 * @copyright  bit3 UG 2013
 * @author     David Maack <david.maack@arcor.de>
 * @package    Avisota
 */
class IntegratedRecipientsAndMembersByMailingList implements RecipientSourceInterface
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
	 * Count the recipients.
	 *
	 * @return int
	 */
	public function countRecipients()
	{
		//ToDo get Count
		return 3;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecipients($limit = null, $offset = null)
	{
		$recipients = array();
		
		$mailingListIds = array('0');
		foreach ($this->mailingLists as $mailingList) {
			$mailingListIds[] = 'mailing_list:' . $mailingList->id();
		}
		
		$objDB = \Database::getInstance();
		
		$objStmt = $objDB->prepare('SELECT m.* FROM tl_member as m 
			JOIN orm_avisota_member_subscription as s ON (m.id = s.member) 
			WHERE list IN (\''. implode("','", $mailingListIds) .'\')
			AND s.confirmed = 1');
		
		if ($limit > 0 || $offset > 0)
		{
			//convert null values
			if ($limit === null) $limit = 18446744073709551610 ;
			if ($offset === null) $offset = 0 ;
			
			$objStmt->limit($limit, $offset);
		}
		
		$objMembers = $objStmt->execute();
		
		if ($objMembers->numRows > 0)
		{
			//decrease the limit & offset
			$limit = $limit - $objMembers->numRows;
			$offset = $offset - $objMembers->numRows;
			
			while($objMembers->next());
			{
				$recipients[] = new MutableRecipient(
					$objMembers->email,
					$objMembers->row()
				);
			}
		}
		
		
		//get reciepeints if needed
		if ($objMembers->numRows < $limit)
		{
			$queryBuilder = EntityHelper::getEntityManager()->createQueryBuilder();
			$queryBuilder
				->select('r')
				->from('Avisota\Contao:Recipient', 'r');
			$this->prepareQuery($queryBuilder);
			if ($limit > 0) {
				$queryBuilder->setMaxResults($limit);
			}
			if ($offset > 0) {
				$queryBuilder->setFirstResult($offset);
			}
			$queryBuilder->orderBy('r.email');
			$query = $queryBuilder->getQuery();
			$integratedRecipients = $query->getResult();

			/** @var Recipient $integratedRecipient */
			foreach ($integratedRecipients as $integratedRecipient) {
				$recipients[] = new MutableRecipient(
					$integratedRecipient->getEmail(),
					$integratedRecipient->toArray()
				);
			}
		}
		
		return $recipients;
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
