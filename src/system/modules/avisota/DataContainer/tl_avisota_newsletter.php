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

class orm_avisota_message extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function updatePalette()
	{
		if ($this->Input->get('act') == 'edit') {
			$category = $this->Database
				->prepare(
				'SELECT c.*
						   FROM orm_avisota_message_category c
						   INNER JOIN orm_avisota_message n
						   ON c.id=n.pid
						   WHERE n.id=?'
			)
				->execute($this->Input->get('id'));

			if ($category->next()) {
				switch ($category->recipientsMode) {
					case 'byNewsletterOrCategory':
						$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['recipient'][] = 'setRecipients';
						break;

					case 'byNewsletter':
						$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['recipient'][] = 'recipients';
						break;
				}

				switch ($category->themeMode) {
					case 'byNewsletterOrCategory':
						$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['theme'][] = 'setTheme';
						break;

					case 'byNewsletter':
						$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['theme'][] = 'theme';
						break;
				}

				switch ($category->transportMode) {
					case 'byNewsletterOrCategory':
						$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['transport'][] = 'setTransport';
						break;

					case 'byNewsletter':
						$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['transport'][] = 'transport';
						break;
				}
			}
		}
		else {
			$category = $this->Database
				->prepare(
				'SELECT c.*
						   FROM orm_avisota_message_category c
						   WHERE c.id=?'
			)
				->execute($this->Input->get('id'));

			if ($category->next()) {
				switch ($category->recipientsMode) {
					case 'byNewsletterOrCategory':
					case 'byCategory':
						$GLOBALS['TL_DCA']['orm_avisota_message']['list']['sorting']['headerFields'][] = 'recipients';
						break;
				}

				switch ($category->themeMode) {
					case 'byNewsletterOrCategory':
					case 'byCategory':
						$GLOBALS['TL_DCA']['orm_avisota_message']['list']['sorting']['headerFields'][] = 'theme';
						break;
				}

				switch ($category->transportMode) {
					case 'byNewsletterOrCategory':
					case 'byCategory':
						$GLOBALS['TL_DCA']['orm_avisota_message']['list']['sorting']['headerFields'][] = 'transport';
						break;
				}
			}
		}
	}

	/**
	 * Check permissions to edit table tl_newsletter_channel
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// Set root IDs
		if (!is_array($this->User->avisota_newsletter_categories) || count(
			$this->User->avisota_newsletter_categories
		) < 1
		) {
			$root = array(0);
		}
		else {
			$root = $this->User->avisota_newsletter_categories;
		}

		// Check permissions to add channels
		if (!$this->User->hasAccess('create', 'avisota_newsletter_permissions')) {
			$GLOBALS['TL_DCA']['orm_avisota_message']['config']['closed'] = true;
		}

		// Check current action
		switch ($this->Input->get('act')) {
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
			case 'copy':
			case 'paste':
			case 'delete':
			case 'show':
				$pid = -1;
				if ($this->Input->get('id')) {
					$newsletter = $this->Database
						->prepare("SELECT * FROM orm_avisota_message WHERE id=?")
						->execute($this->Input->get('id'));
					if ($newsletter->next()) {
						$pid = $newsletter->pid;
					}
				}
				if (!in_array($pid, $root) || ($this->Input->get('act') == 'delete' && !$this->User->hasAccess(
					'delete',
					'avisota_newsletter_permissions'
				))
				) {
					$this->log(
						'Not enough permissions to ' . $this->Input->get(
							'act'
						) . ' avisota newsletter ID "' . $this->Input->get('id') . '"',
						'orm_avisota_message checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess(
					'delete',
					'avisota_newsletter_permissions'
				)
				) {
					$session['CURRENT']['IDS'] = array();
				}
				else {
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act'))) {
					$this->log(
						'Not enough permissions to ' . $this->Input->get('act') . ' avisota newsletter',
						'orm_avisota_message checkPermission',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}

	/**
	 * Return the edit button
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
	public function editNewsletter($row, $href, $label, $title, $icon, $attributes)
	{
		return (!$row['sendOn'] && ($this->User->isAdmin || count(
			preg_grep('/^orm_avisota_message::/', $this->User->alexf)
		) > 0)) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
			$title
		) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : '';
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
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return (!$row['sendOn'] && ($this->User->isAdmin || count(
			preg_grep('/^orm_avisota_message::/', $this->User->alexf)
		) > 0)) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
			$title
		) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : '';
	}


	/**
	 * Return the copy button
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
	public function copyNewsletter($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'avisota_newsletter_permissions'))
			? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
				$title
			) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> '
			: $this->generateImage(
				preg_replace('/\.gif$/i', '_.gif', $icon)
			) . ' ';
	}


	/**
	 * Return the delete button
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
	public function deleteNewsletter($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'avisota_newsletter_permissions'))
			? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
				$title
			) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> '
			: $this->generateImage(
				preg_replace('/\.gif$/i', '_.gif', $icon)
			) . ' ';
	}


	/**
	 * Return the send button
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
	public function sendNewsletter($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->isAdmin && !$this->User->hasAccess('send', 'avisota_newsletter_permissions')) {
			$label = $GLOBALS['TL_LANG']['orm_avisota_message']['view_only'][0];
			$title = $GLOBALS['TL_LANG']['orm_avisota_message']['view_only'][1];
		}
		return '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars(
			$title
		) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}

	public function addHeader($add, $dc)
	{
		$key       = $GLOBALS['TL_LANG']['orm_avisota_message_category']['recipients'][0];
		$add[$key] = array();

		$category = AvisotaNewsletterCategory::load($dc->id);

		$fallback = $category->recipientsMode == 'byNewsletterOrCategory';

		$selectedRecipients = $category->getRecipients();

		$recipients = AvisotaBackend::getInstance()
			->getRecipients(true);

		foreach ($recipients as $group => $lists) {
			list($source, $group) = explode(':', $group, 2);
			foreach ($lists as $listKey => $list) {
				if (in_array($listKey, $selectedRecipients)) {
					$add[$key][] = sprintf(
						'<a href="contao/main.php?do=avisota_recipient_source&act=edit&id=%d">%s &raquo; %s</a>%s',
						$source,
						$group,
						$list,
						$fallback ? ' ' . $GLOBALS['TL_LANG']['orm_avisota_message']['fallback'] : ''
					);
				}
			}
		}

		$add[$key] = implode('<br>', $add[$key]);


		if ($category->themeMode == 'byNewsletterOrCategory') {
			$key = $GLOBALS['TL_LANG']['orm_avisota_message_category']['theme'][0];
			$add[$key] .= ' ' . $GLOBALS['TL_LANG']['orm_avisota_message']['fallback'];
		}


		if ($category->transportMode == 'byNewsletterOrCategory') {
			$key = $GLOBALS['TL_LANG']['orm_avisota_message_category']['transport'][0];
			$add[$key] .= ' ' . $GLOBALS['TL_LANG']['orm_avisota_message']['fallback'];
		}


		return $add;
	}

	/**
	 * Add the recipient row.
	 *
	 * @param array
	 */
	public function addNewsletter($newsletterData)
	{
		$icon = $newsletterData['sendOn'] ? 'visible' : 'invisible';

		$label = $newsletterData['subject'];

		if ($row['sendOn']) {
			$label .= ' <span style="color:#b3b3b3; padding-left:3px;">(' . sprintf(
				$GLOBALS['TL_LANG']['orm_avisota_recipient']['sended'],
				$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $row['sendOn'])
			) . ')</span>';
		}

		return sprintf(
			'<div class="list_icon" style="background-image:url(\'system/themes/%s/images/%s.gif\');">%s</div>',
			$this->getTheme(),
			$icon,
			$label
		);
	}

	public function addGroup($group, $mode, $field, $row, $dc)
	{
		if (!isset($GLOBALS['MAGIC_ADD_GROUP_INDEX'])) {
			$GLOBALS['MAGIC_ADD_GROUP_INDEX'] = 0;
		}
		else {
			$GLOBALS['MAGIC_ADD_GROUP_INDEX']++;
		}

		if ($row[$GLOBALS['MAGIC_ADD_GROUP_INDEX']]['sendOn'] > 0) {
			return $this->parseDate('F Y', $row[$GLOBALS['MAGIC_ADD_GROUP_INDEX']]['sendOn']);
		}
		return $GLOBALS['TL_LANG']['orm_avisota_message']['notSend'];
	}

	/**
	 * Autogenerate a news alias if it has not been set yet
	 *
	 * @param mixed $value
	 * @param \DataContainer $dc
	 *
	 * @return string
	 */
	public function generateAlias($value, $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($value)) {
			$autoAlias = true;
			$value  = standardize($dc->activeRecord->subject);
		}

		$aliasResultSet = $this->Database
			->prepare("SELECT id FROM orm_avisota_message WHERE alias=?")
			->execute($value);

		// Check whether the news alias exists
		if ($aliasResultSet->numRows > 1 && !$autoAlias) {
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $value));
		}

		// Add ID to alias
		if ($aliasResultSet->numRows && $autoAlias) {
			$value .= '-' . $dc->id;
		}

		return $value;
	}
}
