<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
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
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class AvisotaBackend
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBackend extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->import('DomainLink');
	}


	public function hookOutputBackendTemplate($strContent, $strTemplate)
	{
		if ($strTemplate == 'be_main')
		{
			# add form multipart enctype
			if (($this->Input->get('table') == 'tl_avisota_recipient_import' || $this->Input->get('table') == 'tl_avisota_recipient_remove'))
			{
				$strContent = str_replace('<form', '<form enctype="multipart/form-data"', $strContent);
			}
		}
		return $strContent;
	}


	/**
	 * Clean up recipient list.
	 */
	public function cronCleanupRecipientList()
	{
		$objModule = $this->Database
			->execute("SELECT * FROM tl_module WHERE type='avisota_subscription' AND avisota_do_cleanup='1' AND avisota_cleanup_time>0");
		while ($objModule->next())
		{
			$objRecipient = $this->Database
				->prepare("SELECT * FROM tl_avisota_recipient WHERE confirmed='' AND token!='' AND addedOn<=? AND addedByModule=?")
				->execute(mktime(0,0,0)-($objModule->avisota_cleanup_time * 24 * 60 * 60), $objModule->id);
			while ($objRecipient->next())
			{
				$this->log('Remove unconfirmed recipient ' . $objRecipient->email, 'AvisotaBackend::cronCleanupRecipientList', TL_INFO);

				$this->Database
					->prepare("DELETE FROM tl_avisota_recipient WHERE id=?")
					->execute($objRecipient->id);
			}
		}
	}


	/**
	 * Send notifications.
	 */
	public function cronNotifyRecipients()
	{
		$this->loadLanguageFile('avisota');

		$objModule = $this->Database
			->execute("SELECT * FROM tl_module WHERE type='avisota_subscription' AND avisota_send_notification='1' AND avisota_notification_time>0");
		while ($objModule->next())
		{
			$objRecipient = $this->Database
				->prepare("SELECT addedOnPage, email, GROUP_CONCAT(pid) as lists, GROUP_CONCAT(id) as ids, GROUP_CONCAT(token) as tokens
					FROM tl_avisota_recipient
					WHERE confirmed='' AND token!='' AND addedOn<=? AND addedByModule=? AND notification=''
					GROUP BY addedOnPage,email")
				->execute(mktime(0,0,0)-($objModule->avisota_notification_time * 24 * 60 * 60), $objModule->id);
			while ($objRecipient->next())
			{
				// HOOK: add custom logic
				if (isset($GLOBALS['TL_HOOKS']['avisotaNotifyRecipient']) && is_array($GLOBALS['TL_HOOKS']['avisotaNotifyRecipient']))
				{
					foreach ($GLOBALS['TL_HOOKS']['avisotaNotifyRecipient'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($objRecipient->row());
					}
				}

				$objPage = $this->getPageDetails($objRecipient->addedOnPage);
				$strUrl = $this->DomainLink->absolutizeUrl($this->generateFrontendUrl($objPage->row()) . '?subscribetoken=' . $objRecipient->tokens, $objPage);

				$arrList = $this->getListNames(explode(',', $objRecipient->lists));

				$objPlain = new FrontendTemplate($objModule->avisota_template_notification_mail_plain);
				$objPlain->content = sprintf($GLOBALS['TL_LANG']['avisota']['notification']['mail']['plain'], implode(', ', $arrList), $strUrl);

				$objHtml = new FrontendTemplate($objModule->avisota_template_notification_mail_html);
				$objHtml->title = $GLOBALS['TL_LANG']['avisota']['notification']['mail']['subject'];
				$objHtml->content = sprintf($GLOBALS['TL_LANG']['avisota']['notification']['mail']['html'], implode(', ', $arrList), $strUrl);

				if (($strError = $this->sendMail($objModule, $objPage, $objPlain->parse(), $objHtml->parse(), $objRecipient->email)) === true)
				{
					$this->log('Notify recipient ' . $objRecipient->email . ' for activation', 'AvisotaBackend::cronNotifyRecipients', TL_INFO);
				}
				else
				{
					$this->log('Notify recipient ' . $objRecipient->email . ' for activation failed: ' . $strError, 'AvisotaBackend::cronNotifyRecipients', TL_ERROR);
				}

				$this->Database
					->execute("UPDATE tl_avisota_recipient SET notification='1' WHERE id IN (" . $objRecipient->ids . ")");
			}
		}
	}


	/**
	 * Send an email.
	 *
	 * @param string $strMode
	 * @param string $strPlain
	 * @param string $strHtml
	 * @param string $strRecipient
	 */
	protected function sendMail($objModule, $objPage, $strPlain, $strHtml, $strRecipient)
	{
		$objRoot = $this->getPageDetails($objPage->rootId);

		$objEmail = new Email();

		$objEmail->subject = $GLOBALS['TL_LANG']['avisota']['notification']['mail']['subject'];
		$objEmail->logFile = 'subscription.log';
		$objEmail->text = $strPlain;
		$objEmail->html = $strHtml;

		$objEmail->from = $objModule->avisota_subscription_sender ? $objModule->avisota_subscription_sender : (strlen($objRoot->adminEmail) ? $objRoot->adminEmail : $GLOBALS['TL_CONFIG']['adminEmail']);

		// Add sender name
		if (strlen($objModule->avisota_subscription_sender_name))
		{
			$objEmail->fromName = $objModule->avisota_subscription_sender_name;
		}

		$objEmail->imageDir = TL_ROOT . '/';

		try
		{
			$objEmail->sendTo($strRecipient);
			return true;
		}
		catch (Swift_RfcComplianceException $e)
		{
			return $e->getMessage();
		}
	}


	/**
	 * Convert id list to name list.
	 *
	 * @param array $arrListIds
	 * @return array
	 */
	protected function getListNames($arrListIds)
	{
		$arrList = array();

		$arrPlaceholder = array();
		for ($i=0; $i<count($arrListIds); $i++)
		{
			$arrPlaceholder[] = '?';
		}

		$objList = $this->Database->prepare("
					SELECT
						*
					FROM
						`tl_avisota_recipient_list`
					WHERE
						`id` IN (" . implode(',', $arrPlaceholder) . ")
					ORDER BY
						`title`")
				->execute($arrListIds);
		while ($objList->next())
		{
			$arrList[] = $objList->title;
		}

		return $arrList;
	}
}
?>