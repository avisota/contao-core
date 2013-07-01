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
class IntegratedRecipients extends AbstractIntegratedRecipients
{
	/**
	 * {@inheritdoc}
	 */
	protected function loadRecipients()
	{
		$recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');
		return $recipientRepository->findAll();
	}
}
