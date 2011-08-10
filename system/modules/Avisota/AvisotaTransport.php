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


define('TL_MODE', 'FE');
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
	protected $objNewsletter;

	protected $objCategory;

	protected $arrAttachments;

	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		$this->import('AvisotaBase', 'Base');
		$this->import('AvisotaContent', 'Content');
		$this->import('AvisotaStatic', 'Static');
		$this->import('Database');

		// force all URLs absolute
		$GLOBALS['TL_CONFIG']['forceAbsoluteDomainLink'] = true;
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


	/**
	 *
	 */
	protected function findNewsletter($intId)
	{
		$this->objNewsletter = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
			->execute($intId);

		if (!$this->objNewsletter->next())
		{
			$this->log('Could not find newsletter ID ' . $intId, 'AvisotaTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// find the newsletter category
		$this->objCategory = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter_category WHERE id=?")
			->execute($this->objNewsletter->pid);

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
			$objUser = $this->Database->prepare("SELECT * FROM tl_user WHERE id=?")->execute($this->Input->post('recipient_user'));
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
		$personalized = $this->finalizeRecipientArray($arrRecipient);
		$this->Static->setRecipient($arrRecipient);

		// create the contents
		$plain = $this->Content->prepareBeforeSending($this->Content->generatePlain($this->objNewsletter, $this->objCategory, $personalized));
		$html = $this->Content->prepareBeforeSending($this->Content->generateHtml($this->objNewsletter, $this->objCategory, $personalized));

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
			->execute()
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
					   ->execute($intOutbox, $time, $intOutbox, $intIdTmp);
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
					   ->execute($intOutbox, $time, $intOutbox, $intIdTmp);
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
		$objOutbox = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter_outbox WHERE id=?")
			->execute($this->Input->post('id'));

		if (!$objOutbox->next())
		{
			$this->log('Could not find outbox ID ' . $this->Input->get('id'), 'AvisotaTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->findNewsletter($objOutbox->pid);

		ob_start();

		// set timeout and count
		$intTimeout = $GLOBALS['TL_CONFIG']['avisota_max_send_timeout'];
		$intCount = $GLOBALS['TL_CONFIG']['avisota_max_send_count'];

		// set counters
		$arrSuccess = array();
		$arrFailed = array();
		$intStart = time();

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
			->execute($objOutbox->id);

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
					->execute(time(), $this->objNewsletter->id);
			}

			$intEndExecutionTime = $_SERVER['REQUEST_TIME'] + $GLOBALS['TL_CONFIG']['avisota_max_send_time'];

			while ($intEndExecutionTime > time() && $objRecipients->next())
			{
				$arrRecipient = array
				(
					'email' => $objRecipients->email
				);

				// add recipient details
				if ($objRecipients->source == 'list')
				{
					$objData = $this->Database
						->prepare("SELECT * FROM tl_avisota_recipient WHERE id=?")
						->execute($objRecipients->recipientID);
					$this->extendArray($objData->row(), $arrRecipient);
				}

				// add member details
				if (   $objRecipients->source == 'mgroup'
					|| $GLOBALS['TL_CONFIG']['avisota_merge_member_details'])
				{
					$objData = $this->Database
						->prepare("SELECT * FROM tl_member WHERE id=?")
						->execute($objRecipients->recipientID);
					$this->extendArray($objData->row(), $arrRecipient);
				}

				$personalized = $this->finalizeRecipientArray($arrRecipient);

				$this->Static->setRecipient($arrRecipient);
				//var_dump($personalized, $arrRecipient);
				//exit;

				// create the contents
				$plain = $this->Content->generatePlain($this->objNewsletter, $this->objCategory, $personalized);
				$html = $this->Content->generateHtml($this->objNewsletter, $this->objCategory, $personalized);

				// prepare content for sending, e.a. replace specific insert tags
				$plain = $this->Content->prepareBeforeSending($plain);
				$html  = $this->Content->prepareBeforeSending($html);

				// Send
				$objEmail = $this->generateEmailObject();
				if ($this->sendNewsletter(
						$objEmail,
						$this->prepareTrackingPlain($this->objNewsletter, $this->objCategory, $objRecipients, $plain),
						$this->prepareTrackingHtml($this->objNewsletter, $this->objCategory, $objRecipients, $html),
						$arrRecipient,
						$personalized))
				{
					$arrSuccess[] = $objRecipients->row();
				}
				else
				{
					$arrFailed[] = $objRecipients->row();

					$this->Database
						->prepare("UPDATE tl_avisota_newsletter_outbox SET failed='1' WHERE id=?")
						->execute($objRecipients->id);

					// disable recipient from list
					if ($objRecipients->source == 'list')
					{
						if (!$GLOBALS['TL_CONFIG']['avisota_dont_disable_recipient_on_failure'])
						{
							$this->Database
								->prepare("UPDATE tl_avisota_recipient SET confirmed='' WHERE id=?")
								->execute($objRecipients->recipientID);
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
								->execute($objRecipients->recipientID);
							$this->log('Member address "' . $objRecipients->email . '" was rejected and has been disabled', 'AvisotaTransport', TL_ERROR);
						}
					}
				}

				$this->Database
					->prepare("UPDATE tl_avisota_newsletter_outbox_recipient SET send=? WHERE id=?")
					->execute(time(), $objRecipients->id);
			}
		}

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
			'time'    => time() - $intStart,
			'error'   => $strError
		));
		exit;
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
		$this->Static->set($this->objCategory, $this->objNewsletter, $arrRecipient);

		// Prepare text content
		$objEmail->text = $this->replaceInsertTags($plain);

		// Prepare html content
		$objEmail->html = $this->replaceInsertTags($html);
		$objEmail->imageDir = TL_ROOT . '/';

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
		catch (Swift_RfcComplianceException $e)
		{
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
		$objPrepareTrackingHelper = new PrepareTrackingHelper($objNewsletter, $objCategory, $objRecipient);
		$strHtml = preg_replace_callback('#href=["\']((http|ftp)s?:\/\/.+)["\']#U', array(&$objPrepareTrackingHelper, 'replaceHtml'), $strHtml);

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
				->prepare("INSERT INTO tl_avisota_statistic_raw_recipient (pid,tstamp,recipient) VALUES (?, ?, ?)")
				->execute($objNewsletter->id, time(), $objRecipient->email);
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
		$objPrepareTrackingHelper = new PrepareTrackingHelper($objNewsletter, $objCategory, $objRecipient);
		return preg_replace_callback('#<((http|ftp)s?:\/\/.+)>#U', array(&$objPrepareTrackingHelper, 'replacePlain'), $strPlain);
	}

	protected function finalizeRecipientArray(&$arrRecipient)
	{
		// set the firstname and lastname field if missing
		if (empty($arrRecipient['firstname']) && empty($arrRecipient['lastname']) && !empty($arrRecipient['name']))
		{
			list($arrRecipient['firstname'], $arrRecipient['lastname']) = explode(' ', $arrRecipient['name'], 2);
		}

		// set the name field, if missing
		if (empty($arrRecipient['name']) && !(empty($arrRecipient['firstname']) && empty($arrRecipient['lastname'])))
		{
			$arrRecipient['name'] = trim($arrRecipient['firstname'] . ' ' . $arrRecipient['lastname']);
		}

		// set the fullname field, if missing
		if (empty($arrRecipient['fullname']) && !empty($arrRecipient['name']))
		{
			$arrRecipient['fullname'] = trim($arrRecipient['title'] . ' ' . $arrRecipient['name']);
		}

		// set the shortname field, if missing
		if (empty($arrRecipient['shortname']) && !empty($arrRecipient['firstname']))
		{
			$arrRecipient['shortname'] = $arrRecipient['firstname'];
		}

		// a recipient is anonymous, if he has no name
		if (!empty($arrRecipient['name']))
		{
			$personalized = 'private';
		}
		else
		{
			$personalized = 'anonymous';
		}

		// extend with maybe missing anonymous informations
		$this->extendArray($GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous'], $arrRecipient);

		// update salutation
		if (empty($arrRecipient['salutation']))
		{
			if (isset($GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_' . $arrRecipient['gender']]))
			{
				$arrRecipient['salutation'] = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_' . $arrRecipient['gender']];
			}
			else
			{
				$arrRecipient['salutation'] = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation'];
			}
		}

		// replace placeholders in salutation
		preg_match_all('#\{([^\}]+)\}#U', $arrRecipient['salutation'], $matches, PREG_SET_ORDER);
		foreach ($matches as $match)
		{
			$arrRecipient['salutation'] = str_replace($match[0], $arrRecipient[$match[1]], $arrRecipient['salutation']);
		}

		return $personalized;
	}


	protected function extendArray($arrSource, &$arrTarget)
	{
		foreach ($arrSource as $k=>$v)
		{
			if (   !empty($v)
				&& empty($arrTarget[$k])
				&& !in_array($k, array(
					// tl_avisota_recipient fields
					'id', 'pid', 'tstamp', 'confirmed', 'token', 'addedOn', 'addedBy',
					// tl_member fields
					'password', 'session')))
			{
				$arrTarget[$k] = $v;
			}
		}
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

	public function __construct($objNewsletter, $objCategory, $objRecipient)
	{
		parent::__construct();
		$this->import('Database');
		$this->import('DomainLink');
		$this->objNewsletter = $objNewsletter;
		$this->objCategory = $objCategory;
		$this->objRecipient = $objRecipient;
	}

	public function replaceHtml($m)
	{
		$strUrl = $this->replace($m[1]);

		if ($strUrl)
		{
			return 'href="' . specialchars($strUrl) . '"';
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
			preg_match('#\.(jpe?g|png|gif)#i', $strUrl)
			// unsubscribe url
			|| preg_match('#unsubscribetoken#i', $strUrl))
		{
			return false;
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
				->execute($this->objNewsletter->id, time(), $strUrl)
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
				->prepare("INSERT INTO tl_avisota_statistic_raw_recipient_link (pid,linkID,tstamp,url,recipient) VALUES (?, ?, ?, ?, ?)")
				->execute($this->objNewsletter->id, $intLink, time(), $strUrl, $this->objRecipient->email)
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

		return $this->DomainLink->absolutizeUrl('nltrack.php?link=' . $intLink, $objPage);
	}
}

$objAvisotaTransport = new AvisotaTransport();
$objAvisotaTransport->run();
