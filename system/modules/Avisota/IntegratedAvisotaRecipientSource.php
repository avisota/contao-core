<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @filesource
 */


/**
 * Class IntegratedAvisotaRecipientSource
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class IntegratedAvisotaRecipientSource extends Controller implements AvisotaRecipientSource
{
	private $arrConfig;
	
	public function __construct($arrConfig)
	{
		$this->import('Database');
		$this->arrConfig = $arrConfig;
	}
	
	
	/**
	 * List all recipient lists.
	 * 
	 * @return array
	 * Assoziative array ID=>Name of the recipient lists or <strong>null</strong> if this source does not have lists.
	 */
	public function getLists()
	{
		$arrLists = array();
		$objList = $this->Database->execute("
				SELECT
					*
				FROM
					tl_avisota_recipient_list
				ORDER BY
					title");
		while ($objList->next())
		{
			$arrLists[$objList->id] = $objList->title;
		}
		return $arrLists;
	}
	
	
	/**
	 * List all recipients.
	 * 
	 * @param mixed $varList
	 * ID of the recipient list.
	 * 
	 * @return array
	 * List of all recipient emails.
	 */
	public function getRecipients($varList = null)
	{
		$objRecipient = $this->Database->prepare("
				SELECT
					email
				FROM
					tl_avisota_recipient
				WHERE
					pid=?")
			->execute($varList);
		return $objRecipient->fetchEach('email');
	}
	
	
	/**
	 * Get the recipient details.
	 * 
	 * @param string $strEmail
	 * @return array
	 * Associative array of recipient details.
	 */
	public function getRecipientDetails($strEmail, $varList = null)
	{
		$arrRecipient = array('email'=>$strEmail);
		
		if (	$this->detail_source == 'member_details'
			||	$this->detail_source == 'integrated_member_details')
		{
			$objRecipient = $this->Database->prepare("
					SELECT
						*
					FROM
						tl_member
					WHERE
						email=?")
				->execute($strEmail);
			if ($objRecipient->next())
			{
				$arrRecipient = array_merge($arrRecipient, $objRecipient->fetchAssoc());
			}
		}
		
		if (	$this->detail_source == 'integrated_details'
			||	$this->detail_source == 'integrated_member_details')
		{
			$objRecipient = $this->Database->prepare("
					SELECT
						*
					FROM
						tl_avisota_recipient
					WHERE
						email=?
					AND	pid=?")
				->execute($strEmail, $varList);
			if ($objRecipient->next())
			{
				$arrRecipient = array_merge($arrRecipient, $objRecipient->fetchAssoc());
			}
		}
		
		return $arrRecipient;
	}
}

?>