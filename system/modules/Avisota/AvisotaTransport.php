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

/**
 * Class AvisotaTransport
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaTransport extends Backend
{
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

		// find the newsletter
		$intId = $this->Input->post('id');

		$objNewsletter = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
			->execute($intId);

		if (!$objNewsletter->next())
		{
			$this->log('Could not find newsletter ID ' . $intId, 'AvisotaTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// find the newsletter category
		$objCategory = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter_category WHERE id=?")
			->execute($objNewsletter->pid);

		if (!$objCategory->next())
		{
			$this->log('Could not find newsletter category ID ' . $objNewsletter->pid, 'AvisotaTransport', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// set static data
		$this->Static->setNewsletter($objNewsletter);
		$this->Static->setCategory($objCategory);

		// Overwrite the SMTP configuration
		if ($objCategory->useSMTP)
		{
			$GLOBALS['TL_CONFIG']['useSMTP'] = true;

			$GLOBALS['TL_CONFIG']['smtpHost'] = $objCategory->smtpHost;
			$GLOBALS['TL_CONFIG']['smtpUser'] = $objCategory->smtpUser;
			$GLOBALS['TL_CONFIG']['smtpPass'] = $objCategory->smtpPass;
			$GLOBALS['TL_CONFIG']['smtpEnc']  = $objCategory->smtpEnc;
			$GLOBALS['TL_CONFIG']['smtpPort'] = $objCategory->smtpPort;
		}

		// Add default sender address
		if (!strlen($objCategory->sender))
		{
			list($objCategory->senderName, $objCategory->sender) = $this->splitFriendlyName($GLOBALS['TL_CONFIG']['adminEmail']);
		}

		$arrAttachments = array();

		// Add attachments
		if ($objNewsletter->addFile)
		{
			$files = deserialize($objNewsletter->files);

			if (is_array($files) && count($files) > 0)
			{
				foreach ($files as $file)
				{
					if (is_file(TL_ROOT . '/' . $file))
					{
						$arrAttachments[] = $file;
					}
				}
			}
		}

		// get the current action
		$strAction = $this->Input->post('action');

		// preview a newsletter
		if ($strAction == 'preview')
		{
			$this->preview($objNewsletter, $objCategory, $arrAttachments);
			$this->redirect('contao/main.php?do=avisota_newsletter&table=tl_avisota_newsletter&key=send&id=' . $intId);
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
			$this->schedule($objNewsletter, $objCategory, $arrAttachments);
			$this->redirect('contao/main.php?do=avisota_outbox&id=' . $intId);
		}

		// send a newsletter
		if ($strAction == 'send')
		{
			header('Content-Type: application/json');
			echo json_encode($this->send($objNewsletter, $objCategory, $arrAttachments));
			exit;
		}

		exit;
		// no valid action to do
		$this->log('No action given.', 'AvisotaTransport', TL_ERROR);
		$this->redirect('contao/main.php?act=error');
	}


	/**
	 * Send a newsletter preview.
	 *
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objCategory
	 * @param array $arrAttachments
	 */
	protected function preview($objNewsletter, $objCategory, $arrAttachments)
	{
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
		$arrRecipient = $this->Base->getPreviewRecipient($arrSession['personalized']);
		$arrRecipient['email'] = $strEmail;
		$this->Static->setRecipient($arrRecipient);

		// create the contents
		switch ($arrRecipient['personalized'])
		{
		case 'private':
			$plain = $this->Content->prepareBeforeSending($this->Content->generatePlain($objNewsletter, $objCategory, 'private'));
			$html = $this->Content->prepareBeforeSending($this->Content->generateHtml($objNewsletter, $objCategory, 'private'));
			break;

		case 'anonymous':
			$plain = $this->Content->prepareBeforeSending($this->Content->generatePlain($objNewsletter, $objCategory, 'anonymous'));
			$html = $this->Content->prepareBeforeSending($this->Content->generateHtml($objNewsletter, $objCategory, 'anonymous'));
			break;
		}

		// Send
		$objEmail = $this->generateEmailObject($objNewsletter, $objCategory, $arrAttachments);
		$this->sendNewsletter($objEmail, $objNewsletter, $objCategory, $plain, $html, $arrRecipient, $arrRecipient['personalized']);

		// Redirect
		$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirmPreview'], $strEmail);
	}


	/**
	 * Put a newsletter into the outbox.
	 *
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objCategory
	 * @param array $arrAttachments
	 */
	protected function schedule($objNewsletter, $objCategory, $arrAttachments)
	{
		$time = time();

		$intOutbox = $this->Database
			->prepare("INSERT INTO tl_avisota_newsletter_outbox %s")
			->set(array('pid'=>$objNewsletter->id, 'tstamp'=>$time))
			->execute()
			->insertId;

		// Insert list of recipients into outbox
		$arrRecipients = unserialize($objNewsletter->recipients);
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
								(pid, tstamp, email, domain, source, sourceID)
							SELECT
								?,
								?,
								r.email,
								SUBSTRING(r.email, LOCATE('@', r.email)+1),
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
								(pid, tstamp, email, domain, source, sourceID)
							SELECT
								?,
								?,
								m.email,
								SUBSTRING(m.email, LOCATE('@', m.email)+1),
								'mgroup',
								m.pid
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
	}


	/**
	 * Send a newsletter from the outbox.
	 *
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objCategory
	 * @param array $arrAttachments
	 */
	protected function send($objNewsletter, $objCategory, $arrAttachments)
	{
		// TODO
	}


	/**
	 * Generate the e-mail object and return it
	 * @param object
	 * @param array
	 * @return object
	 */
	protected function generateEmailObject(Database_Result &$objNewsletter, Database_Result &$objCategory, $arrAttachments)
	{
		$objEmail = new ExtendedEmail();

		$objEmail->from = $objCategory->sender;
		$objEmail->subject = $objNewsletter->subject;

		// Add sender name
		if (strlen($objCategory->senderName))
		{
			$objEmail->fromName = $objCategory->senderName;
		}

		$objEmail->logFile = 'newsletter_' . $objNewsletter->id . '.log';

		// Attachments
		if (is_array($arrAttachments) && count($arrAttachments) > 0)
		{
			foreach ($arrAttachments as $strAttachment)
			{
				$objEmail->attachFile(TL_ROOT . '/' . $strAttachment);
			}
		}

		return $objEmail;
	}


	/**
	 * Send a newsletter.
	 *
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objCategory
	 */
	public function sendNewsletter(Email $objEmail, Database_Result &$objNewsletter, Database_Result &$objCategory, $plain, $html, $arrRecipient, $personalized)
	{
		$this->Static->set($objCategory, $objNewsletter, $arrRecipient);

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
}

$objAvisotaTransport = new AvisotaTransport();
$objAvisotaTransport->run();
