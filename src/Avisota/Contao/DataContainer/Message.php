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

namespace Avisota\Contao\DataContainer;

use Avisota\Contao\Entity\MessageCategory;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\Common\Persistence\Mapping\MappingException;

class Message extends \Controller
{
	public function updatePalette()
	{
		$input    = \Input::getInstance();
		$database = \Database::getInstance();

		if ($input->get('do') == 'avisota_newsletter' &&
			$input->get('table') == 'orm_avisota_message' &&
			$database->tableExists('orm_avisota_message_category')
		) {
			try {
				$newsletterCategoryRepository = EntityHelper::getRepository('Avisota\Contao:MessageCategory');

				if ($input->get('act') == 'edit') {
					$newsletterRepository = EntityHelper::getRepository('Avisota\Contao:Message');
					/** @var \Avisota\Contao\Entity\Message $newsletter */
					$newsletter = $newsletterRepository->find($input->get('id'));
					/** @var MessageCategory $newsletterCategory */
					$newsletterCategory = $newsletterCategoryRepository->find($newsletter->getPid());

					switch ($newsletterCategory->getRecipientsMode()) {
						case 'byMessageOrCategory':
							$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['recipient'][] = 'setRecipients';
							break;

						case 'byMessage':
							$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['recipient'][] = 'recipients';
							break;
					}

					switch ($newsletterCategory->getThemeMode()) {
						case 'byMessageOrCategory':
							$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['theme'][] = 'setTheme';
							break;

						case 'byMessage':
							$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['theme'][] = 'theme';
							break;
					}

					switch ($newsletterCategory->getTransportMode()) {
						case 'byMessageOrCategory':
							$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['transport'][] = 'setTransport';
							break;

						case 'byMessage':
							$GLOBALS['TL_DCA']['orm_avisota_message']['metapalettes']['default']['transport'][] = 'transport';
							break;
					}
				}
				else {
					/** @var MessageCategory $newsletterCategory */
					$newsletterCategory = $newsletterCategoryRepository->find($input->get('id'));

					switch ($newsletterCategory->getRecipientsMode()) {
						case 'byMessageOrCategory':
						case 'byCategory':
							$GLOBALS['TL_DCA']['orm_avisota_message']['list']['sorting']['headerFields'][] = 'recipients';
							break;
					}

					switch ($newsletterCategory->getThemeMode()) {
						case 'byMessageOrCategory':
						case 'byCategory':
							$GLOBALS['TL_DCA']['orm_avisota_message']['list']['sorting']['headerFields'][] = 'theme';
							break;
					}

					switch ($newsletterCategory->getTransportMode()) {
						case 'byMessageOrCategory':
						case 'byCategory':
							$GLOBALS['TL_DCA']['orm_avisota_message']['list']['sorting']['headerFields'][] = 'transport';
							break;
					}
				}
			}
			catch (MappingException $e) {

			}
		}
	}

	/**
	 * Check permissions to edit table tl_newsletter_channel
	 */
	public function checkPermission()
	{
		$user = \BackendUser::getInstance();

		if ($user->isAdmin) {
			return;
		}

		$input    = \Input::getInstance();
		$database = \Database::getInstance();

		// Set root IDs
		if (!is_array($user->avisota_newsletter_categories) || count(
				$user->avisota_newsletter_categories
			) < 1
		) {
			$root = array(0);
		}
		else {
			$root = $user->avisota_newsletter_categories;
		}

		// Check permissions to add channels
		if (!$user->hasAccess('create', 'avisota_newsletter_permissions')) {
			$GLOBALS['TL_DCA']['orm_avisota_message']['config']['closed'] = true;
		}

		// Check current action
		switch ($input->get('act')) {
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
				if ($input->get('id')) {
					$newsletter = $database
						->prepare("SELECT * FROM orm_avisota_message WHERE id=?")
						->execute($input->get('id'));
					if ($newsletter->next()) {
						$pid = $newsletter->pid;
					}
				}
				if (!in_array($pid, $root) || ($input->get('act') == 'delete' && !$user->hasAccess(
							'delete',
							'avisota_newsletter_permissions'
						))
				) {
					$this->log(
						'Not enough permissions to ' . $input->get(
							'act'
						) . ' avisota newsletter ID "' . $input->get('id') . '"',
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
				if ($input->get('act') == 'deleteAll' && !$user->hasAccess(
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
				if (strlen($input->get('act'))) {
					$this->log(
						'Not enough permissions to ' . $input->get('act') . ' avisota newsletter',
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
	public function editMessage($row, $href, $label, $title, $icon, $attributes)
	{
		$user = \BackendUser::getInstance();

		return (!$row['sendOn'] && ($user->isAdmin || count(
					preg_grep('/^orm_avisota_message::/', $user->alexf)
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
		$user = \BackendUser::getInstance();

		return (!$row['sendOn'] && ($user->isAdmin || count(
					preg_grep('/^orm_avisota_message::/', $user->alexf)
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
	public function copyMessage($row, $href, $label, $title, $icon, $attributes)
	{
		$user = \BackendUser::getInstance();

		return ($user->isAdmin || $user->hasAccess('create', 'avisota_newsletter_permissions'))
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
	public function deleteMessage($row, $href, $label, $title, $icon, $attributes)
	{
		$user = \BackendUser::getInstance();

		return ($user->isAdmin || $user->hasAccess('delete', 'avisota_newsletter_permissions'))
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
	public function sendMessage($row, $href, $label, $title, $icon, $attributes)
	{
		$user = \BackendUser::getInstance();

		if (!$user->isAdmin && !$user->hasAccess('send', 'avisota_newsletter_permissions')) {
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

		$newsletterCategoryRepository = EntityHelper::getRepository('Avisota\Contao:MessageCategory');
		/** @var MessageCategory $newsletterCategory */
		$newsletterCategory = $newsletterCategoryRepository->find($dc->id);

		$fallback = $newsletterCategory->getRecipientsMode() == 'byMessageOrCategory';

		/*
		 * TODO
		$selectedRecipients = $newsletterCategory->getRecipients();

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
		*/

		$add[$key] = implode('<br>', $add[$key]);


		if ($newsletterCategory->getThemeMode() == 'byMessageOrCategory') {
			$key = $GLOBALS['TL_LANG']['orm_avisota_message_category']['theme'][0];
			$add[$key] .= ' ' . $GLOBALS['TL_LANG']['orm_avisota_message']['fallback'];
		}


		if ($newsletterCategory->getTransportMode() == 'byMessageOrCategory') {
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
	public function addMessageRow($newsletterData)
	{
		$icon = $newsletterData['sendOn'] ? 'visible' : 'invisible';

		$label = $newsletterData['subject'];

		if ($newsletterData['sendOn']) {
			$label .= ' <span style="color:#b3b3b3; padding-left:3px;">(' . sprintf(
					$GLOBALS['TL_LANG']['orm_avisota_recipient']['sended'],
					$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $newsletterData['sendOn'])
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
}
