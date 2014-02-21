<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Class AvisotaBackendOutbox
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 */
class AvisotaBackendOutbox extends BackendModule
{
	protected $strTemplate = 'be_avisota_outbox';

	public function __construct()
	{
		parent::__construct();
		$this->import('DomainLink');
		$this->import('BackendUser', 'User');
		$this->import('AvisotaBase', 'Base');
		$this->loadLanguageFile('orm_avisota_message');
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see BackendModule::generate()
	 */
	public function generate()
	{
		if ($this->Input->get('act') == 'details') {
			$this->strTemplate = 'be_avisota_outbox_details';
		}
		if ($this->Input->get('act') == 'send') {
			$this->strTemplate = 'be_avisota_outbox_send';
		}

		return parent::generate();
	}


	/**
	 * (non-PHPdoc)
	 *
	 * @see BackendModule::compile()
	 */
	protected function compile()
	{
		if (!$this->User->isAdmin && !$this->User->hasAccess('send', 'avisota_newsletter_permissions')) {
			$this->log('Not enough permissions to send avisota newsletter', 'Avisota outbox', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->loadLanguageFile('orm_avisota_message_outbox');
		$this->loadLanguageFile('orm_avisota_message');

		if ($this->Input->get('act') == 'details') {
			$this->details();
			return;
		}

		if ($this->Input->get('act') == 'remove') {
			$this->remove();
			return;
		}

		if ($this->Input->get('act') == 'send') {
			$this->send();
			return;
		}

		$this->outboxes();
	}

	protected function details()
	{
		$outbox     = $this->getOutbox();
		$newsletter = $this->getNewsletter($outbox);

		$this->Template->outbox     = $outbox->row();
		$this->Template->newsletter = $newsletter->row();

		$sessionData = $this->Session->get('AVISOTA_OUTBOX');

		if (!isset($sessionData['state'])) {
			$sessionData['state'] = '';
		}
		if (!isset($sessionData['offset']) || $sessionData['offset'] > $outbox->recipients) {
			$sessionData['offset'] = 0;
		}
		if (!isset($sessionData['limit'])) {
			$sessionData['limit'] = 30;
		}
		if ($this->Input->post('FORM_SUBMIT') == 'tl_filters') {
			// set new state
			$sessionData['state'] = in_array($this->Input->post('state'), array('outstanding', 'sended', 'failed'))
				? $this->Input->post('state') : '';

			// filter all
			if ($this->Input->post('tl_filter') == 'all') {
				$sessionData['offset'] = 0;
				$sessionData['limit']  = 500;
			}

			// filter limit
			else if (preg_match('#^(\d+),(\d+)$#', $this->Input->post('tl_filter'), $m)) {
				$sessionData['offset'] = intval($m[1]);
				$sessionData['limit']  = intval($m[2]);
			}

			// filter default
			else {
				$sessionData['offset'] = 0;
				$sessionData['limit']  = 30;
			}

			// store session ...
			$this->Session->set('AVISOTA_OUTBOX', $sessionData);

			// ... and reload
			$this->reload();
		}

		$this->Session->set('AVISOTA_OUTBOX', $sessionData);
		$this->Template->state  = $sessionData['state'];
		$this->Template->offset = $sessionData['offset'];
		$this->Template->limit  = $sessionData['limit'];

		switch ($sessionData['state']) {
			case 'outstanding':
				$where = "AND send=0";
				break;

			case 'sended':
				$where = "AND send>0 AND failed=''";
				break;

			case 'failed':
				$where = "AND send>0 AND failed='1'";
				break;

			default:
				$where = '';
		}
		$recipients = array();
		$recipient = \Database::getInstance()
			->prepare("SELECT * FROM orm_avisota_message_outbox_recipient WHERE pid=? $where ORDER BY email")
			->limit($sessionData['limit'], $sessionData['offset'])
			->execute($outbox->id);
		while ($recipient->next()) {
			$source    = $this->getSource($recipient);
			$recipientData = $recipient->row();
			switch ($recipient->source) {
				case 'list':
					$recipientData['linkedEmail'] = '<a href="contao/main.php?do=avisota_recipients&table=orm_avisota_recipient&act=edit&id=' . $recipientData['recipientID'] . '">' . $recipientData['email'] . '</a>';
					break;

				case 'mgroup':
					$recipientData['linkedEmail'] = '<a href="contao/main.php?do=member&act=edit&id=' . $recipientData['recipientID'] . '">' . $recipientData['email'] . '</a>';
					break;
			}
			$recipientData['source'] = $source;
			$recipients[]        = $recipientData;
		}
		$this->Template->recipients = $recipients;
	}

	protected function remove()
	{
		\Database::getInstance()
			->prepare("DELETE FROM orm_avisota_message_outbox WHERE id=?")
			->execute($this->Input->get('id'));
		\Database::getInstance()
			->prepare("DELETE FROM orm_avisota_message_outbox_recipient WHERE pid=?")
			->execute($this->Input->get('id'));

		$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['orm_avisota_message_outbox']['removed'];

		$this->redirect('contao/main.php?do=avisota_outbox');
	}


	protected function send()
	{
		if (!$this->Base->allowBackendSending()) {
			// TODO
			$this->redirect($referer);
		}

		$outbox     = $this->getOutbox();
		$newsletter = $this->getNewsletter($outbox);

		$this->Template->outbox       = $outbox->row();
		$this->Template->newsletter   = $newsletter->row();
		$this->Template->cycleTimeout = $GLOBALS['TL_CONFIG']['avisota_max_send_time'];
		$this->Template->sendTimeout  = $GLOBALS['TL_CONFIG']['avisota_max_send_timeout'] * 1000;
		$this->Template->expectedTime = ($outbox->outstanding / $GLOBALS['TL_CONFIG']['avisota_max_send_count']) * ($GLOBALS['TL_CONFIG']['avisota_max_send_time'] + $GLOBALS['TL_CONFIG']['avisota_max_send_timeout'] * 1000);
	}


	protected function outboxes()
	{
		// allow backend sending
		$this->Template->beSend = $this->Base->allowBackendSending();

		$outboxCounters = array
		(
			'open'       => array(),
			'incomplete' => array(),
			'complete'   => array()
		);
		$outbox = \Database::getInstance()->execute(
			"
				SELECT
					o.id,
					n.subject as newsletter,
					o.tstamp,
					(SELECT COUNT(id) FROM orm_avisota_message_outbox_recipient r WHERE r.pid=o.id) as recipients,
					(SELECT COUNT(id) FROM orm_avisota_message_outbox_recipient r WHERE r.pid=o.id AND r.send=0) as outstanding,
					(SELECT COUNT(id) FROM orm_avisota_message_outbox_recipient r WHERE r.pid=o.id AND r.failed='1') as failed
				FROM
					orm_avisota_message_outbox o
				INNER JOIN
					orm_avisota_message n
				ON
					n.id=o.pid
				ORDER BY
					o.tstamp DESC,
					n.subject ASC"
		);
		while ($outbox->next()) {

			// show source-list-names
			$resultSet = \Database::getInstance()
				->prepare(
				'SELECT source, sourceID, COUNT(id) as recipients FROM orm_avisota_message_outbox_recipient WHERE pid=? GROUP BY source'
			)
				->execute($outbox->id);

			$sources = array();
			while ($resultSet->next()) {
				$source = $this->getSource($resultSet);
				if ($source) {
					$sources[] = array_merge($source, $resultSet->row());
				}
			}
			$outbox->sources = $sources;

			if ($outbox->outstanding == $outbox->recipients) {
				$outboxCounters['open'][] = $outbox->row();
			}
			elseif ($outbox->outstanding > 0) {
				$outboxCounters['incomplete'][] = $outbox->row();
			}
			else {
				$outboxCounters['complete'][] = $outbox->row();
			}
			if ($outbox->failed > 0) {
				$this->Template->display_failed = true;
			}
		}
		if (count($outboxCounters['open']) || count($outboxCounters[incomplete]) || count($outboxCounters['complete'])) {
			$this->Template->outbox = $outboxCounters;
		}
		else {
			$this->Template->outbox = false;
		}

		return $this->Template->parse();
	}


	/**
	 * Get a source description from outbox recipient.
	 */
	protected function getSource($recipient)
	{
		switch ($recipient->source) {
			case 'list':
				$list = \Database::getInstance()
					->prepare("SELECT * FROM orm_avisota_mailing_list WHERE id=?")
					->execute($recipient->sourceID);
				if ($list->next()) {
					$source                = $list->row();
					$source['title']       = sprintf(
						'%s: %s',
						$GLOBALS['TL_LANG']['orm_avisota_message_outbox']['recipient_list'],
						$list->title
					);
					$source['linkedTitle'] = sprintf(
						'%s: <a href="contao/main.php?do=avisota_recipients&table=orm_avisota_recipient&id=%d">%s</a>',
						$GLOBALS['TL_LANG']['orm_avisota_message_outbox']['recipient_list'],
						$list->id,
						$list->title
					);
					return $source;
				}

			case 'mgroup':
				$memberGroup = \Database::getInstance()
					->prepare("SELECT * FROM tl_member_group WHERE id=?")
					->execute($recipient->sourceID);
				if ($memberGroup->next()) {
					$source                = $memberGroup->row();
					$source['title']       = sprintf(
						'%s: %s',
						$GLOBALS['TL_LANG']['orm_avisota_message_outbox']['mgroup'],
						$memberGroup->name
					);
					$source['linkedTitle'] = sprintf(
						'%s: <a href="contao/main.php?do=member">%s</a>',
						$GLOBALS['TL_LANG']['orm_avisota_message_outbox']['mgroup'],
						$memberGroup->name
					);
					return $source;
				}
		}
		return false;
	}

	protected function getOutbox()
	{
		// get the outbox
		$outbox = \Database::getInstance()
			->prepare(
			"SELECT
					*,
					(SELECT COUNT(id) FROM orm_avisota_message_outbox_recipient r WHERE o.id=r.pid) as recipients,
					(SELECT COUNT(id) FROM orm_avisota_message_outbox_recipient r WHERE o.id=r.pid AND r.send=0) as outstanding,
					(SELECT COUNT(id) FROM orm_avisota_message_outbox_recipient r WHERE o.id=r.pid AND r.failed='1') as failed
				FROM
					orm_avisota_message_outbox o
				WHERE
					id=?"
		)
			->execute($this->Input->get('id'));

		if (!$outbox->next()) {
			$this->redirect('contao/main.php?do=avisota_outbox');
		}

		return $outbox;
	}

	protected function getNewsletter($outbox)
	{
		$newsletter = \Database::getInstance()
			->prepare("SELECT * FROM orm_avisota_message WHERE id=?")
			->execute($outbox->pid);

		if (!$newsletter->next()) {
			$this->redirect('contao/main.php?do=avisota_outbox');
		}

		return $newsletter;
	}
}
