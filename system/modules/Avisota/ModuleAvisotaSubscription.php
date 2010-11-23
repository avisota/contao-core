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
 * Class ModuleAvisotaSubscription
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class ModuleAvisotaSubscription extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_avisota_subscription';
	
	
	/**
	 * Construct the content element
	 */
	public function __construct(Database_Result $objModule)
	{
		parent::__construct($objModule);
		$this->import('DomainLink');
		$this->import('FrontendUser', 'User');
		$this->loadLanguageFile('avisota');
	}
	
	
	/**
	 * Find the email adress and lists.
	 * 
	 * Note: $strEmail and $arrListIds are set back by reference!
	 * 
	 * @param string $strMode
	 * @param string $strEmail
	 * @param array $arrListIds
	 */
	protected function findData($strMode, &$strEmail, &$arrListIds, $blnIgnoreSubsription)
	{
		$strEmail = $this->Input->post('email');
		
		if (FE_USER_LOGGED_IN && !$strEmail)
		{
			$strEmail = $this->User->email;
		}
		
		$arrSubscriptions = array();
		if ($blnIgnoreSubsription)
		{
			$objSubscription = $this->Database->prepare("
						SELECT
							*
						FROM
							`tl_avisota_recipient`
						WHERE
							`email`=?")
				->execute($strEmail);
			while ($objSubscription->next())
			{
				$arrSubscriptions[] = $objSubscription->pid;
			}
		}
		
		if ($this->avisota_show_lists)
		{
			$arrList = $this->Input->post('list');
			
			$arrPlaceholder = array();
			for ($i=0; $i<count($arrList); $i++)
			{
				$arrPlaceholder[] = '?';
			}
			
			$objList = $this->Database->prepare("
					SELECT
						*
					FROM
						`tl_avisota_recipient_list`
					WHERE
						`alias` IN (" . implode(',', $arrPlaceholder) . ")
						" . ($blnIgnoreSubsription && count($arrSubscriptions) ? " AND `id` NOT IN (" . implode(',', $arrSubscriptions) . ")" : ''))
				->execute($arrList);
			$arrListIds = array();
			while ($objList->next())
			{
				$arrListIds[] = $objList->id;
			}
		}
		else
		{
			$arrListIds = deserialize($this->avisota_lists);
			if ($blnIgnoreSubsription && count($arrSubscriptions))
			{
				$arrListIds = array_diff($arrListIds, $arrSubscriptions);
			}
		}
	}
	
	
	/**
	 * Generate the subscription confirm url for all tokens.
	 * 
	 * @param array $arrTokens
	 */
	protected function generateSubscribeUrl($arrTokens)
	{
		return $this->DomainLink->generateDomainLink($GLOBALS['objPage']->row(), '', $this->generateFrontendUrl($GLOBALS['objPage']->row()) . '?subscribetoken[]=' . implode('&subscribetoken[]=', $arrTokens), true);
	}
	
	
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
						`alias` IN (" . implode(',', $arrPlaceholder) . ")
					ORDER BY
						`title`")
				->execute($arrListIds);
		while ($objList->next())
		{
			$arrList[] = $objList->title;
		}
		
		return $arrList;
	}
	
	
	/**
	 * Send an email.
	 * 
	 * @param string $strMode
	 * @param string $strPlain
	 * @param string $strHtml
	 * @param string $strRecipient
	 */
	protected function sendMail($strMode, $strPlain, $strHtml, $strRecipient)
	{
		$objEmail = new Email();
		
		$objEmail->subject = $GLOBALS['TL_LANG']['avisota'][$strMode]['mail']['subject'];
		$objEmail->logFile = 'subscription.log';
		$objEmail->text = $strPlain;
		$objEmail->html = $strHtml;
		
		$objEmail->from = $this->sender ? $this->sender : $GLOBALS['TL_CONFIG']['adminEmail'];
		
		// Add sender name
		if (strlen($this->senderName))
		{
			$objEmail->fromName = $this->senderName;
		}

		// Attachments
		$arrAttachments = $this->addFile ? unserialize($this->files, true) : false;
		if (is_array($arrAttachments) && count($arrAttachments) > 0)
		{
			foreach ($arrAttachments as $strAttachment)
			{
				$objEmail->attachFile(TL_ROOT . '/' . $strAttachment);
			}
		}
		
		$objEmail->imageDir = TL_ROOT . '/';

		try
		{
			$objEmail->sendTo($strRecipient);
			return true;
		}
		catch (Swift_RfcComplianceException $e)
		{
			return false;
		}
	}
	
	
	/**
	 * Handle subscribe
	 */
	public function subscribe()
	{
		$this->findData('subscribe', $strEmail, $arrListIds, true);
		if ($strEmail && count($arrListIds))
		{
			if (!count($arrListIds))
			{
				$_SESSION['avisota_subscription'][] = $GLOBALS['TL_LANG']['avisota']['subscription']['no_new'];
				$this->redirect($this->Environment->request);
			}
			
			$time = time();
			$arrTitles = array();
			$arrTokens = array();
			foreach ($arrListIds as $intId)
			{
				$arrTitles[] = $arrListIds[$intId];
				$strToken = md5($time . $arrListIds[$intId] . $intId. $strEmail);
				$arrTokens[] = $strToken;
				$this->Database->prepare("
						INSERT INTO
							`tl_avisota_recipient`
							(`pid`, `tstamp`, `email`, `confirmed`, `token`, `addedOn`)
						VALUES
							(?,?,?,?,?,?)")
					->execute($intId, $time, $strEmail, '', $strToken, $time);
			}
			
			$strUrl = $this->generateSubscribeUrl($arrTokens);
			
			$arrList = $this->getListNames($arrListIds);
			
			$objPlain = new FrontendTemplate($this->avisota_template_subscribe_mail_plain);
			$objPlain->content = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['plain'], implode(', ', $arrList), $strUrl);
	
			$objHtml = new FrontendTemplate($this->avisota_template_subscribe_mail_html);
			$objHtml->title = $GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['subject'];
			$objHtml->content = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['html'], implode(', ', $arrList), $strUrl);
			
			if ($this->sendMail('subscribe', $objPlain->parse(), $objHtml->parse(), $strEmail))
			{
				$_SESSION['avisota_subscription'][] = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['send'], $strEmail);
			}
			else
			{
				$_SESSION['avisota_subscription'][] = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['rejected'], $strRecipient);
			}
			
			$this->redirect($this->Environment->request);
		}
	}
	
	
	/**
	 * Handle unsubscribe
	 */
	public function unsubscribe()
	{
		$this->findData('unsubscribe', $strEmail, $arrList);
	}
	
	
	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### Avisota Subscription ###';
			return $objTemplate->parse();
		}
		
		return parent::generate();
	}
	
	
	/**
	 * Generate the content element
	 */
	public function compile()
	{
		if (!isset($_SESSION['avisota_subscription']) || !is_array($_SESSION['avisota_subscription']))
		{
			$_SESSION['avisota_subscription'] = array();
		}
		if ($this->Input->post('subscribe'))
		{
			$this->subscribe();
		}
		if ($this->Input->post('unsubscribe'))
		{
			$this->unsubscribe();
		}
		
		$this->Template->messages = $_SESSION['avisota_subscription'];
		unset($_SESSION['avisota_subscription']);
		
		if ($this->avisota_show_lists)
		{
			$arrLists = array();
			$objLists = $this->Database->execute("
					SELECT
						*
					FROM
						`tl_avisota_recipient_list`
					WHERE
						`id` IN (" . implode(',', deserialize($this->avisota_lists)) . ")
					ORDER BY
						`title`");
			while ($objLists->next())
			{
				$arrLists[$objLists->id] = $objLists->title;
			}
			$this->Template->lists = $arrLists;
		}
		
		$this->Template->formId = 'avisota_subscription_' . $this->id;
		$this->Template->formAction = $this->generateFrontendUrl($this->jumpTo ? $this->Database->prepare("SELECT * FROM `tl_page` WHERE `id`=?")->execute($this->jumpTo)->row() : $GLOBALS['objPage']->row());
	}
}

?>