<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class AvisotaRecipientSourceIntegratedRecipients
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaRecipientSourceIntegratedRecipients extends Controller implements AvisotaRecipientSource
{
	/**
	 * @var array
	 */
	private $config;
	
	public function __construct($arrConfig)
	{
		$this->import('Database');
		$this->config = $arrConfig;
	}

	/**
	 * Get all selectable recipient options for this source.
	 * Every option can be an individuell ID.
	 *
	 * @return array
	 */
	public function getRecipientOptions()
	{
		switch ($this->config['integratedBy']) {
			case 'integratedByMailingLists':
				$arrIDs = array_filter(array_map('intval', deserialize($this->config['integratedMailingLists'], true)));
				if (!count($arrIDs)) {
					$arrIDs[] = 0;
				}
				// fetch all selected mailing lists
				$objMailingList = $this->Database
					->execute('SELECT * FROM tl_avisota_mailing_list
							   WHERE id IN (' . implode(',', $arrIDs) . ')
							   ORDER BY title');
				// continue with same logic as for all mailing lists
			case 'integratedByAllMailingLists':
				// fetch mailing lists, if there are no result
				if (!isset($objMailingList)) {
					$objMailingList = $this->Database
						->execute('SELECT * FROM tl_avisota_mailing_list ORDER BY title');
				}

				// if single selection is allowed, build an option for every mailing list
				if ($this->config['integratedAllowSingleListSelection']) {
					$arrOptions = array();
					while ($objMailingList->next()) {
						$arrOptions[$objMailingList->id] = $objMailingList->title;
					}
					return $arrOptions;
				}

				// build a wildcard option for non-single select
				else {
					return array(
						'*' => implode(', ', $objMailingList->fetchEach('title'))
					);
				}
				break;

			case 'integratedByRecipients':
				$arrIDs = array_filter(array_map('intval', deserialize($this->config['integratedMailingLists'], true)));
				if (!count($arrIDs)) {
					$arrIDs[] = 0;
				}
				$objRecipient = $this->Database
					->prepare('SELECT r.*, (SELECT t2.confirmed FROM tl_avisota_recipient_to_mailing_list t2 WHERE t2.recipient=r.id AND confirmed=? LIMIT 1) as confirmed
							   FROM tl_avisota_recipient r
							   INNER JOIN tl_avisota_recipient_to_mailing_list t ON t.recipient=r.id
							   WHERE t.list IN (' . implode(',', $arrIDs) . ')
							   ORDER BY email')
					->execute(1);
			case 'integratedByAllRecipients':
				if (!isset($objRecipient)) {
					$objRecipient = $this->Database
						->execute("SELECT *, 1 AS confirmed FROM tl_avisota_recipient
								   ORDER BY email");
				}

				$arrOptions = array();
				while ($objRecipient->next()) {
					$strName = trim($objRecipient->firstname . ' ' . $objRecipient->lastname);
					$strName = $strName ? sprintf('%s &lt;%s&gt;', $strName, $objRecipient->email) : $objRecipient->email;
					if (!$objRecipient->confirmed) {
						$strName = '<span style="color:#A6A6A6">' . $strName . '</span>';
					}
					$arrOptions[$objRecipient->id] = $strName;
				}

				if ($this->config['integratedAllowSingleSelection']) {
					return $arrOptions;
				}

				else {
					return array(
						'*' => implode(', ', $arrOptions)
					);
				}
				break;

			default:
				$this->log('The integrated recipient source ID ' . $this->config['id'] . ' is not fully configured!', 'AvisotaRecipientSourceIntegratedRecipients::getRecipientOptions()', TL_ERROR);
				return array();
		}
	}

	/**
	 * Get recipient IDs of a list of options.
	 *
	 * @abstract
	 * @param array $varOption
	 * @return array
	 */
	public function getRecipients($arrOptions)
	{
		switch ($this->config['integratedBy']) {
			case 'integratedByMailingLists':

				break;
			case 'integratedByAllMailingLists':

				break;
			case 'integratedByAllRecipients':
				break;
			default:
				$this->log('The recipient source ID ' . $this->config['id'] . ' is not fully configured!', 'AvisotaRecipientSourceIntegratedRecipients::getRecipientOptions()', TL_ERROR);
				return array();
		}
	}

	/**
	 * Get the recipient details.
	 *
	 * @param string $varId
	 * @return array
	 */
	public function getRecipientDetails($varId)
	{

	}
}
