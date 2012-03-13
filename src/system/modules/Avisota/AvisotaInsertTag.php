<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * Class AvisotaInsertTag
 *
 * InsertTag hook class.
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaInsertTag extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->import('DomainLink');
		$this->import('AvisotaBase', 'Base');
		$this->import('AvisotaStatic', 'Static');
	}


	public function hookReplaceNewsletterInsertTags($strTag)
	{
		$strTag = explode('::', $strTag);
		switch ($strTag[0])
		{
		case 'recipient':
			$arrCurrentRecipient = $this->Static->getRecipient();

			if ($arrCurrentRecipient)
			{
				switch ($strTag[1])
				{
				default:
					if ($arrCurrentRecipient && isset($arrCurrentRecipient[$strTag[1]]))
					{
						return $arrCurrentRecipient[$strTag[1]];
					}
				}
			}
			return '';

		case 'newsletter':
			$arrCurrentRecipient = $this->Static->getRecipient();
			$objCategory = $this->Static->getCategory();
			$objNewsletter = $this->Static->getNewsletter();

			if ($arrCurrentRecipient && $objCategory && $objNewsletter)
			{
				switch ($strTag[1])
				{
				case 'href':
					$objPage = $this->Base->getViewOnlinePage($objCategory, $arrCurrentRecipient);
					if ($objPage)
					{
						return $this->Base->extendURL($this->generateFrontendUrl($objPage->row(), '/item/' . ($objNewsletter->alias ? $objNewsletter->alias : $objNewsletter->id)), $objPage);
					}
					break;

				case 'unsubscribe':
				case 'unsubscribe_url':
					$strAlias = false;
					if ($arrCurrentRecipient['source'] == 'list')
					{
						$objRecipientList = $this->Database
							->prepare("SELECT * FROM tl_avisota_mailing_list WHERE id=?")
							->execute($arrCurrentRecipient['sourceID']);
						if (!$objRecipientList->next())
						{
							return '';
						}
						$strAlias = $objRecipientList->alias;
						$objPage = $this->getPageDetails($objRecipientList->subscriptionPage);
					}
					else if ($arrCurrentRecipient['source'] == 'mgroup')
					{
						if ($objCategory->subscriptionPage > 0)
						{
							$objPage = $this->getPageDetails($objCategory->subscriptionPage);
						}
					}

					if ($objPage)
					{
						$strUrl = $this->Base->extendURL($this->generateFrontendUrl($objPage->row()) . '?' . ($arrCurrentRecipient['email'] ? 'email=' . $arrCurrentRecipient['email'] : '') . ($strAlias ? '&unsubscribetoken=' . $strAlias : ''));
					}
					else
					{
						$strUrl = $this->Base->extendURL('?' . ($arrCurrentRecipient['email'] ? 'email=' . $arrCurrentRecipient['email'] : '') . ($strAlias ? '&unsubscribetoken=' . $strAlias : ''));
					}

					if ($strTag[1] == 'unsubscribe_url')
					{
						return $strUrl;
					}

					switch ($strTag[2])
					{
						case 'html':
							return sprintf('<a href="%s">%s</a>', $strUrl, $GLOBALS['TL_LANG']['tl_avisota_newsletter']['unsubscribe']);

						case 'plain':
							return sprintf("%s\n[%s]", $GLOBALS['TL_LANG']['tl_avisota_newsletter']['unsubscribe'], $strUrl);
					}
					break;
				}
			}
			return '';

		case 'newsletter_latest_link':
		case 'newsletter_latest_url':
			if (strlen($strTag[1]))
			{
				$strId = "'" . implode("','", trimsplit(',', $strTag[1])) . "'";
				$objNewsletter = $this->Database->prepare("
						SELECT
							n.*,
							c.`viewOnlinePage`,
							c.`subscriptionPage`
						FROM
							`tl_avisota_newsletter` n
						INNER JOIN
							`tl_avisota_newsletter_category` c
						ON
							c.`id`=n.`pid`
						WHERE
							(	c.`id` IN ($strId)
							OR	c.`alias` IN ($strId))
							AND n.`sendOn`!=''
						ORDER BY
							n.`sendOn` DESC")
					->limit(1)
					->execute();
				if ($objNewsletter->next())
				{
					if (strlen($strTag[2]))
					{
						$objPage = $this->Database->prepare("
								SELECT
									*
								FROM
									`tl_page`
								WHERE
										`id`=?
									OR	`alias`=?")
							->execute($strTag[2], $strTag[2]);
						if (!$objPage->next())
						{
							$objPage = false;
						}
					}
					else
					{
						$objPage = $this->Base->getViewOnlinePage($objNewsletter, false);
					}
					if ($objPage)
					{
						$strUrl = $this->Base->extendURL($this->generateFrontendUrl($objPage->row(), '/item/' . ($objNewsletter->alias ? $objNewsletter->alias : $objNewsletter->id)), $objPage);
						if ($strTag[0] == 'newsletter_latest_link')
						{
							$this->loadLanguageFile('avisota');
							return sprintf($GLOBALS['TL_LANG']['avisota']['latest_link'], specialchars($strUrl));
						}
						else
						{
							return $strUrl;
						}
					}
				}
			}
			return '';
		}
		return false;
	}
}
?>