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

namespace Avisota\Contao\Core\RecipientSource;

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Entity\RecipientSource;
use Avisota\RecipientSource\CSVFile;
use Contao\Doctrine\ORM\EntityHelper;

class CSVFileFactory implements RecipientSourceFactoryInterface
{
	public function createRecipientSource(RecipientSource $recipientSource)
	{
		return new CSVFile($recipientSource->getCsvFileSrc(), $recipientSource->getCsvColumnAssignment());
	}
}
