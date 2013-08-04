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

use Avisota\Contao\Entity\Recipient;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class AvisotaRecipientSourceIntegratedRecipients
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class IntegratedRecipients extends AbstractIntegratedRecipients
{
	/**
	 * @return QueryBuilder
	 */
	protected function prepareQuery(QueryBuilder $queryBuilder)
	{
	}
}
