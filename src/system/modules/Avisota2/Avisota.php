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
 *
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
	/**
	 * @var AvisotaBase
	 */
	protected $Base;

	public function __construct()
	{
		parent::__construct();
		$this->import('DomainLink');
		$this->import('BackendUser', 'User');
		$this->import('AvisotaBase', 'Base');
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

		$objNewsletter = AvisotaNewsletter::load($intId);

		if (!$objNewsletter) {
			$this->redirect('contao/main.php?do=avisota_newsletter');
		}

		$objCategory = AvisotaNewsletterCategory::load($objNewsletter->pid);

		if (!$objCategory) {
			$this->redirect('contao/main.php?do=avisota_newsletter');
		}

		if (!$this->User->isAdmin) {
			if (!is_array($this->User->avisota_newsletter_categories) || count(
				$this->User->avisota_newsletter_categories
			) < 1
			) {
				$root = array(0);
			}
			else {
				$root = $this->User->avisota_newsletter_categories;
			}

			if (!in_array($objCategory->id, $root)) {
				$this->log(
					'Not enough permissions to send newsletter from category ID ' . $objCategory->id,
					'Avisota::send()',
					TL_ERROR
				);
				$this->redirect('contao/main.php?act=error');
			}
		}

		AvisotaStatic::pushCategory($objCategory);
		AvisotaStatic::pushNewsletter($objNewsletter);

		$objTemplate = new BackendTemplate('be_avisota_send');
		$objTemplate->import('BackendUser', 'User');

		// allow backend sending
		$objTemplate->beSend = $this->Base->allowBackendSending();

		// Store the current referer
		$session = $this->Session->get('referer');
		if ($session['current'] != $this->Environment->requestUri) {
			$session['tl_avisota_newsletter'] = $this->Environment->requestUri;
			$session['last']                  = $session['current'];
			$session['current']               = $this->Environment->requestUri;
			$this->Session->set('referer', $session);
		}

		$objTemplate->users = $this->getAllowedUsers();

		return $objTemplate->parse();
	}


	protected function getAllowedUsers()
	{
		$arrUser = array();
		$objUser = $this->Database->execute("SELECT * FROM tl_user ORDER BY name,email");
		while ($objUser->next()) {
			if (!$objUser->admin && !$this->User->isAdmin) {
				$arrGroups = array_intersect($this->User->groups, deserialize($objUser->groups, true));
				if (!count($arrGroups)) {
					continue;
				}
			}
			$arrUser[$objUser->id] = $objUser->row();
		}
		return $arrUser;
	}
}
