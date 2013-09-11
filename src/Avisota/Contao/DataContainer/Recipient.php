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
use Avisota\Contao\Entity\RecipientSubscription;
use Avisota\Contao\Event\ResolveSubscriptionNameEvent;
use Avisota\Contao\Subscription\SubscriptionManagerInterface;
use Contao\Doctrine\ORM\EntityHelper;
use DcGeneral\DC_General;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

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

		$input    = \Input::getInstance();
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
	 * @param array      $recipientData
	 * @param string     $label
	 * @param DC_General $dc
	 *
	 * @return string
	 */
	public function getLabel($recipientData, $label, DC_General $dc)
	{
		global $container;

		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $container['event-dispatcher'];

		$database = \Database::getInstance();

		$label = trim($recipientData['forename'] . ' ' . $recipientData['surname']);
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
		$queryBuilder  = $entityManager->createQueryBuilder();
		$subscriptions = $queryBuilder
			->select('s')
			->from('Avisota\Contao:RecipientSubscription', 's')
			->where('s.recipient=?1')
			->setParameter(1, $recipientData['id'])
			->getQuery()
			->getResult();

		if ($subscriptions) {
			/** @var RecipientSubscription $subscription */
			foreach ($subscriptions as $subscription) {
				$event = new ResolveSubscriptionNameEvent($subscription);
				$eventDispatcher->dispatch(ResolveSubscriptionNameEvent::NAME, $event);

				$label .= '<li>';
				$label .= $this->generateImage(
					sprintf(
						'system/themes/%s/images/%s.gif',
						$this->getTheme(),
						$subscription->getConfirmed() ? 'visible' : 'invisible'
					),
					''
				);
				$label .= '&nbsp;';
				$label .= $event->getSubscriptionName();
				$label .= '</li>';
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

		$input    = \Input::getInstance();
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
	 * @param \DataContainer $dc
	 */
	public function ondelete_callback($dc)
	{
		$input = \Input::getInstance();

		$options = SubscriptionManagerInterface::OPT_UNSUBSCRIBE_GLOBAL;
		if ($input->get('blacklist') == 'false') {
			$options |= SubscriptionManagerInterface::OPT_NO_BLACKLIST;
		}

		try {
			$subscriptionManager = $GLOBALS['container']['avisota.subscription'];
			$recipient           = $subscriptionManager->resolveRecipient(
				'Avisota\Contao:Recipient',
				$dc
					->getEnvironment()
					->getCurrentModel()
					->getProperty('email')
			);
			$subscriptionManager->unsubscribe(
				$recipient,
				null,
				$options
			);
		}
		catch (\Exception $exception) {
			global $container;
			/** @var LoggerInterface $logger */
			$logger = $container['avisota.logger'];
			$logger->error($exception->getMessage(), array('trace' => $exception->getTraceAsString()));
		}
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

		$input    = \Input::getInstance();
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
