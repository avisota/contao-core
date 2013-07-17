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

namespace Avisota\Contao\Salutation;

use Avisota\Contao\Entity\Salutation;
use Avisota\Recipient\RecipientInterface;

class FieldValueDecider implements DeciderInterface
{
	public function accept(RecipientInterface $recipient, Salutation $salutation)
	{
		$fieldValues = $salutation->getFieldValuesFilter();
		if (!$salutation->getEnableFieldValuesFilter() || empty($fieldValues)) {
			return true;
		}

		$details = $recipient->getDetails();
		foreach ($fieldValues as $fieldValue) {
			$fieldName = $fieldValue['field'];
			$fieldPattern = $fieldValue['value'];
			$isRegexp = $fieldValue['rgxp'];

			if (!$isRegexp) {
				$fieldPattern = explode('*', $fieldPattern);
				$fieldPattern = array_map(
					function($pattern) {
						return preg_quote($pattern, '/');
					},
					$fieldPattern
				);
				$fieldPattern = implode('.*', $fieldPattern);
				$fieldPattern = '/' . $fieldPattern . '/';
			}

			if (!isset($details[$fieldName]) || !preg_match($fieldPattern, $details[$fieldName])) {
				return false;
			}
		}

		return true;
	}
}
