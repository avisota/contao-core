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
		$file      = \Compat::resolveFile($recipientSource->getCsvFileSrc());
		$columns   = array();
		$delimiter = $recipientSource->getCsvFileDelimiter();
		$enclosure = $recipientSource->getCsvFileEnclosure();

		foreach ($recipientSource->getCsvColumnAssignment() as $item) {
			$columns[$item['column'] - 1] = $item['field'];
		}

		switch ($delimiter) {
			case 'semicolon':
				$delimiter = ';';
				break;
			case 'space':
				$delimiter = ' ';
				break;
			case 'tabulator':
				$delimiter = "\t";
				break;
			case 'linebreak':
				$delimiter = "\n";
				break;
			default:
				$delimiter = ',';
		}

		switch ($enclosure) {
			case 'single':
				$enclosure = "'";
				break;
			default:
				$enclosure = '"';
		}

		return new CSVFile(TL_ROOT . DIRECTORY_SEPARATOR . $file, $columns, $delimiter, $enclosure);
	}
}
