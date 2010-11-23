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
 * Class AvisotaBase
 *
 * InsertTag hook class.
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBase extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->import('DomainLink');
	}
	
	
	public function getViewOnlinePage($objCategory = null, $arrRecipient = null)
	{
		if (!$objCategory)
		{
			$objCategory = Avisota::getCurrentCategory();
		}
		
		if (!$arrRecipient)
		{
			$arrRecipient = Avisota::getCurrentRecipient();
		}
		
		if (preg_match('#^list:(\d+)$#', $arrRecipient['outbox_source'], $arrMatch))
		{
			// the dummy list, used on preview
			if ($arrMatch[1] > 0)
			{
				$objRecipientList = $this->Database->prepare("
						SELECT
							*
						FROM
							`tl_avisota_recipient_list`
						WHERE
							`id`=?")
					->execute($arrMatch[1]);
				if ($objRecipientList->next())
				{
					return $this->getPageDetails($objRecipientList->viewOnlinePage);
				}
			}
		}
		
		if ($objCategory->viewOnlinePage > 0)
		{
			return $this->getPageDetails($objCategory->viewOnlinePage);
		}
		
		return false;
	}
	
	
	/**
	 * Extend the url to an absolute url.
	 */
	public function extendURL($strUrl, $objPage = null, $objCategory = null, $arrRecipient = null)
	{
		if ($objPage == null)
		{
			$objPage = $this->getViewOnlinePage($objCategory, $arrRecipient);
		}
		
		return $this->DomainLink->absolutizeUrl($strUrl, $objPage);
	}
}
?>