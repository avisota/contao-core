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


class orm_avisota_recipient extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function getLabel($recipientData, $label, DataContainer $dc)
	{
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
			$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $recipientData['addedOn'])
		);
		if ($recipientData['addedBy'] > 0) {
			$user = $this->Database
				->prepare("SELECT * FROM tl_user WHERE id=?")
				->execute($recipientData['addedBy']);
			$label .= sprintf(
				$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedBy'][2],
				$user->next() ? $user->name : $GLOBALS['TL_LANG']['orm_avisota_recipient']['addedBy'][3]
			);
		}
		$label .= ')</span>';

		$label .= '<ul style="margin-top: 3px;">';

		$list = $this->Database
			->prepare(
			"SELECT ml.*, rtml.confirmed, rtml.confirmationSent, rtml.reminderSent, rtml.reminderCount FROM orm_avisota_mailing_list ml INNER JOIN orm_avisota_recipient_to_mailing_list rtml ON ml.id=rtml.list WHERE rtml.recipient=? ORDER BY ml.title"
		)
			->execute($recipientData['id']);
		while ($list->next()) {
			$label .= '<li>';
			$label .= '<a href="javascript:void(0);" onclick="if ($(this).getProperty(\'data-confirmed\') || confirm(' . specialchars(
				json_encode($GLOBALS['TL_LANG']['orm_avisota_recipient']['confirmManualActivation'])
			) . ')) Avisota.toggleConfirmation(this);" data-recipient="' . $recipientData['id'] . '" data-list="' . $list->id . '" data-confirmed="' . ($list->confirmed
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
		}

		$label .= '</ul>';

		return $label;
	}

	public function onload_callback($dc)
	{
		if (TL_MODE == 'FE') {
			return;
		}

		if ($this->Input->get('act') == 'toggleConfirmation') {
			$recipientId = $this->Input->get('recipient');
			$listId      = $this->Input->get('list');

			$this->Database
				->prepare("UPDATE orm_avisota_recipient_to_mailing_list SET confirmed=? WHERE recipient=? AND list=?")
				->execute($this->Input->get('confirmed') ? '1' : '', $recipientId, $listId);

			header('Content-Type: application/javascript');
			echo json_encode(
				array(
					'confirmed' => $this->Input->get('confirmed') ? true : false
				)
			);
			exit;
		}
	}

	public function onsubmit_callback($dc)
	{
		$recipient = AvisotaIntegratedRecipient::byEmail($dc->activeRecord->email);
		$recipient->subscribe($_SESSION['avisotaMailingLists'], true);

		switch ($_SESSION['avisotaSubscriptionAction']) {
			case 'sendConfirmation':
				$recipient->sendSubscriptionConfirmation($_SESSION['avisotaMailingLists']);
				break;
			case 'activateSubscription':
				$recipient->confirmSubscription($_SESSION['avisotaMailingLists']);
				break;
		}

		unset ($_SESSION['avisotaMailingLists'], $_SESSION['avisotaSubscriptionAction']);
	}


	public function ondelete_callback($dc)
	{
		if ($this->Input->get('blacklist') !== 'false') {
			$time = time();

			$lists = $this->loadMailingLists('', $dc, true);

			// build insert values
			$values = array();
			$args   = array();
			foreach ($lists as $listId) {
				$values[] = '(?, ?, ?)';
				$args[]   = $time;
				$args[]   = $listId;
				$args[]   = md5(strtolower($dc->activeRecord->email));
			}

			// on duplicate key update tstamp
			$args[] = $time;

			// execute query
			if (count($values)) {
				$this->Database
					->prepare(
					"INSERT INTO orm_avisota_recipient_blacklist (tstamp, pid, email)
							   VALUES " . implode(',', $values) . "
							   ON DUPLICATE KEY UPDATE tstamp=?"
				)
					->execute($args);
			}
		}
	}


	/**
	 * Make email lowercase.
	 *
	 * @param $email
	 *
	 * @return string
	 */
	public function saveEmail($email)
	{
		return strtolower($email);
	}


	public function validateBlacklist($listIds, DataContainer $dc)
	{
		// do not check in frontend mode
		if (TL_MODE == 'FE') {
			return $listIds;
		}

		$email = $this->Input->post('email');
		$listIds = deserialize($listIds, true);
		$listIds = array_map('intval', $listIds);
		$listIds = array_filter($listIds);
		if (!count($listIds)) {
			return $listIds;
		}

		$blacklisted = AvisotaIntegratedRecipient::checkBlacklisted($email, $listIds);

		if ($blacklisted) {
			$blacklist = $this->Database
				->execute(
				"SELECT * FROM orm_avisota_mailing_list
				           WHERE id IN (" . implode(',', $blacklisted) . ")
				           ORDER BY title"
			);
			if ($blacklist->numRows) {
				$k = 'AVISOTA_BLACKLIST_WARNING_' . md5(implode(',', $blacklist->fetchEach('id')));
				if (!(isset($_SESSION[$k]) && time() - $_SESSION[$k] < 60)) {
					$_SESSION[$k] = time();
					throw new Exception(
						sprintf(
							$GLOBALS['TL_LANG']['orm_avisota_recipient'][$blacklist->numRows > 1 ? 'blacklists'
								: 'blacklist'],
							implode(', ', $blacklist->fetchEach('title'))
						)
					);
				}
			}
		}
		return $listIds;
	}

	public function loadMailingLists($value, DataContainer $dc, $confirmed = null)
	{
		if (TL_MODE == 'FE') {
			return;
		}

		return $this->Database
			->prepare(
			"SELECT * FROM orm_avisota_recipient_to_mailing_list WHERE recipient=?"
				. ($confirmed !== null ? ' AND confirmed=?' : '')
		)
			->execute($dc->id, $confirmed ? '1' : '')
			->fetchEach('list');
	}

	public function saveMailingLists($value)
	{
		if (TL_MODE == 'FE') {
			return $value;
		}

		$_SESSION['avisotaMailingLists'] = $value;
		return null;
	}

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

		$id = strlen($this->Input->get('id')) ? $this->Input->get('id') : CURRENT_ID;


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
		switch ($this->Input->get('act')) {
			case 'create':
				if (!strlen($this->Input->get('pid')) || !in_array(
					$this->Input->get('pid'),
					$root
				) || !$this->User->hasAccess('create', 'avisota_recipient_permissions')
				) {
					$this->log(
						'Not enough permissions to create newsletters recipients in list ID "' . $this->Input->get(
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
				$recipient = $this->Database
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

				switch ($this->Input->get('act')) {
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
							$this->Input->get('blacklist') == 'false' ? 'delete_no_blacklist' : 'delete',
							'avisota_recipient_permissions'
						));
						break;
				}
				if (!in_array($recipient->pid, $root) || !$hasAccess) {
					$this->log(
						'Not enough permissions to ' . $this->Input->get(
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
				switch ($this->Input->get('act')) {
					case 'select':
						$hasAccess = true;
						break;

					case 'editAll':
					case 'overrideAll':
						$hasAccess = (count(preg_grep('/^orm_avisota_recipient::/', $this->User->alexf)) > 0);
						break;

					case 'deleteAll':
						$hasAccess = ($this->User->hasAccess(
							$this->Input->get('blacklist') == 'false' ? 'delete_no_blacklist' : 'delete',
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

				$recipient = $this->Database
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
				if (strlen($this->Input->get('act'))) {
					$this->log(
						'Invalid command "' . $this->Input->get('act') . '"',
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
