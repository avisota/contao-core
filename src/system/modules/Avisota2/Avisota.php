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
		$id = $this->Input->get('id');

		$newsletter = AvisotaNewsletter::load($id);

		if (!$newsletter) {
			$this->redirect('contao/main.php?do=avisota_newsletter');
		}

		$category = AvisotaNewsletterCategory::load($newsletter->pid);

		if (!$category) {
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

			if (!in_array($category->id, $root)) {
				$this->log(
					'Not enough permissions to send newsletter from category ID ' . $category->id,
					'Avisota::send()',
					TL_ERROR
				);
				$this->redirect('contao/main.php?act=error');
			}
		}

		AvisotaStatic::pushCategory($category);
		AvisotaStatic::pushNewsletter($newsletter);

		$template = new BackendTemplate('be_avisota_send');
		$template->import('BackendUser', 'User');

		// allow backend sending
		$template->beSend = $this->Base->allowBackendSending();

		// Store the current referer
		$session = $this->Session->get('referer');
		if ($session['current'] != $this->Environment->requestUri) {
			$session['tl_avisota_newsletter'] = $this->Environment->requestUri;
			$session['last']                  = $session['current'];
			$session['current']               = $this->Environment->requestUri;
			$this->Session->set('referer', $session);
		}

		$template->users = $this->getAllowedUsers();

		return $template->parse();
	}


	protected function getAllowedUsers()
	{
		$users = array();
		$user = $this->Database->execute("SELECT * FROM tl_user ORDER BY name,email");
		while ($user->next()) {
			if (!$user->admin && !$this->User->isAdmin) {
				$groups = array_intersect($this->User->groups, deserialize($user->groups, true));
				if (!count($groups)) {
					continue;
				}
			}
			$users[$user->id] = $user->row();
		}
		return $users;
	}
}
