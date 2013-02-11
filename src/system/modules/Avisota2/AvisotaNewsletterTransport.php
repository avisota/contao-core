<?php if (defined('TL_ROOT')) {
	die('You can not access this file via contao!');
}

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


// disable contao 2.10 token check
define('BYPASS_TOKEN_CHECK', true);

// run in FE mode
define('TL_MODE', 'FE');

// Define the static URL constants
define('TL_FILES_URL', '');
define('TL_SCRIPT_URL', '');
define('TL_PLUGINS_URL', '');

// initialize contao
include('../../initialize.php');

// disable error reporting
#error_reporting(0);

/**
 * Class AvisotaNewsletterTransport
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaNewsletterTransport extends Backend
{
	protected $newsletter;

	protected $category;

	protected $attachments;

	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();
		$this->import('AvisotaBase', 'Base');
		$this->import('AvisotaNewsletterContent', 'Content');
		$this->import('AvisotaStatic', 'Static');
		$this->import('Database');

		// force all URLs absolute
		$GLOBALS['TL_CONFIG']['forceAbsoluteDomainLink'] = true;

		// load default translations
		$this->loadLanguageFile('default');

		// HOTFIX Remove isotope frontend hook
		if (isset($GLOBALS['TL_HOOKS']['parseTemplate']) && is_array($GLOBALS['TL_HOOKS']['parseTemplate'])) {
			foreach ($GLOBALS['TL_HOOKS']['parseTemplate'] as $k => $v) {
				if ($v[0] == 'IsotopeFrontend') {
					unset($GLOBALS['TL_HOOKS']['parseTemplate'][$k]);
				}
			}
		}
		// HOTFIX Remove catalog frontend hook
		if (isset($GLOBALS['TL_HOOKS']['parseFrontendTemplate']) && is_array(
			$GLOBALS['TL_HOOKS']['parseFrontendTemplate']
		)
		) {
			foreach ($GLOBALS['TL_HOOKS']['parseFrontendTemplate'] as $k => $v) {
				if ($v[0] == 'CatalogExt') {
					unset($GLOBALS['TL_HOOKS']['parseFrontendTemplate'][$k]);
				}
			}
		}
	}

	public function run()
	{
		// user have to be authenticated
		$this->User->authenticate();

		// load language files
		$this->loadLanguageFile('tl_avisota_newsletter');

		// get the current action
		$action = $this->Input->post('action');

		// preview a newsletter
		if ($action == 'preview') {
			$this->preview();
		}

		// user have to be the right to transport from this point
		if (!$this->User->isAdmin && !$this->User->hasAccess('send', 'avisota_newsletter_permissions')) {
			$this->log('Not enough permissions to send avisota newsletter', 'AvisotaNewsletterTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// schedule a newsletter
		if ($action == 'schedule') {
			$this->schedule();
		}

		// send a newsletter
		if ($action == 'send') {
			$this->send();
		}

		// no valid action to do
		$this->log('No action given.', 'AvisotaNewsletterTransport', TL_ERROR);
		$this->redirect('contao/main.php?act=error');
	}


	/**
	 *
	 */
	protected function findNewsletter($id)
	{
		$this->newsletter = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
			->execute($id);

		if (!$this->newsletter->next()) {
			$this->log('Could not find newsletter ID ' . $id, 'AvisotaNewsletterTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// find the newsletter category
		$this->category = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter_category WHERE id=?")
			->execute($this->newsletter->pid);

		if (!$this->category->next()) {
			$this->log(
				'Could not find newsletter category ID ' . $this->newsletter->pid,
				'AvisotaNewsletterTransport',
				TL_ERROR
			);
			$this->redirect('contao/main.php?act=error');
		}

		// set static data
		$this->Static->setNewsletter($this->newsletter);
		$this->Static->setCategory($this->category);

		// Overwrite the SMTP configuration
		if ($this->category->useSMTP) {
			$GLOBALS['TL_CONFIG']['useSMTP'] = true;

			$GLOBALS['TL_CONFIG']['smtpHost'] = $this->category->smtpHost;
			$GLOBALS['TL_CONFIG']['smtpUser'] = $this->category->smtpUser;
			$GLOBALS['TL_CONFIG']['smtpPass'] = $this->category->smtpPass;
			$GLOBALS['TL_CONFIG']['smtpEnc']  = $this->category->smtpEnc;
			$GLOBALS['TL_CONFIG']['smtpPort'] = $this->category->smtpPort;
		}

		// Add default sender address
		if (!strlen($this->category->sender)) {
			list($this->category->senderName, $this->category->sender) = $this->splitFriendlyName(
				$GLOBALS['TL_CONFIG']['adminEmail']
			);
		}

		$this->attachments = array();

		// Add attachments
		if ($this->newsletter->addFile) {
			$files = deserialize($this->newsletter->files);

			if (is_array($files) && count($files) > 0) {
				foreach ($files as $file) {
					if (is_file(TL_ROOT . '/' . $file)) {
						$this->attachments[] = $file;
					}
				}
			}
		}
	}


	/**
	 * Send a newsletter preview.
	 */
	protected function preview()
	{
		$this->findNewsletter($this->Input->post('id'));

		// get the email address
		$recipientEmail = false;
		if ($this->Input->post('recipient_email')) {
			if ($this->User->isAdmin || $this->User->hasAccess('send', 'avisota_newsletter_permissions')) {
				$recipientEmail = urldecode($this->Input->post('recipient_email', true));
			}
		}
		else {
			$user = $this->Database
				->prepare("SELECT * FROM tl_user WHERE id=?")
				->execute($this->Input->post('recipient_user'));
			if ($user->next()) {
				$recipient = $user->row();
				$recipientEmail     = $user->email;
			}
		}

		// validate the email address
		if (!$recipientEmail || !$this->isValidEmailAddress($recipientEmail)) {
			$_SESSION['TL_PREVIEW_ERROR'] = true;
			return;
		}

		// read session data
		$session = $this->Session->get('AVISOTA_PREVIEW');

		// create the recipient object
		if (empty($recipient)) {
			$recipient          = $this->Base->getPreviewRecipient($session['personalized']);
			$recipient['email'] = $recipientEmail;
		}
		$personalized = $this->Base->finalizeRecipientArray($recipient);

		// register static data
		$this->Static->set($this->category, $this->newsletter, $recipient);

		// create the contents
		$plain = $this->Content->generatePlain($this->newsletter, $this->category, $personalized);
		$html  = $this->Content->generateHtml($this->newsletter, $this->category, $personalized);

		// prepare content for sending, e.a. replace specific insert tags
		$plain = $this->Content->prepareBeforeSending($plain);
		$html  = $this->Content->prepareBeforeSending($html);

		// replace insert tags
		$plain = $this->replaceInsertTags($plain);
		$html  = $this->replaceInsertTags($html);

		// Send
		$email = $this->generateEmailObject();
		$this->sendNewsletter($email, $plain, $html, $recipient, $personalized);

		// Redirect
		$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirmPreview'], $recipientEmail);

		$this->redirect(
			'contao/main.php?do=avisota_newsletter&table=tl_avisota_newsletter&key=send&id=' . $this->newsletter->id
		);
	}


	/**
	 * Put a newsletter into the outbox.
	 */
	protected function schedule()
	{
		$this->findNewsletter($this->Input->post('id'));

		$time = time();

		$outboxId = $this->Database
			->prepare("INSERT INTO tl_avisota_newsletter_outbox %s")
			->set(array('pid' => $this->newsletter->id, 'tstamp' => $time))
			->execute()
			->insertId;

		// Insert list of recipients into outbox
		$recipients = unserialize($this->newsletter->recipients);
		foreach ($recipients as $recipientEmail) {
			if (preg_match('#^(list|mgroup)\-(\d+)$#', $recipientEmail, $matches)) {
				switch ($matches[1]) {
					case 'list':
						$idTemp = $matches[2];
						$this->Database
							->prepare(
							"
							INSERT INTO
								tl_avisota_newsletter_outbox_recipient
								(pid, tstamp, email, domain, recipientID, source, sourceID)
							SELECT
								?,
								?,
								r.email,
								SUBSTRING(r.email, LOCATE('@', r.email)+1),
								r.id,
								'list',
								r.pid
							FROM
								tl_avisota_recipient r
							WHERE
									r.email NOT IN (SELECT email FROM tl_avisota_newsletter_outbox_recipient WHERE pid=?)
								AND	r.pid=?
								AND r.confirmed='1'"
						)
							->execute($outboxId, $time, $outboxId, $idTemp);
						break;

					case 'mgroup':
						$idTemp = $matches[2];
						$this->Database
							->prepare(
							"
							INSERT INTO
								tl_avisota_newsletter_outbox_recipient
								(pid, tstamp, email, domain, recipientID, source, sourceID)
							SELECT
								?,
								?,
								m.email,
								SUBSTRING(m.email, LOCATE('@', m.email)+1),
								m.id,
								'mgroup',
								g.group_id
							FROM
								tl_member m
							LEFT JOIN
								tl_member_to_group g
							ON
								m.id=g.member_id
							WHERE
									m.email NOT IN (SELECT email FROM tl_avisota_newsletter_outbox_recipient WHERE pid=?)
								AND	g.group_id=?
								AND m.disable=''
								AND m.email!=''"
						)
							->execute($outboxId, $time, $outboxId, $idTemp);
						break;
				}
			}
		}

		$this->redirect('contao/main.php?do=avisota_outbox&id=' . $outboxId);
	}


	/**
	 * Send a newsletter from the outbox.
	 */
	protected function send()
	{
		$outbox = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter_outbox WHERE id=?")
			->execute($this->Input->post('id'));

		if (!$outbox->next()) {
			$this->log('Could not find outbox ID ' . $this->Input->get('id'), 'AvisotaNewsletterTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->findNewsletter($outbox->pid);

		ob_start();

		// set timeout and count
		$timeout = $GLOBALS['TL_CONFIG']['avisota_max_send_timeout'];
		$count   = $GLOBALS['TL_CONFIG']['avisota_max_send_count'];

		// set counters
		$successes = array();
		$fails  = array();
		$startTime   = time();

		// get recipients
		$recipient = $this->Database
			->prepare(
			"SELECT
					*
				FROM
					tl_avisota_newsletter_outbox_recipient
				WHERE
						pid=?
					AND send=0
				GROUP BY
					domain"
		)
			->limit($count)
			->execute($outbox->id);

		// Send newsletter
		if ($recipient->numRows > 0) {
			if (!$this->newsletter->sendOn) {
				$this->Database
					->prepare(
					"
						UPDATE
							tl_avisota_newsletter
						SET
							sendOn=?
						WHERE
							id=?"
				)
					->execute(time(), $this->newsletter->id);
			}

			$endExecutionTime = $_SERVER['REQUEST_TIME'] + $GLOBALS['TL_CONFIG']['avisota_max_send_time'];

			while ($endExecutionTime > time() && $recipient->next()) {
				$recipientData = $recipient->row();

				// add recipient details
				if ($recipient->source == 'list') {
					$data = $this->Database
						->prepare("SELECT * FROM tl_avisota_recipient WHERE id=?")
						->execute($recipient->recipientID);
					if ($data->next()) {
						$this->Base->extendArray($data->row(), $recipientData);
					}
				}

				// add member details
				if ($recipient->source == 'mgroup') {
					$data = $this->Database
						->prepare("SELECT * FROM tl_member WHERE id=?")
						->execute($recipient->recipientID);
					if ($data->next()) {
						$this->Base->extendArray($data->row(), $recipientData);
					}
				}
				// merge member details
				else if ($GLOBALS['TL_CONFIG']['avisota_merge_member_details']) {
					$data = $this->Database
						->prepare("SELECT * FROM tl_member WHERE email=?")
						->execute($recipient->email);
					if ($data->next()) {
						$this->Base->extendArray($data->row(), $recipientData);
					}
				}

				$personalized = $this->Base->finalizeRecipientArray($recipientData);

				$this->Static->set($this->category, $this->newsletter, $recipientData);

				// create the contents
				$plain = $this->Content->generatePlain($this->newsletter, $this->category, $personalized);
				$html  = $this->Content->generateHtml($this->newsletter, $this->category, $personalized);

				// prepare content for sending, e.a. replace specific insert tags
				$plain = $this->Content->prepareBeforeSending($plain);
				$html  = $this->Content->prepareBeforeSending($html);

				// replace insert tags
				$plain = $this->replaceInsertTags($plain);
				$html  = $this->replaceInsertTags($html);

				// Send
				$email = $this->generateEmailObject();
				if ($this->sendNewsletter(
					$email,
					$plain,
					$html,
					$recipientData,
					$personalized
				)
				) {
					$successes[] = $recipient->row();
				}
				else {
					$fails[] = $recipient->row();

					$this->Database
						->prepare("UPDATE tl_avisota_newsletter_outbox_recipient SET failed='1' WHERE id=?")
						->execute($recipient->id);

					// disable recipient from list
					if ($recipient->source == 'list') {
						if (!$GLOBALS['TL_CONFIG']['avisota_dont_disable_recipient_on_failure']) {
							$this->Database
								->prepare("UPDATE tl_avisota_recipient SET confirmed='' WHERE id=?")
								->execute($recipient->recipientID);
							$this->log(
								'Recipient address "' . $recipient->email . '" was rejected and has been deactivated',
								'AvisotaNewsletterTransport',
								TL_ERROR
							);
						}
					}

					// disable member
					else if ($recipient->source == 'mgroup') {
						if (!$GLOBALS['TL_CONFIG']['avisota_dont_disable_member_on_failure']) {
							$this->Database
								->prepare("UPDATE tl_member SET disable='1' WHERE id=?")
								->execute($recipient->recipientID);
							$this->log(
								'Member address "' . $recipient->email . '" was rejected and has been disabled',
								'AvisotaNewsletterTransport',
								TL_ERROR
							);
						}
					}
				}

				$this->Database
					->prepare("UPDATE tl_avisota_newsletter_outbox_recipient SET send=? WHERE id=?")
					->execute(time(), $recipient->id);
			}
		}

		$error = '';
		do {
			$buffer = ob_get_contents();
			if ($buffer) {
				$error .= $buffer . "\n";
			}
		} while (ob_end_clean());

		header('Content-Type: application/json');
		echo json_encode(
			array(
				'success' => $successes,
				'failed'  => $fails,
				'time'    => time() - $startTime,
				'error'   => $error
			)
		);
		exit;
	}


	/**
	 * Generate the e-mail object and return it
	 *
	 * @return object
	 */
	protected function generateEmailObject()
	{
		$email = new BasicEmail();

		$email->from    = $this->category->sender;
		$email->subject = $this->newsletter->subject;

		// Add sender name
		if (strlen($this->category->senderName)) {
			$email->fromName = $this->category->senderName;
		}

		$email->logFile = 'newsletter_' . $this->newsletter->id . '.log';

		// Attachments
		if (is_array($this->attachments) && count($this->attachments) > 0) {
			foreach ($this->attachments as $attachmentPathname) {
				$email->attachFile(TL_ROOT . '/' . $attachmentPathname);
			}
		}

		return $email;
	}


	/**
	 * Send a newsletter.
	 */
	protected function sendNewsletter(Email $email, $plain, $html, $recipientData, $personalized)
	{
		// set text content
		$email->text = $plain;

		// Prepare html content
		$email->html     = $html;
		$email->imageDir = TL_ROOT . '/';

		$failed = false;

		// Deactivate invalid addresses
		try {
			if ($GLOBALS['TL_CONFIG']['avisota_developer_mode']) {
				$email->sendTo($GLOBALS['TL_CONFIG']['avisota_developer_email']);
			}
			else {
				$email->sendTo($recipientData['email']);
			}
		}
		catch (Swift_RfcComplianceException $e) {
			$failed = true;
		}

		// Rejected recipients
		if (count($email->failures)) {
			$failed = true;
		}

		$this->Static->resetRecipient();

		return !$failed;
	}
}

$avisotaNewsletterTransport = new AvisotaNewsletterTransport();
$avisotaNewsletterTransport->run();
