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
use Avisota\Contao\Entity\RecipientSource;
use Avisota\RecipientSource\CSVFile;
use Avisota\RecipientSource\Dummy;
use Contao\Doctrine\ORM\EntityHelper;

class DummyFactory implements RecipientSourceFactoryInterface
{
	public function createRecipientSource(RecipientSource $recipientSource)
	{
		return new Dummy($recipientSource->getDummyMinCount(), $recipientSource->getDummyMaxCount());
	}
}
