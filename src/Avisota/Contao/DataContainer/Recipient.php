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
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\DataContainer;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\RecipientBlacklist;
use Avisota\Contao\Subscription\SubscriptionManagerInterface;
use Contao\Doctrine\ORM\EntityHelper;
use DcGeneral\DC_General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;

class Recipient extends \Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * @param \DataContainer $dc
	 */
	public function filterByMailingLists($dc = null)
	{
		if (TL_MODE == 'FE') {
			return;
		}

		$input = \Input::getInstance();
		$database = \Database::getInstance();

		/*
		$id = $input->get('showlist');
		if ($id) {
			$list = $database
				->prepare("SELECT * FROM orm_avisota_mailing_list WHERE id=?")
				->execute($id);
			if ($list->next()) {
				$GLOBALS['TL_DCA']['orm_avisota_recipient']['list']['sorting']['filter'][] = array(
					'id IN (SELECT recipient FROM orm_avisota_mailing_list WHERE list=?)',
					$id
				);

				$this->loadLanguageFile('avisota_dca');
				$_SESSION['TL_INFO'][] = sprintf(
					$GLOBALS['TL_LANG']['avisota_dca']['filteredByMailingList'],
					$list->title,
					preg_replace('#[&\?](avisota_)?showlist=\d+#', '', $this->Environment->request)
				);
			}
		}
		*/
	}

	/**
	 * @param array $recipientData
	 * @param string $label
	 * @param \DataContainer $dc
	 *
	 * @return string
	 */
	public function getLabel($recipientData, $label, $dc)
	{
		$database = \Database::getInstance();

		$label = trim($recipientData['firstname'] . ' ' . $recipientData['lastname']);
		if (strlen($label)) {
			$label .= ' &lt;' . $recipientData['email'] . '&gt;';
		}
		else {
			$label = $recipientData['email'];
		}

		$label .= ' <span style="color:#b3b3b3; padding-left:3px;">(';
		$label .= sprintf(
			$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedOn'][2],
			$recipientData['addedOn']->format($GLOBALS['TL_CONFIG']['datimFormat'])
		);
		if ($recipientData['addedBy'] > 0) {
			$user = $database
				->prepare("SELECT * FROM tl_user WHERE id=?")
				->execute($recipientData['addedBy']);
			$label .= sprintf(
				$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedBy'][2],
				$user->next() ? $user->name : $GLOBALS['TL_LANG']['orm_avisota_recipient']['addedBy'][3]
			);
		}
		$label .= ')</span>';

		$label .= '<ul style="margin-top: 3px;">';

		$entityManager = EntityHelper::getEntityManager();
		$queryBuilder = $entityManager->createQueryBuilder();
		$subscriptions = $queryBuilder
			->select('s')
			->from('Avisota\Contao:RecipientSubscription', 's')
			->where('s.recipient=?1')
			->setParameter(1, $dc->id)
			->getQuery()
			->getResult();

		if ($subscriptions) {
			/** @var MailingList $subscription */
			foreach ($subscriptions as $subscription) {
				/*
				$label .= '<li>';
				$label .= '<a href="javascript:void(0);" onclick="if ($(this).getProperty(\'data-confirmed\') || confirm(' . specialchars(
					json_encode($GLOBALS['TL_LANG']['orm_avisota_recipient']['confirmManualActivation'])
				) . ')) Avisota.toggleConfirmation(this);" data-recipient="' . $recipientData['id'] . '" data-list="' . $subscription->getId() . '" data-confirmed="' . ($list->confirmed
					? '1' : '') . '">';
				$label .= $this->generateImage(
					sprintf(
						'system/themes/%s/images/%s.gif',
						$this->getTheme(),
						$list->confirmed ? 'visible' : 'invisible'
					),
					''
				);
				$label .= '</a> ';
				$label .= $list->title;
				if ($list->confirmationSent || $list->reminderSent) {
					$label .= ' <span style="color:#b3b3b3; padding-left:3px;">(';
					if ($list->reminderCount > 1) {
						$label .= sprintf(
							$GLOBALS['TL_LANG']['orm_avisota_recipient']['remindersSent'],
							$list->reminderCount,
							$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'])
						);
					}
					else if ($list->reminderSent > 0) {
						$label .= sprintf(
							$GLOBALS['TL_LANG']['orm_avisota_recipient']['reminderSent'],
							$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'])
						);
					}
					else if ($list->confirmationSent > 0) {
						$label .= sprintf(
							$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirmationSent'],
							$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'])
						);
					}
					$label .= ')</span>';
				}
				$label .= '</li>';
				*/
			}
		}

		$label .= '</ul>';

		return $label;
	}

	/**
	 * @param \DataContainer $dc
	 */
	public function onload_callback($dc)
	{
		if (TL_MODE == 'FE') {
			return;
		}

		$input = \Input::getInstance();
		$database = \Database::getInstance();

		if ($input->get('act') == 'toggleConfirmation') {
			$recipientId = $input->get('recipient');
			$listId      = $input->get('list');

			$database
				->prepare("UPDATE orm_avisota_mailing_list SET confirmed=? WHERE recipient=? AND list=?")
				->execute($input->get('confirmed') ? '1' : '', $recipientId, $listId);

			header('Content-Type: application/javascript');
			echo json_encode(
				array(
					'confirmed' => $input->get('confirmed') ? true : false
				)
			);
			exit;
		}
	}

	/**
	 * @param DC_General $dc
	 */
	public function onsubmit_callback($dc)
	{
		if (isset($_SESSION['avisotaSubscriptionAction']) && isset($_SESSION['avisotaMailingLists'])) {
			$opt = SubscriptionManagerInterface::OPT_IGNORE_BLACKLIST;

			switch ($_SESSION['avisotaSubscriptionAction']) {
				case 'activateSubscription':
					$opt |= SubscriptionManagerInterface::OPT_ACTIVATE;
					break;
				case 'doNothink':
					$opt |= SubscriptionManagerInterface::OPT_NO_CONFIRMATION;
					break;
			}

			$subscriptionManager = $GLOBALS['container']['avisota.subscription'];
			$recipient = $subscriptionManager->resolveRecipient(
				'Avisota\Contao:Recipient',
				$dc->getEnvironment()->getCurrentModel()->getProperty('email')
			);
			$subscriptionManager->subscribe(
				$recipient,
				$_SESSION['avisotaMailingLists'],
				$opt
			);
			
			if ($subscriptions && $_SESSION['avisotaSubscriptionAction'] == 'sendOptIn')
			{
				// TODO send OptInMail
			}

			unset ($_SESSION['avisotaMailingLists'], $_SESSION['avisotaSubscriptionAction']);
		}
	}

	/**
	 * @param \DataContainer $dc
	 */
	public function ondelete_callback($dc)
	{
		$input = \Input::getInstance();

		$options = SubscriptionManagerInterface::OPT_UNSUBSCRIBE_GLOBAL;
		if ($input->get('blacklist') == 'false') {
			$options |= SubscriptionManagerInterface::OPT_NO_BLACKLIST;
		}

			$subscriptionManager = $GLOBALS['container']['avisota.subscription'];
			$recipient = $subscriptionManager->resolveRecipient(
				'Avisota\Contao:Recipient',
				$dc->getEnvironment()->getCurrentModel()->getProperty('email')
			);
		$subscriptionManager->unsubscribe(
			$recipient,
			null,
			$options
		);
	}


	/**
	 * Make email lowercase.
	 *
	 * @param string $email
	 *
	 * @return string
	 */
	public function saveEmail($email)
	{
		return strtolower($email);
	}

	/**
	 * @param array          $lists
	 * @param \DataContainer $dc
	 *
	 * @return array
	 * @throws Exception
	 */
	public function validateBlacklist($lists, $dc)
	{
		// do not check in frontend mode
		if (TL_MODE == 'FE') {
			return $lists;
		}
		$subscriptionManager = $GLOBALS['container']['avisota.subscription'];
		$input = \Input::getInstance();
		$email = $dc->getCurrentModel()->getProperty('email');
		$lists = deserialize($lists, true);

		// Check for blacklists. If the recipient is new, this test will throw an
		// exception, because the recipient was not written to the db at this point.
		try {
			$blacklists = $subscriptionManager->isBlacklisted($email, $lists);
		}
		catch (\RuntimeException $e)
		{
			return $lists;
		}

		if ($blacklists) {
			$k = array_map(
				function ($blacklist) {
					/** @var RecipientBlacklist $blacklist */
					return $blacklist->getList();
				},
				$blacklists
			);
			$k = 'AVISOTA_BLACKLIST_WARNING_' . md5(implode(',', $k));

			if (!(isset($_SESSION[$k]) && time() - $_SESSION[$k] < 60)) {
				$_SESSION[$k] = time();

				$entityManager = EntityHelper::getEntityManager();
				$queryBuilder = $entityManager->createQueryBuilder();
				$queryBuilder
					->select('m')
					->from('Avisota\Contao:MailingList', 'm');
				foreach ($blacklists as $index => $blacklist) {
					if ($index) {
						$queryBuilder->orWhere('id=?' . $index);
					}
					else {
						$queryBuilder->where('id=?' . $index);
					}
					$queryBuilder->setParameter($index, str_replace('mailing_list:', '', $blacklist->getList()));
				}
				$query = $queryBuilder->getQuery();
				$mailingLists = $query->getResult();

				$titles = array_map(
					function ($mailingList) {
						/** @var MailingList $mailingList */
						return $mailingList->getTitle();
					},
					$mailingLists
				);

				throw new Exception(
					sprintf(
						$GLOBALS['TL_LANG']['orm_avisota_recipient'][count($blacklists) > 1 ? 'blacklists'
							: 'blacklist'],
						implode(', ', $titles)
					)
				);
			}
		}
		return $lists;
	}

	/**
	 * @param mixed          $value
	 * @param \DataContainer $dc
	 * @param bool           $confirmed
	 *
	 * @return array
	 */
	public function loadMailingLists($value, $dc, $confirmed = null)
	{
		if (TL_MODE == 'FE') {
			return;
		}

		$arrSubscritions = array();
		$entityManager = EntityHelper::getEntityManager();
		$queryBuilder = $entityManager->createQueryBuilder();
		$mailingListIds = $queryBuilder
			->select('l.id')
			->from('Avisota\Contao:MailingList', 'l')
			->innerJoin(
				'Avisota\Contao:RecipientSubscription',
				's',
				Join::WITH,
				$queryBuilder->expr()->eq($queryBuilder->expr()->concat(':mailingListPrefix', 'l.id'), 's.list')
			)
			->where('s.recipient=:recipientId')
			->setParameter(':mailingListPrefix', 'mailing_list:')
			->setParameter(':recipientId', $dc->id)
			->getQuery()
			->getResult();
		foreach ($mailingListIds as $list)
		{
			$arrSubscritions[] = $list['id'];
		}

		return $arrSubscritions;
		/*
		$database = \Database::getInstance();

		$sql = 'SELECT * FROM orm_avisota_mailing_list WHERE recipient=?';
		$args = array($dc->id);

		if ($confirmed !== null) {
			$sql .= ' AND confirmed=?';
			$args[] = $confirmed ? '1' : '';
		}

		return $database
			->prepare($sql)
			->execute($args)
			->fetchEach('list');
		*/
	}

	/**
	 * @param array $value
	 *
	 * @return null
	 */
	public function saveMailingLists($value, $dc)
	{
		if (TL_MODE == 'FE') {
			return $value;
		}
		//get existing subscriptions
		$arrLists = $this->loadMailingLists($value, $dc);
		
		//check for subscriptions for removal
		$arrRemove = array_diff($arrLists, $value);
		if ($arrRemove)
		{

			//remove unchecked subscriptions
			$subscriptionManager = $GLOBALS['container']['avisota.subscription'];
			$recipient = $subscriptionManager->resolveRecipient(
				'Avisota\Contao:Recipient',
				$dc->getCurrentModel()->getProperty('email')
			);

			$subscriptions       = $subscriptionManager->unsubscribe(
				$recipient,
				$arrRemove
			);
		}
		
		//save new subscriptions for submit callback
		$_SESSION['avisotaMailingLists'] = $value;
		return null;
	}

	/**
	 * @param array $value
	 *
	 * @return null
	 */
	public function saveSubscriptionAction($value)
	{
		if (TL_MODE == 'FE') {
			return null;
		}

		$_SESSION['avisotaSubscriptionAction'] = $value;
		return null;
	}

	/**
	 * Check permissions to edit table orm_avisota_recipient
	 */
	public function checkPermission()
	{
		if (TL_MODE == 'FE') {
			return;
		}

		if ($this->User->isAdmin) {
			return;
		}

		// Set root IDs
		if (!is_array($this->User->avisota_recipient_lists) || count($this->User->avisota_recipient_lists) < 1) {
			$root = array(0);
		}
		else {
			$root = $this->User->avisota_recipient_lists;
		}

		$input = \Input::getInstance();
		$database = \Database::getInstance();

		$id = strlen($input->get('id')) ? $input->get('id') : CURRENT_ID;


		// Check permissions to add recipients
		if (!$this->User->hasAccess('create', 'avisota_recipient_permissions')) {
			$GLOBALS['TL_DCA']['orm_avisota_recipient']['config']['closed'] = true;
			unset($GLOBALS['TL_DCA']['orm_avisota_recipient']['list']['global_operations']['migrate']);
			unset($GLOBALS['TL_DCA']['orm_avisota_recipient']['list']['global_operations']['import']);
		}

		// Check permission to delete recipients
		if (!$this->User->hasAccess('delete', 'avisota_recipient_permissions')) {
			unset($GLOBALS['TL_DCA']['orm_avisota_recipient']['list']['global_operations']['remove']);

			// remove edit header class, if only delete without blacklist is allowed
			if ($this->User->hasAccess('delete_no_blacklist', 'avisota_recipient_permissions')) {
				$GLOBALS['TL_DCA']['orm_avisota_recipient']['list']['operations']['delete_no_blacklist']['attributes'] = str_replace(
					'class="edit-header"',
					'',
					$GLOBALS['TL_DCA']['orm_avisota_recipient']['list']['operations']['delete_no_blacklist']['attributes']
				);
			}
			else {
				unset($GLOBALS['TL_DCA']['orm_avisota_recipient']['list']['operations']['delete_no_blacklist']);
			}
		}

		// remove tools if there are no tools
		$tools = 0;
		foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['list']['global_operations'] as $globalOperation) {
			if (strpos($globalOperation['class'], 'recipient_tool') !== false) {
				$tools++;
			}
		}
		if ($tools <= 1) {
			unset($GLOBALS['TL_DCA']['orm_avisota_recipient']['list']['global_operations']['tools']);
		}

		// Check current action
		switch ($input->get('act')) {
			case 'create':
				if (!strlen($input->get('pid')) || !in_array(
					$input->get('pid'),
					$root
				) || !$this->User->hasAccess('create', 'avisota_recipient_permissions')
				) {
					$this->log(
						'Not enough permissions to create newsletters recipients in list ID "' . $input->get(
							'pid'
						) . '"',
						'orm_avisota_recipient checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'edit':
			case 'show':
			case 'copy':
			case 'paste':
			case 'delete':
			case 'toggle':
				$recipient = $database
					->prepare("SELECT pid FROM orm_avisota_recipient WHERE id=?")
					->limit(1)
					->execute($id);

				if ($recipient->numRows < 1) {
					$this->log(
						'Invalid newsletter recipient ID "' . $id . '"',
						'orm_avisota_recipient checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}

				switch ($input->get('act')) {
					case 'edit':
					case 'toggle':
						$hasAccess = (count(preg_grep('/^orm_avisota_recipient::/', $this->User->alexf)) > 0);
						break;

					case 'show':
						$hasAccess = true;
						break;

					case 'copy':
						$hasAccess = ($this->User->hasAccess('create', 'avisota_recipient_permissions'));
						break;

					case 'delete':
						$hasAccess = ($this->User->hasAccess(
							$input->get('blacklist') == 'false' ? 'delete_no_blacklist' : 'delete',
							'avisota_recipient_permissions'
						));
						break;
				}
				if (!in_array($recipient->pid, $root) || !$hasAccess) {
					$this->log(
						'Not enough permissions to ' . $input->get(
							'act'
						) . ' recipient ID "' . $id . '" of recipient list ID "' . $recipient->pid . '"',
						'orm_avisota_recipient checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'select':
			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				switch ($input->get('act')) {
					case 'select':
						$hasAccess = true;
						break;

					case 'editAll':
					case 'overrideAll':
						$hasAccess = (count(preg_grep('/^orm_avisota_recipient::/', $this->User->alexf)) > 0);
						break;

					case 'deleteAll':
						$hasAccess = ($this->User->hasAccess(
							$input->get('blacklist') == 'false' ? 'delete_no_blacklist' : 'delete',
							'avisota_recipient_permissions'
						));
						break;
				}
				if (!in_array($id, $root) || !$hasAccess) {
					$this->log(
						'Not enough permissions to access recipient list ID "' . $id . '"',
						'orm_avisota_recipient checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}

				$recipient = $database
					->prepare("SELECT id FROM orm_avisota_recipient WHERE pid=?")
					->execute($id);

				if ($recipient->numRows < 1) {
					$this->log(
						'Invalid newsletter recipient ID "' . $id . '"',
						'orm_avisota_recipient checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}

				$session                   = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect(
					$session['CURRENT']['IDS'],
					$recipient->fetchEach('id')
				);
				$this->Session->setData($session);
				break;

			default:
				if (strlen($input->get('act'))) {
					$this->log(
						'Invalid command "' . $input->get('act') . '"',
						'orm_avisota_recipient checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root)) {
					$this->log(
						'Not enough permissions to access newsletter recipient ID "' . $id . '"',
						'orm_avisota_recipient checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Return the edit header button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function editRecipient($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^orm_avisota_recipient::/', $this->User->alexf)) > 0)
			? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
				$title
			) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : '';
	}


	/**
	 * Return the copy channel button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function copyRecipient($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'avisota_recipient_permissions'))
			? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
				$title
			) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> '
			: $this->generateImage(
				preg_replace('/\.gif$/i', '_.gif', $icon)
			) . ' ';
	}


	/**
	 * Return the delete channel button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function deleteRecipient($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'avisota_recipient_permissions'))
			? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
				$title
			) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> '
			: $this->generateImage(
				preg_replace('/\.gif$/i', '_.gif', $icon)
			) . ' ';
	}


	/**
	 * Return the delete channel button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function deleteRecipientNoBlacklist($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete_no_blacklist', 'avisota_recipient_permissions'))
			? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
				$title
			) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> '
			: $this->generateImage(
				preg_replace('/\.gif$/i', '_.gif', $icon)
			) . ' ';
	}


	/**
	 * Return the notify button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function notify($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="contao/main.php?do=avisota_recipients&amp;table=orm_avisota_recipient_notify&amp;act=edit&amp;id=' . $row['id'] . '" title="' . specialchars(
			$title
		) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}
}
