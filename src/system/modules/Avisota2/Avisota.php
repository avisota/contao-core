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
 * Class Avisota
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class Avisota extends Backend
{
	public function __construct()
	{
		parent::__construct();
		$this->import('DomainLink');
		$this->import('BackendUser', 'User');
		$this->import('AvisotaBase', 'Base');
		$this->import('AvisotaContent', 'Content');
		$this->import('AvisotaStatic', 'Static');
		$this->loadLanguageFile('tl_avisota_newsletter');
	}


	/**
	 * Show preview and send the Newsletter.
	 *
	 * @return string
	 */
	public function send()
	{
		$intId = $this->Input->get('id');

		// get the newsletter
		$objNewsletter = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter
				WHERE
					id=?")
			->execute($intId);

		if (!$objNewsletter->next())
		{
			$this->redirect('contao/main.php?do=avisota_newsletter');
		}

		// get the newsletter category
		$objCategory = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter_category
				WHERE
					id=?")
			->execute($objNewsletter->pid);

		if (!$objCategory->next())
		{
			$this->redirect('contao/main.php?do=avisota_newsletter');
		}

		if (!$this->User->isAdmin)
		{
			// Set root IDs
			if (!is_array($this->User->avisota_newsletter_categories) || count($this->User->avisota_newsletter_categories) < 1)
			{
				$root = array(0);
			}
			else
			{
				$root = $this->User->avisota_newsletter_categories;
			}

			if (!in_array($objCategory->id, $root))
			{
				$this->redirect('contao/main.php?act=error');
			}
		}

		$this->Static->setCategory($objCategory);
		$this->Static->setNewsletter($objNewsletter);

		$objTemplate = new BackendTemplate('be_avisota_send');
		$objTemplate->import('BackendUser', 'User');

		// add category data to template
		$objTemplate->setData($objCategory->row());

		// add newsletter data to template
		$objTemplate->setData($objNewsletter->row());

		// add sender
		$strFrom = '';
		if ($objCategory->sender)
		{
			$strFrom = $objCategory->sender;
		}
		else
		{
			$strFrom = $GLOBALS['TL_CONFIG']['adminEmail'];
		}
		if ($objCategory->senderName)
		{
			$strFrom = sprintf('%s &lt;%s&gt;', $objCategory->senderName, $strFrom);
		}
		$objTemplate->from = $strFrom;

		// add recipients
		$arrRecipients = unserialize($objNewsletter->recipients);
		$arrLists = array();
		$arrMgroups = array();
		foreach ($arrRecipients as $strRecipient)
		{
			if (preg_match('#^(list|mgroup)\-(\d+)$#', $strRecipient, $arrMatch))
			{
				switch ($arrMatch[1])
				{
				case 'list':
					$intIdTmp = $arrMatch[2];
					$objList = $this->Database->prepare("
							SELECT
								*
							FROM
								tl_avisota_mailing_list
							WHERE
								id=?")
						->execute($intIdTmp);
					$arrLists[$intIdTmp] = $objList->title;
					break;

				case 'mgroup':
					$intIdTmp = $arrMatch[2];
					$objMgroup = $this->Database->prepare("
							SELECT
								*
							FROM
								tl_member_group
							WHERE
								id=?")
						->execute($intIdTmp);
					$arrMgroups[$intIdTmp] = $objMgroup->name;
					break;
				}
			}
		}
		$objTemplate->recipients_list = $arrLists;
		$objTemplate->recipients_mgroup = $arrMgroups;

		// add token
		$objTemplate->token = $strToken;

		// allow backend sending
		$objTemplate->beSend = $this->Base->allowBackendSending();

		// Store the current referer
		$session = $this->Session->get('referer');
		if ($session['current'] != $this->Environment->requestUri)
		{
			$session['tl_avisota_newsletter'] = $this->Environment->requestUri;
			$session['last'] = $session['current'];
			$session['current'] = $this->Environment->requestUri;
			$this->Session->set('referer', $session);
		}

		$objTemplate->users = $this->getAllowedUsers();

		return $objTemplate->parse();
	}


	protected function getAllowedUsers()
	{
		$arrUser = array();
		$objUser = $this->Database->execute("SELECT * FROM tl_user ORDER BY name,email");
		while ($objUser->next())
		{
			if (!$objUser->admin && !$this->User->isAdmin)
			{
				$arrGroups = array_intersect($this->User->groups, deserialize($objUser->groups, true));
				if (!count($arrGroups))
				{
					continue;
				}
			}
			$arrUser[$objUser->id] = $objUser->row();
		}
		return $arrUser;
	}
}
