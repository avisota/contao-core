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
 * Class ModuleAvisotaSubscription
 *
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class ModuleAvisotaSubscription extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_subscription';


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
	protected function findData(&$strEmail, &$arrListIds, $varExistingSubscription = false)
	{
		$strEmail = $this->Input->post('email');

		if (!$strEmail)
		{
			$strEmail = $this->Input->get('email');
		}

		if (FE_USER_LOGGED_IN && !$strEmail)
		{
			$strEmail = $this->User->email;
		}

		// check for valid email address
		if (!$this->isValidEmailAddress($strEmail))
		{
			$strEmail = false;
			return;
		}

		$arrSubscriptions = array();
		if ($varExistingSubscription)
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

		if ($this->avisota_show_lists && $varExistingSubscription != 'only')
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
						`id` IN (" . implode(',', $arrPlaceholder) . ")
						" . ($varExistingSubscription == 'ignore' && count($arrSubscriptions) ? " AND `id` NOT IN (" . implode(',', $arrSubscriptions) . ")" : ''))
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
			if ($varExistingSubscription == 'ignore' && count($arrSubscriptions))
			{
				$arrListIds = array_diff($arrListIds, $arrSubscriptions);
			}
			if ($varExistingSubscription == 'only')
			{
				$arrListIds = array_intersect($arrListIds, $arrSubscriptions);
			}
		}
	}


	/**
	 * Generate the subscription confirm url for all tokens.
	 *
	 * @param array $arrTokens
	 * @return string
	 */
	protected function generateSubscribeUrl($arrTokens)
	{
		return $this->DomainLink->absolutizeUrl($this->generateFrontendUrl($GLOBALS['objPage']->row()) . '?subscribetoken=' . implode(',', $arrTokens), $GLOBALS['objPage']);
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
		global $objPage;
		$objRoot = $this->getPageDetails($objPage->rootId);

		$objEmail = new Email();

		$objEmail->subject = $GLOBALS['TL_LANG']['avisota'][$strMode]['mail']['subject'];
		$objEmail->logFile = 'subscription.log';
		$objEmail->text = $strPlain;
		$objEmail->html = $strHtml;

		$objEmail->from = $this->avisota_subscription_sender ? $this->avisota_subscription_sender : (strlen($objRoot->adminEmail) ? $objRoot->adminEmail : $GLOBALS['TL_CONFIG']['adminEmail']);

		// Add sender name
		if (strlen($this->avisota_subscription_sender_name))
		{
			$objEmail->fromName = $this->avisota_subscription_sender_name;
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
	 * Filter out subscribed lists.
	 */
	protected function filterSubscribed(&$arrRecipient)
	{
		$arrLists = $this->Database
				->prepare("SELECT * FROM tl_avisota_recipient WHERE email=?")
				->execute($arrRecipient['email'])
				->fetchEach('pid');
		$arrRecipient['lists'] = array_diff($arrRecipient['lists'], $arrLists);
	}


	/**
	 * Handle subscribe
	 */
	protected function subscribe($arrRecipient)
	{
		$this->filterSubscribed($arrRecipient);

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaPrepareRecipient']) && is_array($GLOBALS['TL_HOOKS']['avisotaPrepareRecipient']))
		{
			foreach ($GLOBALS['TL_HOOKS']['avisotaPrepareRecipient'] as $callback)
			{
				$this->import($callback[0]);
				$varTemp = $this->$callback[0]->$callback[1]($arrRecipient);
				if (is_array($varTemp))
				{
					$arrRecipient = $varTemp;
				}
			}
		}

		$time = time();
		$arrTokens = array();
		foreach ($arrRecipient['lists'] as $intId)
		{
			$arrTokens[$intId] = md5($time . '-' . $intId . '-' . $arrRecipient['email']);
		}
		if (!count($arrTokens))
		{
			$_SESSION['avisota_subscription'][] = $GLOBALS['TL_LANG']['avisota']['subscription']['empty'].'|error';
			return;
		}

		$strUrl = $this->generateSubscribeUrl($arrTokens);

		$arrList = $this->getListNames($arrRecipient['lists']);

		$objPlain = new FrontendTemplate($this->avisota_template_subscribe_mail_plain);
		$objPlain->content = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['plain'], implode(', ', $arrList), $strUrl);

		$objHtml = new FrontendTemplate($this->avisota_template_subscribe_mail_html);
		$objHtml->title = $GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['subject'];
		$objHtml->content = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['html'], implode(', ', $arrList), $strUrl);

		if ($this->sendMail('subscribe', $this->replaceInsertTags($objPlain->parse()), $this->replaceInsertTags($objHtml->parse()), $arrRecipient['email']))
		{
			unset($arrRecipient['lists']);
			$arrRecipient['tstamp'] = $time;
			$arrRecipient['confirmed'] = '';
			$arrRecipient['addedOn'] = $time;
			$arrRecipient['addedByModule'] = $this->id;
			$arrRecipient['addedOnPage'] = $GLOBALS['objPage']->id;
			foreach ($arrTokens as $intId => $strToken)
			{
				$arrRecipient['pid'] = $intId;
				$arrRecipient['token'] = $strToken;
				$this->Database->prepare("INSERT INTO `tl_avisota_recipient` %s")
					->set($arrRecipient)
					->execute();

				$this->Database->prepare("DELETE FROM tl_avisota_recipient_blacklist WHERE pid=? AND email=?")
					->execute($intId, md5($arrRecipient['email']));
			}

			$_SESSION['avisota_subscription'][] = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['send'], $arrRecipient['email']).'|confirmation';
			$this->log('Add new recipient ' . $arrRecipient['email'] . ' to ' . implode(', ', $arrList), 'ModuleAvisotaSubscription::subscribe', TL_INFO);
		}
		else
		{
			$_SESSION['avisota_subscription'][] = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['rejected'], $arrRecipient['email']).'|error';
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaSubscribe']) && is_array($GLOBALS['TL_HOOKS']['avisotaSubscribe']))
		{
			foreach ($GLOBALS['TL_HOOKS']['avisotaSubscribe'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($arrRecipient, $arrTokens);
			}
		}

		$this->redirect($this->Environment->request);
	}


	/**
	 * Handle subscribetoken's
	 */
	protected function subscribetoken()
	{
		$arrSubscribetoken = explode(',', $this->Input->get('subscribetoken'));
		if (is_array($arrSubscribetoken) && count($arrSubscribetoken) > 0)
		{
			foreach ($arrSubscribetoken as $strToken)
			{
				$objRecipient = $this->Database->prepare("
						SELECT
							r.id,
							r.email,
							l.title
						FROM
							`tl_avisota_recipient` r
						INNER JOIN
							`tl_avisota_recipient_list` l
						ON
							r.`pid`=l.`id`
						WHERE
							r.`token`=?")
					->execute($strToken);
				if ($objRecipient->next())
				{
					$this->Database->prepare("
							UPDATE
								`tl_avisota_recipient`
							SET
								`confirmed`='1',
								`token`=''
							WHERE
								`id`=?")
						->execute($objRecipient->id);
					$_SESSION['avisota_subscription'][] = sprintf($GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['confirm'], $objRecipient->title).'|confirmation';

					// HOOK: add custom logic
					if (isset($GLOBALS['TL_HOOKS']['avisotaActivateSubscribtion']) && is_array($GLOBALS['TL_HOOKS']['avisotaActivateSubscribtion']))
					{
						foreach ($GLOBALS['TL_HOOKS']['avisotaActivateSubscribtion'] as $callback)
						{
							$this->import($callback[0]);
							$this->$callback[0]->$callback[1]($objRecipient->id, $objRecipient->email, $strToken);
						}
					}
				}
			}

			$this->redirect(preg_replace('#(&amp;|&)?subscribetoken=[^&]+#', '', $this->Environment->request));
		}
	}

	/**
	 * Handle unsubscribe
	 */
	protected function unsubscribe($arrRecipient)
	{
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['avisotaUnsubscribe']) && is_array($GLOBALS['TL_HOOKS']['avisotaUnsubscribe']))
		{
			foreach ($GLOBALS['TL_HOOKS']['avisotaUnsubscribe'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($arrRecipient);
			}
		}

		$this->remove_subscription($arrRecipient['email'], $arrRecipient['lists']);
	}


	/**
	 * Handle unsubscribetoken
	 */
	protected function unsubscribetoken()
	{
		$this->findData($strEmail, $arrListIds, 'only');
		if ($strEmail)
		{
			$strAlias = $this->Input->get('unsubscribetoken');

			$objRecipientList = $this->Database->prepare("
					SELECT
						*
					FROM
						`tl_avisota_recipient_list`
					WHERE
						`alias`=?")
				->execute($strAlias);

			if ($objRecipientList->next() && in_array($objRecipientList->id, $arrListIds))
			{
				// HOOK: add custom logic
				if (isset($GLOBALS['TL_HOOKS']['avisotaUnsubscribe']) && is_array($GLOBALS['TL_HOOKS']['avisotaUnsubscribe']))
				{
					foreach ($GLOBALS['TL_HOOKS']['avisotaUnsubscribe'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($strEmail, array($objRecipientList->id));
					}
				}

				$this->remove_subscription($strEmail, array($objRecipientList->id));
			}
		}
	}


	/**
	 * Remove subscription from given lists.
	 *
	 * @param string $strEmail
	 * @param array $arrListIds
	 */
	protected function remove_subscription($strEmail, $arrListIds)
	{
		if (!count($arrListIds))
		{
			$_SESSION['avisota_subscription'][] = $GLOBALS['TL_LANG']['avisota']['unsubscribe']['empty'].'|info';
			$this->redirect($this->Environment->request);
		}

		$this->Database->prepare("
				DELETE FROM
					`tl_avisota_recipient`
				WHERE
						`email`=?
					AND `pid` IN (" . implode(',', $arrListIds) . ")")
			->execute($strEmail);

		// build blacklist
		foreach ($arrListIds as $intId)
		{
			$this->Database->prepare("INSERT INTO tl_avisota_recipient_blacklist SET pid=?, tstamp=?, email=?")
				->execute($intId, time(), md5($strEmail));
		}

		$strUrl = $this->DomainLink->absolutizeUrl(preg_replace('#&?unsubscribetoken=\w+#', '', $this->Environment->request), $GLOBALS['objPage']);

		$arrList = $this->getListNames($arrListIds);

		$objPlain = new FrontendTemplate($this->avisota_template_unsubscribe_mail_plain);
		$objPlain->content = sprintf($GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['plain'], implode(', ', $arrList), $strUrl);

		$objHtml = new FrontendTemplate($this->avisota_template_unsubscribe_mail_html);
		$objHtml->title = $GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['subject'];
		$objHtml->content = sprintf($GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['html'], implode(', ', $arrList), $strUrl);

		$this->sendMail('unsubscribe', $this->replaceInsertTags($objPlain->parse()), $this->replaceInsertTags($objHtml->parse()), $strEmail);
		$_SESSION['avisota_subscription'][] = sprintf($GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['confirm'], $strEmail).'|confirmation';

		$this->redirect(preg_replace('#&?unsubscribetoken=\w+#', '', $this->Environment->request));
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

		$this->avisota_recipient_fields = deserialize($this->avisota_recipient_fields, true);
		$this->avisota_lists = array_filter(array_map('intval', deserialize($this->avisota_lists, true)));

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

		global $objPage;

		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');

		// Call onload_callback (e.g. to check permissions)
		if (is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1](null);
				}
			}
		}

		if (strlen($this->avisota_template_subscription))
		{
			$this->Template = new FrontendTemplate($this->avisota_template_subscription);
			$this->Template->setData($this->arrData);
		}

		$this->Template->tableless = $this->tableless;
		$this->Template->fields = '';
		$doNotSubmit = false;

		$arrEditable = array_merge
		(
			array('email'),
			($this->avisota_show_lists ? array('lists') : array()),
			$this->avisota_recipient_fields
		);

		$arrRecipient = array();
		$arrFields = array();
		$hasUpload = false;
		$i = 0;

		// add the lists options
		if ($this->avisota_show_lists)
		{

			$objList = $this->Database
				->execute("SELECT
						*
					FROM
						tl_avisota_recipient_list" . (count($this->avisota_lists) ? "
					WHERE
						id IN (" . implode(',', $this->avisota_lists) . ")" : '') . "
					ORDER BY
						title");
			while ($objList->next())
			{
				$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['lists']['options'][$objList->id] = $objList->title;
			}
		}

		// or set selected lists, if they are not displayed
		else if (count($this->avisota_lists))
		{
			$arrRecipient['lists'] = $this->avisota_lists;
		}

		// or use all, if there are no lists selected
		else
		{
			$arrRecipient['lists'] = $this->Database->execute("SELECT id FROM tl_avisota_recipient_list")->fetchEach('id');
		}

		// on unsubscribe, only email and lists is mandatory!
		if ($this->Input->post('FORM_SUBMIT') == 'tl_avisota_recipient' && $this->Input->post('unsubscribe'))
		{
			foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $strField => $arrData)
			{
				$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$strField]['eval']['mandatory'] = ($strField == 'email' || $strField == 'lists');
			}
		}

		// Build form
		foreach ($arrEditable as $field)
		{
			$arrData = $GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$field];

			// Map checkboxWizard to regular checkbox widget
			if ($arrData['inputType'] == 'checkboxWizard')
			{
				$arrData['inputType'] = 'checkbox';
			}

			$strClass = $GLOBALS['TL_FFL'][$arrData['inputType']];

			// Continue if the class is not defined
			if (!$this->classFileExists($strClass))
			{
				continue;
			}

			$arrData['eval']['tableless'] = $this->tableless;
			$arrData['eval']['required'] = $arrData['eval']['mandatory'];

			$objWidget = new $strClass($this->prepareForWidget($arrData, $field, $arrData['default']));

			$objWidget->storeValues = true;
			$objWidget->rowClass = 'row_' . $i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');

			// Increase the row count if its a password field
			if ($objWidget instanceof FormPassword)
			{
				$objWidget->rowClassConfirm = 'row_' . ++$i . ((($i % 2) == 0) ? ' even' : ' odd');
			}

			// Validate input
			if ($this->Input->post('FORM_SUBMIT') == 'tl_avisota_recipient')
			{
				$objWidget->validate();
				$varValue = $objWidget->value;

				$rgxp = $arrData['eval']['rgxp'];

				// Convert date formats into timestamps (check the eval setting first -> #3063)
				if (($rgxp == 'date' || $rgxp == 'time' || $rgxp == 'datim') && $varValue != '')
				{
					$objDate = new Date($varValue, $GLOBALS['TL_CONFIG'][$rgxp . 'Format']);
					$varValue = $objDate->tstamp;
				}

				// Make sure that unique fields are unique (check the eval setting first -> #3063)
				if ($arrData['eval']['unique'] && $varValue != '')
				{
					$objUnique = $this->Database->prepare("SELECT * FROM tl_avisota_recipient WHERE " . $field . "=?")
												->limit(1)
												->execute($varValue);

					if ($objUnique->numRows)
					{
						$objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['unique'], (strlen($arrData['label'][0]) ? $arrData['label'][0] : $field)));
					}
				}

				// Save callback
				if (is_array($arrData['save_callback']))
				{
					foreach ($arrData['save_callback'] as $callback)
					{
						$this->import($callback[0]);

						try
						{
							$varValue = $this->$callback[0]->$callback[1]($varValue, $this->User);
						}
						catch (Exception $e)
						{
							$objWidget->class = 'error';
							$objWidget->addError($e->getMessage());
						}
					}
				}

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}

				// Store current value
				$arrRecipient[$field] = $varValue;
			}

			if ($objWidget instanceof uploadable)
			{
				$hasUpload = true;
			}

			$temp = $objWidget->parse();

			$this->Template->fields .= $temp;
			$arrFields[$field] = $temp;

			++$i;
		}

		// lists have to be an array
		if (!is_array($arrRecipient['lists']))
		{
			$arrRecipient['lists'] = array($arrRecipient['lists']);
		}

		$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$this->Template->hasError = $doNotSubmit;

		if ($this->Input->post('FORM_SUBMIT') == 'tl_avisota_recipient' && !$doNotSubmit)
		{
			if ($this->Input->post('subscribe'))
			{
				$this->subscribe($arrRecipient);
			}
			if ($this->Input->post('unsubscribe'))
			{
				$this->unsubscribe($arrRecipient);
			}
		}

		if ($this->Input->get('subscribetoken'))
		{
			$this->subscribetoken();
		}
		if ($this->Input->get('unsubscribetoken'))
		{
			$this->unsubscribetoken();
		}

		// Add fields
		foreach ($arrFields as $k=>$v)
		{
			$this->Template->$k = $v;
		}

		// add messages
		$arrMessages = array
		(
			'confirmation' => array(),
			'info' => array(),
			'error' => array()
		);
		foreach ($_SESSION['avisota_subscription'] as $strMessage)
		{
			list($strMessage, $strClass) = explode('|', $strMessage, 2);
			if (empty($strClass))
			{
				$strClass = 'info';
			}
			if (!isset($arrMessages[$strClass]))
			{
				$arrMessages[$strClass] = array();
			}
			if (!in_array($strMessage, $arrMessages[$strClass]))
			{
				$arrMessages[$strClass][] = $strMessage;
			}
		}
		$this->Template->messages = $arrMessages;
		unset($_SESSION['avisota_subscription']);

		$this->Template->formId = 'tl_avisota_recipient';
		$this->Template->formAction = $this->jumpTo ? $this->generateFrontendUrl($this->getPageDetails($this->jumpTo)->row()) : $this->getIndexFreeRequest();
		$this->Template->hideForm = (count($arrMessages['confirmation'])>0 && count($arrMessages['error'])==0);
	}
}

?>