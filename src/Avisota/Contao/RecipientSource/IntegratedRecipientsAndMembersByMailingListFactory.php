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
use Avisota\Contao\Entity\RecipientSource;
use Contao\Doctrine\ORM\EntityHelper;

class IntegratedRecipientsAndMembersByMailingListFactory implements RecipientSourceFactoryInterface
{
	public function createRecipientSource(RecipientSource $recipientSource)
	{
		return new IntegratedRecipientsAndMembersByMailingList($recipientSource->getMailingLists());
	}
}
