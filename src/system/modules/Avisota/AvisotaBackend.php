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
 * Class AvisotaBackend
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBackend extends Controller
{
	protected static $objInstance = null;

	public static function getInstance()
	{
		if (self::$objInstance === null) {
			self::$objInstance = new AvisotaBackend();
		}
		return self::$objInstance;
	}

	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}


	/**
	 * Get options list of recipients.
	 *
	 * @return array
	 */
	public function getRecipients()
	{
		$arrRecipients = array();

		foreach ($GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE'] as $strSource=>$strClass)
		{
			$this->import($strClass);

		}

		$objList = $this->Database->execute("
				SELECT
					*
				FROM
					`tl_avisota_recipient_list`
				ORDER BY
					`title`");
		while ($objList->next())
		{
			$arrRecipients[$GLOBALS['TL_LANG']['tl_avisota_newsletter']['list']]['list-' . $objList->id] = $objList->title;
		}

		$objMember = $this->Database->execute("
				SELECT
					*
				FROM
					`tl_member_group`
				ORDER BY
					`name`");
		while ($objMember->next())
		{
			$arrRecipients[$GLOBALS['TL_LANG']['tl_avisota_newsletter']['mgroup']]['mgroup-' . $objMember->id] = $objMember->name;
		}

		return $arrRecipients;
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

	public function hookAvisotaMailingListLabel($arrRow, $strLabel, DataContainer $dc)
	{
		$objResult = $this->Database
			->prepare("SELECT
				(SELECT COUNT(rl.recipient) FROM tl_avisota_recipient_to_mailing_list rl WHERE rl.list=?) as total_recipients,
				(SELECT COUNT(rl.recipient) FROM tl_avisota_recipient_to_mailing_list rl INNER JOIN tl_avisota_recipient r ON r.id=rl.recipient WHERE r.confirmed=? AND rl.list=?) as disabled_recipients,
				(SELECT COUNT(ml.member) FROM tl_member_to_mailing_list ml WHERE ml.list=?) as total_members,
				(SELECT COUNT(ml.member) FROM tl_member_to_mailing_list ml INNER JOIN tl_member m ON m.id=ml.member WHERE m.disable=? AND ml.list=?) as disabled_members")
			->execute($arrRow['id'], '', $arrRow['id'], $arrRow['id'], '1', $arrRow['id']);
		if ($objResult->next()) {
			if ($objResult->total_recipients > 0) {
				$strLabel .= '<div style="padding: 1px 0;">' .
					'<a href="contao/main.php?do=avisota_recipients&amp;showlist=' . $arrRow['id'] . '">' .
					$this->generateImage('system/modules/Avisota/html/recipients.png', '') .
					' ' .
					sprintf($GLOBALS['TL_LANG']['tl_avisota_mailing_list']['label_recipients'],
					$objResult->total_recipients,
					$objResult->total_recipients - $objResult->disabled_recipients,
					$objResult->disabled_recipients) .
					'</a>' .
					'</div>';
			}
			if ($objResult->total_members > 0) {
				$strLabel .= '<div style="padding: 1px 0;">' .
					'<a href="contao/main.php?do=member&amp;avisota_showlist=' . $arrRow['id'] . '">' .
					$this->generateImage('system/themes/default/images/member.gif', '') .
					' ' .
					sprintf($GLOBALS['TL_LANG']['tl_avisota_mailing_list']['label_members'],
					$objResult->total_members,
					$objResult->total_members - $objResult->disabled_members,
					$objResult->disabled_members) .
					'</a>' .
					'</div>';
			}
		}
		return $strLabel;
	}


	/**
	 * Clean up recipient list.
	 */
	public function cronCleanupRecipientList()
	{
		$this->import('Database');

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
		$this->import('Database');
		$this->import('DomainLink');
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
						`tl_avisota_mailing_list`
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
