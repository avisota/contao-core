<?php if (defined('TL_ROOT')) die('You can not access this file via contao!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  InfinitySoft 2010,2011
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
 * Class AvisotaTransport
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaTransport extends Backend
{
	/**
	 * @var AvisotaBase
	 */
	protected $Base;

	/**
	 * @var AvisotaContent
	 */
	protected $Content;

	/**
	 * @var AvisotaStatic
	 */
	protected $Static;

	/**
	 * @var Database_Result
	 */
	protected $objNewsletter;

	/**
	 * @var Database_Result
	 */
	protected $objCategory;

	/**
	 * @var array
	 */
	protected $arrAttachments;

	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();
		$this->import('AvisotaBase', 'Base');
		$this->import('AvisotaContent', 'Content');
		$this->import('AvisotaStatic', 'Static');
		$this->import('Database');

		// force all URLs absolute
		$GLOBALS['TL_CONFIG']['forceAbsoluteDomainLink'] = true;

		// load default translations
		$this->loadLanguageFile('default');

		// HOTFIX Remove isotope frontend hook
		if (isset($GLOBALS['TL_HOOKS']['parseTemplate']) && is_array($GLOBALS['TL_HOOKS']['parseTemplate'])) {
			foreach ($GLOBALS['TL_HOOKS']['parseTemplate'] as $k=>$v) {
				if ($v[0] == 'IsotopeFrontend') {
					unset($GLOBALS['TL_HOOKS']['parseTemplate'][$k]);
				}
			}
		}
		// HOTFIX Remove catalog frontend hook
		if (isset($GLOBALS['TL_HOOKS']['parseFrontendTemplate']) && is_array($GLOBALS['TL_HOOKS']['parseFrontendTemplate'])) {
			foreach ($GLOBALS['TL_HOOKS']['parseFrontendTemplate'] as $k=>$v) {
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
		$strAction = $this->Input->post('action');

		// preview a newsletter
		if ($strAction == 'preview')
		{
			$this->preview();
		}

		// user have to be the right to transport from this point
		if (!$this->User->isAdmin && !$this->User->hasAccess('send', 'avisota_newsletter_permissions'))
		{
			$this->log('Not enough permissions to send avisota newsletter', 'AvisotaTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// schedule a newsletter
		if ($strAction == 'schedule')
		{
			$this->schedule();
		}

		// send a newsletter
		if ($strAction == 'send')
		{
			$this->send();
		}

		// no valid action to do
		$this->log('No action given.', 'AvisotaTransport', TL_ERROR);
		$this->redirect('contao/main.php?act=error');
	}


	public function runCron()
	{
		$objOutbox = $this->Database
			->prepare('SELECT o.*,
					       (
					         SELECT id
					         FROM tl_avisota_newsletter_outbox_recipient r
					         WHERE o.id=r.pid AND send=0
					         LIMIT 1
					       ) AS recipient
					   FROM tl_avisota_newsletter_outbox o
					   WHERE o.plannedTime <= ?
					   HAVING recipient > 0')
			->executeUncached(time());

		while ($objOutbox->next())
		{
			$this->log('Start sending of outbox ID ' . $objOutbox->id, 'AvisotaTransport', TL_INFO);

			$intTotalSended = 0;
			$intTotalFailed = 0;
			$strErrors = '';
			do {
				list($intSended, $arrSuccess, $arrFailed) = $this->sendCycle($objOutbox->id);

				if ($intSended > 0) {
					$intTotalSended += $intSended;
					$intTotalFailed += count($arrFailed);

					$this->log('Send ' . $intSended . ' emails in this cycle' . (count($arrFailed) ? ', ' . count($arrFailed) . ' failed' : '') . '.', 'AvisotaTransport', TL_INFO);

					$this->log('Cron cycle wait ' . $GLOBALS['TL_CONFIG']['avisota_max_send_timeout'] . ' seconds.', 'AvisotaTransport', TL_INFO);
					sleep($GLOBALS['TL_CONFIG']['avisota_max_send_timeout']);
				}
			} while ($intSended > 0);

			$strErrors = trim($strErrors);

			$this->log('End sending of outbox ID ' . $objOutbox->id . ', '
				. 'send ' . $intTotalSended . ' emails'
				. ($intTotalFailed ? ', ' . $intTotalFailed . ' failed' : ''),
				'AvisotaTransport', TL_INFO);
		}
	}


	/**
	 *
	 */
	protected function findNewsletter($intId)
	{
		$this->objNewsletter = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
			->executeUncached($intId);

		if (!$this->objNewsletter->next())
		{
			$this->log('Could not find newsletter ID ' . $intId, 'AvisotaTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// find the newsletter category
		$this->objCategory = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter_category WHERE id=?")
			->executeUncached($this->objNewsletter->pid);

		if (!$this->objCategory->next())
		{
			$this->log('Could not find newsletter category ID ' . $this->objNewsletter->pid, 'AvisotaTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// set static data
		$this->Static->setNewsletter($this->objNewsletter);
		$this->Static->setCategory($this->objCategory);

		// Overwrite the SMTP configuration
		if ($this->objCategory->useSMTP)
		{
			$GLOBALS['TL_CONFIG']['useSMTP'] = true;

			$GLOBALS['TL_CONFIG']['smtpHost'] = $this->objCategory->smtpHost;
			$GLOBALS['TL_CONFIG']['smtpUser'] = $this->objCategory->smtpUser;
			$GLOBALS['TL_CONFIG']['smtpPass'] = $this->objCategory->smtpPass;
			$GLOBALS['TL_CONFIG']['smtpEnc']  = $this->objCategory->smtpEnc;
			$GLOBALS['TL_CONFIG']['smtpPort'] = $this->objCategory->smtpPort;
		}

		// Add default sender address
		if (!strlen($this->objCategory->sender))
		{
			list($this->objCategory->senderName, $this->objCategory->sender) = $this->splitFriendlyName($GLOBALS['TL_CONFIG']['adminEmail']);
		}

		$this->arrAttachments = array();

		// Add attachments
		if ($this->objNewsletter->addFile)
		{
			$files = deserialize($this->objNewsletter->files);

			if (is_array($files) && count($files) > 0)
			{
				foreach ($files as $file)
				{
					if (is_file(TL_ROOT . '/' . $file))
					{
						$this->arrAttachments[] = $file;
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
		$strEmail = false;
		if ($this->Input->post('recipient_email'))
		{
			if ($this->User->isAdmin || $this->User->hasAccess('send', 'avisota_newsletter_permissions'))
			{
				$strEmail = urldecode($this->Input->post('recipient_email', true));
			}
		}
		else
		{
			$objUser = $this->Database->prepare("SELECT * FROM tl_user WHERE id=?")->executeUncached($this->Input->post('recipient_user'));
			if ($objUser->next())
			{
				$arrRecipient = $objUser->row();
				$strEmail = $objUser->email;
			}
		}

		// validate the email address
		if (!$strEmail || !$this->isValidEmailAddress($strEmail))
		{
			$_SESSION['TL_PREVIEW_ERROR'] = true;
			return;
		}

		// read session data
		$arrSession = $this->Session->get('AVISOTA_PREVIEW');

		// create the recipient object
		if (empty($arrRecipient))
		{
			$arrRecipient = $this->Base->getPreviewRecipient($arrSession['personalized']);
			$arrRecipient['email'] = $strEmail;
		}
		$personalized = $this->Base->finalizeRecipientArray($arrRecipient);

		// register static data
		$this->Static->set($this->objCategory, $this->objNewsletter, $arrRecipient);

		// create the contents
		$plain = $this->Content->generatePlain($this->objNewsletter, $this->objCategory, $personalized);
		$html = $this->Content->generateHtml($this->objNewsletter, $this->objCategory, $personalized);

		// prepare content for sending, e.a. replace specific insert tags
		$plain = $this->Content->prepareBeforeSending($plain);
		$html  = $this->Content->prepareBeforeSending($html);

		// replace insert tags
		$plain = $this->replaceInsertTags($plain);
		$html  = $this->replaceInsertTags($html);

		// Send
		$objEmail = $this->generateEmailObject();
		$this->sendNewsletter($objEmail, $plain, $html, $arrRecipient, $personalized);

		// Redirect
		$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirmPreview'], $strEmail);

		$this->redirect('contao/main.php?do=avisota_newsletter&table=tl_avisota_newsletter&key=send&id=' . $this->objNewsletter->id);
	}


	/**
	 * Put a newsletter into the outbox.
	 */
	protected function schedule()
	{
		$this->findNewsletter($this->Input->post('id'));

		$time = time();

		$intOutbox = $this->Database
			->prepare("INSERT INTO tl_avisota_newsletter_outbox %s")
			->set(array('pid'=>$this->objNewsletter->id, 'tstamp'=>$time))
			->executeUncached()
			->insertId;

		// Insert list of recipients into outbox
		$arrRecipients = unserialize($this->objNewsletter->recipients);
		$arrMgroups = array();
		foreach ($arrRecipients as $strRecipient)
		{
			if (preg_match('#^(list|mgroup)\-(\d+)$#', $strRecipient, $arrMatch))
			{
				switch ($arrMatch[1])
				{
				case 'list':
					$intIdTmp = $arrMatch[2];
					$this->Database->prepare("
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
								AND r.confirmed='1'")
					   ->executeUncached($intOutbox, $time, $intOutbox, $intIdTmp);
					break;

				case 'mgroup':
					$intIdTmp = $arrMatch[2];
					$this->Database->prepare("
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
								AND m.email!=''")
					   ->executeUncached($intOutbox, $time, $intOutbox, $intIdTmp);
					break;
				}
			}
		}

		$this->redirect('contao/main.php?do=avisota_outbox&id=' . $intOutbox);
	}


	/**
	 * Send a newsletter from the outbox.
	 */
	protected function send()
	{
		ob_start();

		list($intSended, $arrSuccess, $arrFailed) = $this->sendCycle($this->Input->post('id'));

		$strError = '';
		do
		{
			$strBuffer = ob_get_contents();
			if ($strBuffer)
			{
				$strError .= $strBuffer . "\n";
			}
		}
		while (ob_end_clean());

		header('Content-Type: application/json');
		echo json_encode(array(
			'success' => $arrSuccess,
			'failed'  => $arrFailed,
			'time'    => time() - $_SERVER['REQUEST_TIME'],
			'error'   => $strError
		));
		exit;
	}


	protected function sendCycle($intOutbox)
	{
		$objOutbox = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter_outbox WHERE id=?")
			->executeUncached($intOutbox);

		if (!$objOutbox->next())
		{
			$this->log('Could not find outbox ID ' . $this->Input->get('id'), 'AvisotaTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->findNewsletter($objOutbox->pid);

		// set max count
		$intCount = $GLOBALS['TL_CONFIG']['avisota_max_send_count'];

		// set counters
		$intSended  = 0;
		$arrSuccess = array();
		$arrFailed = array();

		// get recipients
		$objRecipients = $this->Database
			->prepare("SELECT
					*
				FROM
					tl_avisota_newsletter_outbox_recipient
				WHERE
						pid=?
					AND send=0
				GROUP BY
					domain")
			->limit($intCount)
			->executeUncached($objOutbox->id);

		// Send newsletter
		if ($objRecipients->numRows > 0)
		{
			if (!$this->objNewsletter->sendOn)
			{
				$this->Database->prepare("
						UPDATE
							tl_avisota_newsletter
						SET
							sendOn=?
						WHERE
							id=?")
					->executeUncached(time(), $this->objNewsletter->id);
			}

			$intEndExecutionTime = $_SERVER['REQUEST_TIME'] + $GLOBALS['TL_CONFIG']['avisota_max_send_time'];

			while (
				// continue while not reach execution time
				$intEndExecutionTime > time()
				// contine while not reached recipients list end
				&& $objRecipients->next()
				// do not hold on errors
				&& (!$GLOBALS['TL_CONFIG']['avisota_hold_on_errors']
					|| (
						// continue while error count is less than X
						count($arrFailed) < $GLOBALS['TL_CONFIG']['avisota_max_send_error_count']
						// continue while error rate is less than X-%
						&& ($intSended <= 2 * $GLOBALS['TL_CONFIG']['avisota_max_send_error_count']
							|| count($arrFailed) / $intSended < $GLOBALS['TL_CONFIG']['avisota_max_send_error_rate']))))
			{
				$arrRecipient = $objRecipients->row();

				// add recipient details
				if ($objRecipients->source == 'list')
				{
					$objData = $this->Database
						->prepare("SELECT * FROM tl_avisota_recipient WHERE id=?")
						->executeUncached($objRecipients->recipientID);
					if ($objData->next())
					{
						$this->Base->extendArray($objData->row(), $arrRecipient);
					}
				}

				// add member details
				if ($objRecipients->source == 'mgroup')
				{
					$objData = $this->Database
						->prepare("SELECT * FROM tl_member WHERE id=?")
						->executeUncached($objRecipients->recipientID);
					if ($objData->next())
					{
						$this->Base->extendArray($objData->row(), $arrRecipient);
					}
				}
				// merge member details
				else if ($GLOBALS['TL_CONFIG']['avisota_merge_member_details'])
				{
					$objData = $this->Database
						->prepare("SELECT * FROM tl_member WHERE email=?")
						->executeUncached($objRecipients->email);
					if ($objData->next())
					{
						$this->Base->extendArray($objData->row(), $arrRecipient);
					}
				}

				$personalized = $this->Base->finalizeRecipientArray($arrRecipient);

				$this->Static->set($this->objCategory, $this->objNewsletter, $arrRecipient);

				// create the contents
				$plain = $this->Content->generatePlain($this->objNewsletter, $this->objCategory, $personalized);
				$html = $this->Content->generateHtml($this->objNewsletter, $this->objCategory, $personalized);

				// prepare content for sending, e.a. replace specific insert tags
				$plain = $this->Content->prepareBeforeSending($plain);
				$html  = $this->Content->prepareBeforeSending($html);

				// replace insert tags
				$plain = $this->replaceInsertTags($plain);
				$html  = $this->replaceInsertTags($html);

				// Send
				$objEmail = $this->generateEmailObject();
				if ($this->sendNewsletter(
						$objEmail,
						$this->prepareTrackingPlain($this->objNewsletter, $this->objCategory, $objRecipients, $plain),
						$this->prepareTrackingHtml($this->objNewsletter, $this->objCategory, $objRecipients, $html),
						$arrRecipient,
						$personalized))
				{
					if (VERBOSE)
					{
						$this->log('Send newsletter "' . $this->objNewsletter->subject . '" to ' . $arrRecipient['email'] . '.', 'AvisotaTransport', TL_INFO);
					}

					$arrSuccess[] = $objRecipients->row();
				}
				else
				{
					if (VERBOSE)
					{
						$this->log('Send newsletter "' . $this->objNewsletter->subject . '" to ' . $arrRecipient['email'] . ' failed.', 'AvisotaTransport', TL_ERROR);
					}

					$arrFailed[] = $objRecipients->row();

					$this->Database
						->prepare("UPDATE tl_avisota_newsletter_outbox_recipient SET failed='1' WHERE id=?")
						->executeUncached($objRecipients->id);

					// disable recipient from list
					if ($objRecipients->source == 'list')
					{
						if (!$GLOBALS['TL_CONFIG']['avisota_dont_disable_recipient_on_failure'])
						{
							$this->Database
								->prepare("UPDATE tl_avisota_recipient SET confirmed='' WHERE id=?")
								->executeUncached($objRecipients->recipientID);
							$this->log('Recipient address "' . $objRecipients->email . '" was rejected and has been deactivated', 'AvisotaTransport', TL_ERROR);
						}
					}

					// disable member
					else if ($objRecipients->source == 'mgroup')
					{
						if (!$GLOBALS['TL_CONFIG']['avisota_dont_disable_member_on_failure'])
						{
							$this->Database
								->prepare("UPDATE tl_member SET disable='1' WHERE id=?")
								->executeUncached($objRecipients->recipientID);
							$this->log('Member address "' . $objRecipients->email . '" was rejected and has been disabled', 'AvisotaTransport', TL_ERROR);
						}
					}
				}

				$this->Database
					->prepare("UPDATE tl_avisota_newsletter_outbox_recipient SET send=? WHERE id=?")
					->executeUncached(time(), $objRecipients->id);

				$intSended ++;
			}
		}

		return array($intSended, $arrSuccess, $arrFailed);
	}


	/**
	 * Generate the e-mail object and return it
	 * @return object
	 */
	protected function generateEmailObject()
	{
		$objEmail = new ExtendedEmail();

		$objEmail->from = $this->objCategory->sender;
		$objEmail->subject = $this->objNewsletter->subject;

		// Add sender name
		if (strlen($this->objCategory->senderName))
		{
			$objEmail->fromName = $this->objCategory->senderName;
		}

		$objEmail->logFile = 'newsletter_' . $this->objNewsletter->id . '.log';

		// Attachments
		if (is_array($this->arrAttachments) && count($this->arrAttachments) > 0)
		{
			foreach ($this->arrAttachments as $strAttachment)
			{
				$objEmail->attachFile(TL_ROOT . '/' . $strAttachment);
			}
		}

		return $objEmail;
	}


	/**
	 * Send a newsletter.
	 */
	protected function sendNewsletter(Email $objEmail, $plain, $html, $arrRecipient, $personalized)
	{
		// set text content
		$objEmail->text = $plain;

		// Prepare html content
		$objEmail->html = $html;
		$objEmail->imageDir = TL_ROOT . '/';

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['sendNewsletter']) && is_array($GLOBALS['TL_HOOKS']['sendNewsletter']))
		{
			foreach ($GLOBALS['TL_HOOKS']['sendNewsletter'] as $callback)
			{
				$this->import($callback[0]);
				if ($this->$callback[0]->$callback[1]($objEmail, $plain, $html, $arrRecipient, $personalized) !== null) {
					// Rejected recipients
					if (count($objEmail->failures))
					{
						$blnFailed = true;
					}

					$this->Static->resetRecipient();

					return !$blnFailed;
				}
			}
		}

		$blnFailed = false;

		// Deactivate invalid addresses
		try
		{
			if ($GLOBALS['TL_CONFIG']['avisota_developer_mode'])
			{
				$objEmail->sendTo($GLOBALS['TL_CONFIG']['avisota_developer_email']);
			}
			else
			{
				$objEmail->sendTo($arrRecipient['email']);
			}
		}
		catch (Exception $e)
		{
			$this->log('Failed to send newsletter ' . $this->objNewsletter->subject . ' to ' . $arrRecipient['email'] . ' with message: ' . $e->getMessage(),
				'AvisotaTransport', TL_ERROR);
			$blnFailed = true;
		}

		// Rejected recipients
		if (count($objEmail->failures))
		{
			$blnFailed = true;
		}

		$this->Static->resetRecipient();

		return !$blnFailed;
	}

	/**
	 * Prepare the html content for tracking.
	 */
	protected function prepareTrackingHtml($objNewsletter, $objCategory, $objRecipient, $strHtml)
	{
		$strHtml = $strHtml;

		$objPrepareTrackingHelper = new PrepareTrackingHelper($objNewsletter, $objCategory, $objRecipient);
		$strHtml = preg_replace_callback('#(<a[^>]*)href=["\']((http|ftp)s?:\/\/.+)["\']#U', array(&$objPrepareTrackingHelper, 'replaceHtml'), $strHtml);

		$objRead = $this->Database
			->prepare("SELECT * FROM tl_avisota_statistic_raw_recipient WHERE pid=? AND recipient=?")
			->executeUncached($objNewsletter->id, $objRecipient->email);
		if ($objRead->next())
		{
			$intRead = $objRead->id;
		}
		else
		{
			$objRead = $this->Database
				->prepare("INSERT INTO tl_avisota_statistic_raw_recipient (pid,tstamp,recipient,recipientID,source,sourceID) VALUES (?, ?, ?, ?, ?, ?)")
				->executeUncached($objNewsletter->id, time(), $objRecipient->email, $objRecipient->recipientID, $objRecipient->source, $objRecipient->sourceID);
			$intRead = $objRead->insertId;
		}

		$strHtml = str_replace('</body>', '<img src="' . $this->Base->extendURL('nltrack.php?read=' . $intRead, null, $objCategory, $objRecipient->row()) . '" alt="" width="1" height="1" />', $strHtml);
		return $strHtml;
	}


	/**
	 * Prepare the plain content for tracking.
	 */
	protected function prepareTrackingPlain($objNewsletter, $objCategory, $objRecipient, $strPlain)
	{
		$strPlain = $strPlain;

		$objPrepareTrackingHelper = new PrepareTrackingHelper($objNewsletter, $objCategory, $objRecipient);
		return preg_replace_callback('#<((http|ftp)s?:\/\/.+)>#U', array(&$objPrepareTrackingHelper, 'replacePlain'), $strPlain);
	}
}

/**
 * Helper class.
 */
class PrepareTrackingHelper extends Controller
{
	protected $objNewsletter;

	protected $objCategory;

	protected $objRecipient;

	protected $strRecipientUnsubscribeUrl;

	public function __construct($objNewsletter, $objCategory, $objRecipient)
	{
		parent::__construct();
		$this->import('Database');
		$this->import('DomainLink');
		$this->import('AvisotaStatic', 'Static');
		$this->objNewsletter = $objNewsletter;
		$this->objCategory = $objCategory;
		$this->objRecipient = $objRecipient;

		$arrRecipeient = $this->Static->getRecipient();
		$this->strRecipientUnsubscribeUrl = $this->replaceInsertTags('{{newsletter::unsubscribe_url}}');
	}

	public function replaceHtml($m)
	{
		$strUrl = $this->replace($m[2]);

		if ($strUrl)
		{
			return $m[1] . 'href="' . specialchars($strUrl) . '"';
		}
		return $m[0];
	}

	public function replacePlain($m)
	{
		$strUrl = $this->replace($m[1]);

		if ($strUrl)
		{
			return '<' . specialchars($strUrl) . '>';
		}
		return $m[0];
	}

	public function replace($strUrl)
	{

		// do not track ...
		if (// images
			preg_match('#\.(jpe?g|png|gif)#i', $strUrl))
		{
			return false;
		}

		$strRealUrl = '';
		if ($this->strRecipientUnsubscribeUrl == $strUrl)
		{
			$strRealUrl = $strUrl;
			$strUrl = preg_replace('#email=[^&]*#', 'email=…', $strUrl);
		}

		$objLink = $this->Database
			->prepare("SELECT * FROM tl_avisota_statistic_raw_link WHERE pid=? AND url=?")
			->executeUncached($this->objNewsletter->id, $strUrl);
		if ($objLink->next())
		{
			$intLink = $objLink->id;
		}
		else
		{
			$intLink = $this->Database
				->prepare("INSERT INTO tl_avisota_statistic_raw_link (pid,tstamp,url) VALUES (?, ?, ?)")
				->executeUncached($this->objNewsletter->id, time(), $strUrl)
				->insertId;
		}

		$objRecipientLink = $this->Database
			->prepare("SELECT * FROM tl_avisota_statistic_raw_recipient_link WHERE pid=? AND linkID=? AND url=? AND recipient=?")
			->executeUncached($this->objNewsletter->id, $intLink, $strUrl, $this->objRecipient->email);
		if ($objLink->next())
		{
			$intRecipientLink = $objRecipientLink->id;
		}
		else
		{
			$intRecipientLink = $this->Database
				->prepare("INSERT INTO tl_avisota_statistic_raw_recipient_link (pid,linkID,tstamp,url,real_url,recipient) VALUES (?, ?, ?, ?, ?, ?)")
				->executeUncached($this->objNewsletter->id, $intLink, time(), $strUrl, $strRealUrl, $this->objRecipient->email)
				->insertId;
		}

		if ($this->objCategory->viewOnlinePage)
		{
			$objPage = $this->getPageDetails($this->objCategory->viewOnlinePage);
		}
		else
		{
			$objPage = null;
		}

		return $this->DomainLink->absolutizeUrl('nltrack.php?link=' . $intRecipientLink, $objPage);
	}
}


// Execution via cli -> cron
if (PHP_SAPI === 'cli')
{
	define('VERBOSE', true);

	// enable error reporting
	error_reporting(E_ALL);

	// hold on erros will not work in cron mode
	$GLOBALS['TL_CONFIG']['avisota_hold_on_errors'] = false;

	// lock the cron file to prevent multiple execution of this file
	$file = fopen(__FILE__, 'r');
	if (!flock($file, LOCK_EX | LOCK_NB))
	{
		fclose($file);
		exit;
	}

	/**
	 * class cron
	 */
	class cron extends Controller
	{
		protected static $objInstance = null;

		public static function getInstance()
		{
			if (self::$objInstance === null) {
				self::$objInstance = new cron();

				$GLOBALS['TL_HOOKS']['addLogEntry'][] = array('cron', 'addLogEntry');
			}
			return self::$objInstance;
		}

		public function run()
		{
			try
			{
				// end all output buffers
				while (ob_end_clean());

				$objAvisotaTransport = new AvisotaTransport();
				$objAvisotaTransport->runCron();
			}
			catch (Exception $e)
			{
				echo $e->getMessage() . "\n";
				echo $e->getTraceAsString();
			}

			while (ob_end_flush());
		}

		public function addLogEntry($strText, $strFunction, $strAction)
		{
			printf("%s %-8s  %s\n",
				$strAction,
				$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], time()),
				html_entity_decode($strText, ENT_QUOTES, 'UTF-8'));
		}
	}

	$objCron = cron::getInstance();
	$objCron->run();

	// release the lock
	flock($file, LOCK_UN);
	fclose($file);
}

// execute via web
else
{
	define('VERBOSE', false);

	$objAvisotaTransport = new AvisotaTransport();
	$objAvisotaTransport->run();
}
