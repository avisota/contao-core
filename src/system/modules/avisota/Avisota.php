<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class Avisota
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
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
		$this->loadLanguageFile('orm_avisota_newsletter');
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
			$session['orm_avisota_newsletter'] = $this->Environment->requestUri;
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
