<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
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
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @filesource
 */


/**
 * Class Avisota
 *
 * Parent class for newsletter content elements.
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class Avisota extends BackendModule
{
	private static $objCurrentCategory;
	
	
	private static $objCurrentNewsletter;
	
	
	private static $arrCurrentRecipient;

	
	public static function getCurrentCategory()
	{
		return self::$objCurrentCategory;
	}
	
	
	public static function getCurrentNewsletter()
	{
		return self::$objCurrentNewsletter;
	}
	
	
	public static function getCurrentRecipient()
	{
		return self::$arrCurrentRecipient;
	}
	
	
	private $htmlHeadCache = false;
	
	
	protected function allowBackendSending()
	{
		if ($GLOBALS['TL_CONFIG']['avisota_backend_send'])
		{
			$this->import('BackendUser', 'User');
			
			if ($GLOBALS['TL_CONFIG']['avisota_backend_send'] == 'disabled')
			{
				return false;
			}
			if ($GLOBALS['TL_CONFIG']['avisota_disable_backend_send'] == 'admin' && !$this->User->admin)
			{
				return false;
			}
		}
		return true;
	}
	
	
	public function importRecipients()
	{
		return 'importRecipients';
	}
	
	
	public function generate()
	{
		switch ($this->Input->get('do'))
		{
		case 'avisota_outbox':
			return $this->outbox();
			
		default:
			return '';
		}
	}
	
	
	/**
	 * Generate module
	 */
	protected function compile()
	{
	}
	
	
	/**
	 * Generate and print out the preview.
	 */
	public function preview()
	{
		$this->import('BackendUser', 'User');
		
		// get preview mode
		if ($this->Input->get('mode'))
		{
			$mode = $this->Input->get('mode');
		}
		else
		{
			$mode = $this->Session->get('tl_avisota_preview_mode');
		}
		
		if (!$mode)
		{
			$mode = NL_HTML;
		}
		$this->Session->set('tl_avisota_preview_mode', $mode);
		
		// get personalized state
		if ($this->Input->get('personalized'))
		{
			$personalized = $this->Input->get('personalized');
		}
		else
		{
			$personalized = $this->Session->get('tl_avisota_preview_personalized');
		}
		
		if (!$personalized)
		{
			$personalized = 'anonymous';
		}
		$this->Session->set('tl_avisota_preview_personalized', $personalized);
		
		// find the newsletter
		$intId = $this->Input->get('id');
		
		$objNewsletter = $this->Database->prepare("
				SELECT
					*
				FROM
					`tl_avisota_newsletter`
				WHERE
					`id`=?")
			->execute($intId);
		
		if (!$objNewsletter->next())
		{
			$this->redirect('contao/main.php?act=tl_error');
		}
		
		// find the newsletter category
		$objCategory = $this->Database->prepare("
				SELECT
					*
				FROM
					`tl_avisota_newsletter_category`
				WHERE
					`id`=?")
			->execute($objNewsletter->pid);
		
		if (!$objCategory->next())
		{
			$this->redirect('contao/main.php?act=tl_error');
		}
		
		// build the recipient data array
		$arrRecipient = $this->getPreviewRecipient($personalized);
		
		$this->prepareRecipient($objNewsletter, $objCategory, $arrRecipient, $mode);
		
		self::$objCurrentCategory = $objCategory;
		self::$objCurrentNewsletter = $objNewsletter;
		self::$arrCurrentRecipient = $arrRecipient;
		
		// generate the preview
		switch ($mode)
		{
		case NL_HTML:
			header('Content-Type: text/html; charset=utf-8');
			echo $this->replaceInsertTags($this->generateHtml($objNewsletter, $objCategory, $personalized));
			exit(0);
			
		case NL_PLAIN:
			header('Content-Type: text/plain; charset=utf-8');
			echo $this->replaceInsertTags($this->generatePlain($objNewsletter, $objCategory, $personalized));
			exit(0);
		}
	}

	
	/**
	 * Show preview and send the Newsletter.
	 * 
	 * @return string
	 */
	public function send()
	{
		$this->import('Database');
		
		$intId = $this->Input->get('id');
		
		// get the newsletter
		$objNewsletter = $this->Database->prepare("
				SELECT
					*
				FROM
					`tl_avisota_newsletter`
				WHERE
					`id`=?")
			->execute($intId);
		
		if (!$objNewsletter->next())
		{
			$this->redirect('contao/main.php?do=avisota_newsletter');
		}
		
		// get the newsletter category
		$objCategory = $this->Database->prepare("
				SELECT
					*
				FROM
					`tl_avisota_newsletter_category`
				WHERE
					`id`=?")
			->execute($objNewsletter->pid);
		
		if (!$objCategory->next())
		{
			$this->redirect('contao/main.php?do=avisota_newsletter');
		}
		
		// Send newsletter
		if (strlen($this->Input->get('token')) && $this->Input->get('token') == $this->Session->get('tl_newsletter_send'))
		{
			$referer = preg_replace('/&(amp;)?(start|mpc|token|recipient|preview)=[^&]*/', '', $this->Environment->request);
				
			// Preview
			if ($this->Input->get('preview'))
			{
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
				
				// create the contents
				$plain = array
				(
					'anonymous' => $this->generatePlain($objNewsletter, $objCategory, 'anonymous'),
					'private' => $this->generatePlain($objNewsletter, $objCategory, 'private')
				);
				$html = array
				(
					'anonymous' => $this->generateHtml($objNewsletter, $objCategory, 'anonymous'),
					'private' => $this->generateHtml($objNewsletter, $objCategory, 'private')
				);
				
				// Check the e-mail address
				if (!$this->isValidEmailAddress($this->Input->get('recipient', true)))
				{
					$_SESSION['TL_PREVIEW_ERROR'] = true;
					$this->redirect($referer);
				}

				$arrRecipient = $this->getPreviewRecipient($this->Session->get('tl_avisota_preview_personalized'));
				$arrRecipient['email'] = urldecode($this->Input->get('recipient', true));

				// Send
				$objEmail = $this->generateEmailObject($objNewsletter, $objCategory, $arrAttachments);
				$this->sendNewsletter($objEmail, $objNewsletter, $objCategory, $plain[$arrRecipient['personalized']], $html[$arrRecipient['personalized']], $arrRecipient, $arrRecipient['personalized']);

				// Redirect
				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirm'], 1);
				$this->redirect($referer);
			}
			
			$strToken = $this->Input->get('token');
			
			// Insert list of recipients into outbox
			$arrRecipients = unserialize($objNewsletter->recipients);
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
									`tl_avisota_newsletter_outbox`
									(`pid`, `token`, `email`)
								SELECT
									DISTINCT ?,?,`email`
								FROM
									`tl_avisota_recipient`
								WHERE
										`pid`=?
									AND `confirmed`='1'")
						   ->execute($objNewsletter->id, $strToken, $intIdTmp);
						break;
						
					case 'mgroup':
						$intIdTmp = $arrMatch[2];
						$this->Database->prepare("
								INSERT INTO
									`tl_avisota_newsletter_outbox`
									(`pid`, `token`, `email`)
								SELECT DISTINCT
									?,?,m.`email`
								FROM
									`tl_member` m
								INNER JOIN
									`tl_member_group` g
								ON
									m.pid=g.id
								WHERE
										g.`id`=?
									AND m.`disable`=''")
						   ->execute($objNewsletter->id, $strToken, $intIdTmp);
						break;
					}
				}
			}
			
			$this->redirect('contao/main.php?do=avisota_outbox' . ($this->allowBackendSending() ? '&id=' . $objNewsletter->id . '&token=' . $strToken : ''));
		}
		
		$strToken = md5(uniqid(mt_rand(), true));
		$this->Session->set('tl_newsletter_send', $strToken);
		
		$objTemplate = new BackendTemplate('be_avisota_send');
		$objTemplate->import('BackendUser', 'User');
		
		// add category data to template
		$objTemplate->setData($objCategory->row());
		
		// add newsletter data to template
		$objTemplate->setData($objNewsletter->row());
		
		// add sender
		$strFrom = '';
		if ($objCategory->sender)
		{
			$strFrom = $objCategory->sender;
		}
		else
		{
			$strFrom = $GLOBALS['TL_CONFIG']['adminEmail'];
		}
		if ($objCategory->senderName)
		{
			$strFrom = sprintf('%s &lt;%s&gt;', $objCategory->senderName, $strFrom);
		}
		$objTemplate->from = $strFrom;
		
		// add recipients
		$arrRecipients = unserialize($objNewsletter->recipients);
		$arrLists = array();
		$arrMgroups = array();
		foreach ($arrRecipients as $strRecipient)
		{
			if (preg_match('#^(list|mgroup)\-(\d+)$#', $strRecipient, $arrMatch))
			{
				switch ($arrMatch[1])
				{
				case 'list':
					$intIdTmp = $arrMatch[2];
					$objList = $this->Database->prepare("
							SELECT
								*
							FROM
								`tl_avisota_recipient_list`
							WHERE
								`id`=?")
						->execute($intIdTmp);
					$arrLists[$intIdTmp] = $objList->title;
					break;
					
				case 'mgroup':
					$intIdTmp = $arrMatch[2];
					$objMgroup = $this->Database->prepare("
							SELECT
								*
							FROM
								`tl_member_group`
							WHERE
								`id`=?")
						->execute($intIdTmp);
					$arrMgroups[$intIdTmp] = $objMgroup->title;
					break;
				}
			}
		}
		$objTemplate->recipients_list = $arrLists;
		$objTemplate->recipients_mgroup = $arrMgroups;
		
		// add token
		$objTemplate->token = $strToken;
		
		// allow backend sending
		$objTemplate->beSend = $this->allowBackendSending();

		// Store the current referer
		$session = $this->Session->get('referer');
		if ($session['current'] != $this->Environment->requestUri)
		{
			$session['tl_avisota_newsletter'] = $this->Environment->requestUri;
			$session['last'] = $session['current'];
			$session['current'] = $this->Environment->requestUri;
			$this->Session->set('referer', $session);
		}
		
		return $objTemplate->parse();
	}
	
	
	protected function outbox()
	{
		$this->loadLanguageFile('tl_avisota_newsletter_outbox');
		$this->loadLanguageFile('tl_avisota_newsletter');
		
		if ($this->Input->get('id') && $this->Input->get('token'))
		{
			$referer = preg_replace('/&(amp;)?(start|mpc|token|recipient|preview)=[^&]*/', '', $this->Environment->request);
			
			if (!$this->allowBackendSending())
			{
				$this->redirect($referer);
			}
			
			$intId = $this->Input->get('id');
			$strToken = $this->Input->get('token');
			
			// get the newsletter
			$objNewsletter = $this->Database->prepare("
					SELECT
						*
					FROM
						`tl_avisota_newsletter`
					WHERE
						`id`=?")
				->execute($intId);
			if (!$objNewsletter->next())
			{
				$this->redirect($referer);
			}
	
			// get the category
			$objCategory = $this->Database->prepare("
					SELECT
						*
					FROM
						`tl_avisota_newsletter_category`
					WHERE
						`id`=?")
				->execute($objNewsletter->pid);
			if (!$objCategory->next())
			{
				$this->redirect($referer);
			}
	
			// get total email count
			$objTotal = $this->Database->prepare("
					SELECT
						COUNT(*) as `total`
					FROM
						`tl_avisota_newsletter_outbox`
					WHERE
							`pid`=?
						AND `token`=?")
				->execute($intId, $strToken);
	
			// Return if there are no recipients
			if ($objTotal->total < 1)
			{
				$this->Session->set('tl_newsletter_send', null);
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['error'];
	
				$this->redirect($referer);
			}
	
			$intTotal = $objTotal->total;
	
			// Set timeout and count
			$intTimeout = 1;
			$intCount = 1;
		
			if (!$_SESSION['REJECTED_RECIPIENTS'])
			{
				$_SESSION['REJECTED_RECIPIENTS'] = array();
			}

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
			
			// create the contents
			$plain = array
			(
				'anonymous' => $this->generatePlain($objNewsletter, $objCategory, 'anonymous'),
				'private' => $this->generatePlain($objNewsletter, $objCategory, 'private')
			);
			$html = array
			(
				'anonymous' => $this->generateHtml($objNewsletter, $objCategory, 'anonymous'),
				'private' => $this->generateHtml($objNewsletter, $objCategory, 'private')
			);
			
			// Get recipients
			$objRecipients = $this->Database->prepare("
				SELECT
					m.*, o.email, o.id as `outbox`
				FROM
					tl_avisota_newsletter_outbox o
				LEFT JOIN
					tl_member m
				ON
						o.email=m.email
					AND m.disable=''
				WHERE
						o.pid=?
					AND o.token=?")
				->limit($intCount)
				->execute($objNewsletter->pid, $strToken);
	
			echo '<div style="font-family:Verdana, sans-serif; font-size:11px; line-height:16px; margin-bottom:12px;">';
	
			// Send newsletter
			if ($objRecipients->numRows > 0)
			{
				if (!$objNewsletter->sendOn)
				{
					$this->Database->prepare("
							UPDATE
								tl_avisota_newsletter
							SET
								sendOn=?
							WHERE
								id=?")
						->execute(time(), $objNewsletter->id);
				}
				
				while ($objRecipients->next())
				{
					// private recipient (member id exists)
					if ($objRecipients->id)
					{
						$arrRecipient = $objRecipients->row();
						$personalized = 'private';
					}
					
					// anonymous recipient
					else
					{
						$arrRecipient = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous'];
						$arrRecipient['email'] = $objRecipients->email;
						$personalized = 'anonymous';
					}
					
					// Send
					$objEmail = $this->generateEmailObject($objNewsletter, $objCategory, $arrAttachments);
					$this->sendNewsletter($objEmail, $objNewsletter, $objCategory, $plain[$personalized], $html[$personalized], $arrRecipient, $personalized);
						
					$this->Database->prepare("
							DELETE FROM
								`tl_avisota_newsletter_outbox`
							WHERE
								`id`=?")
						->execute($objRecipients->outbox);
					
					echo 'Sending newsletter to <strong>' . $objRecipients->email . '</strong><br />';
				}
			}
			
			echo '<div style="margin-top:12px;">';
	
			// Redirect back home
			if ($intCount >= $intTotal)
			{
				// Deactivate rejected addresses
				if (!empty($_SESSION['REJECTED_RECIPIENTS']))
				{
					$intRejected = count($_SESSION['REJECTED_RECIPIENTS']);
					$_SESSION['TL_INFO'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['rejected'], $intRejected);
					$intTotal -= $intRejected;
					
					foreach ($_SESSION['REJECTED_RECIPIENTS'] as $strRecipient)
					{
						$this->Database->prepare("UPDATE tl_avisota_recipient SET confirmed='' WHERE email=?")
									   ->execute($strRecipient);
	
						$this->log('Recipient address "' . $strRecipient . '" was rejected and has been deactivated', 'Avisota outbox()', TL_ERROR);
					}
				}
	
				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_newsletter']['confirm'], $intTotal);
	
				echo '<script type="text/javascript">setTimeout(\'window.location="' . $this->Environment->base . $referer . '"\', 1000);</script>';
				echo '<a href="' . $this->Environment->base . $referer . '">Please click here to proceed if you are not using JavaScript</a>';
			}
	
			// Redirect to the next cycle
			else
			{
				echo '<script type="text/javascript">setTimeout(\'window.location="' . $this->Environment->base . $this->Environment->request . '"\', ' . ($intTimeout * 1000) . ');</script>';
				echo '<a href="' . $this->Environment->base . $this->Environment->request . '">Please click here to proceed if you are not using JavaScript</a>';
			}
	
			echo '</div></div>';
			exit;
		}
		else
		{
			$objTemplate = new BackendTemplate('be_avisota_outbox');
			
			// allow backend sending
			$objTemplate->beSend = $this->allowBackendSending();
			
			$objOutbox = $this->Database->execute("
					SELECT
						n.`id` as `id`,
						n.`subject` as `newsletter`,
						COUNT(o.`email`) as `recipients`,
						o.`token`
					FROM
						`tl_avisota_newsletter_outbox` o
					INNER JOIN
						`tl_avisota_newsletter` n
					ON
						n.id=o.pid
					GROUP BY
						o.`pid`,
						o.`token`
					ORDER BY
						n.`subject`");
			$objTemplate->outbox = $objOutbox->fetchAllAssoc();
			
			return $objTemplate->parse();
		}
	}
	
	
	/**
	 * Generate the e-mail object and return it
	 * @param object
	 * @param array
	 * @return object
	 */
	protected function generateEmailObject(Database_Result &$objNewsletter, Database_Result &$objCategory, $arrAttachments)
	{
		$objEmail = new Email();

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
		self::$objCurrentCategory = $objCategory;
		self::$objCurrentNewsletter = $objNewsletter;
		self::$arrCurrentRecipient = $arrRecipient;
		
		// Prepare text content
		$this->prepareRecipient($objNewsletter, $objCategory, $arrRecipient, NL_PLAIN);
		$objEmail->text = $this->replaceInsertTags($plain);

		// Prepare html content
		$this->prepareRecipient($objNewsletter, $objCategory, $arrRecipient, NL_HTML);
		$objEmail->html = $this->replaceInsertTags($html);
		$objEmail->imageDir = TL_ROOT . '/';
		
		// Deactivate invalid addresses
		try
		{
			$objEmail->sendTo($arrRecipient['email']);
		}
		catch (Swift_RfcComplianceException $e)
		{
			$_SESSION['REJECTED_RECIPIENTS'][] = $arrRecipient['email'];
		}

		// Rejected recipients
		if (count($objEmail->failures))
		{
			$_SESSION['REJECTED_RECIPIENTS'][] = $arrRecipient['email'];
		}
		
		self::$objCurrentCategory = null;
		self::$objCurrentNewsletter = null;
		self::$arrCurrentRecipient = null;
	}
	
	
	/**
	 * Generate the newsletter content.
	 * 
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objCategory
	 * @param array $arrRecipient
	 * @param string $personalized
	 * @param string $mode
	 * @return string
	 */
	protected function generateContent(Database_Result &$objNewsletter, Database_Result &$objCategory, $personalized, $mode)
	{
		$strContent = '';
		
		$objContent = $this->Database->prepare("
				SELECT
					*
				FROM
					`tl_avisota_newsletter_content`
				WHERE
						`pid`=?
					AND `invisible`=''
				ORDER BY
					`sorting`")
			->execute($objNewsletter->id);
		
		while ($objContent->next())
		{
			$strContent .= $this->generateNewsletterElement($objContent, $mode, $personalized);
		}
		
		return $strContent;
	}
	
	
	/**
	 * Generate the html newsletter.
	 * 
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objCategory
	 * @param array $arrRecipient
	 * @param string $personalized
	 * @return string
	 */
	protected function generateHtml(Database_Result &$objNewsletter, Database_Result &$objCategory, $personalized)
	{
		$head = '';
		
		if ($this->htmlHeadCache === false)
		{
			$this->import('DomainLink');
			
			$head .= sprintf('<base href="%s">', $this->DomainLink->generateDomainLink(null, '', '', true)) . "\n";
			
			$css = '';
			// Add style sheet newsletter.css
			if (file_exists(TL_ROOT . '/newsletter.css'))
			{
				$css .= $this->cleanCSS(file_get_contents(TL_ROOT . '/newsletter.css')) . "\n";
			}
			
			if (in_array('layout_additional_sources', $this->Config->getActiveModules()))
			{
				$arrStylesheet = unserialize($objCategory->stylesheets);
				if (is_array($arrStylesheet) && count($arrStylesheet))
				{
					$objStylesheet = $this->Database->execute("
							SELECT
								*
							FROM
								`tl_additional_source`
							WHERE
								`id` IN (" . implode(',', array_map('intval', $arrStylesheet)) . ")
							ORDER BY
								`sorting`");
					while ($objStylesheet->next())
					{
						switch ($objStylesheet->type)
						{
						case 'css_url':
							$strUrl = $this->DomainLink->generateDomainLink(null, '', $objStylesheet->css_url, true);
							$css .= $this->cleanCSS(file_get_contents($strUrl)) . "\n";
							break;
							
						case 'css_file':
							$strSource = LayoutAdditionalSources::getSource($objStylesheet, false);
							if (file_exists(TL_ROOT . '/' . $strSource))
							{
								$css .= $this->cleanCSS(file_get_contents(TL_ROOT . '/' . $strSource)) . "\n";
							}
							break;
						}
					}
				}
			}
			
			if ($css)
			{
				$head .= '<style type="text/css">' . "\n" . $css . '</style>' . "\n";
			}
			
			$this->htmlHeadCache = $head;
		}
		else
		{
			$head = $this->htmlHeadCache;
		}
		
		$objTemplate = new FrontendTemplate($objNewsletter->template_html);
		$objTemplate->head = $head;
		$objTemplate->body = $this->generateContent($objNewsletter, $objCategory, $personalized, NL_HTML);
		return $objTemplate->parse();
	}
	
	
	/**
	 * Generate the plain text newsletter.
	 * 
	 * @param Database_Result $objNewsletter
	 * @param Database_Result $objCategory
	 * @param array $arrRecipient
	 * @param string $personalized
	 * @return string
	 */
	protected function generatePlain(Database_Result &$objNewsletter, Database_Result &$objCategory, $personalized)
	{
		$objTemplate = new FrontendTemplate($objNewsletter->template_plain);
		$objTemplate->body = $this->generateContent($objNewsletter, $objCategory, $personalized, NL_PLAIN);
		return $objTemplate->parse();
	}
	
	
	/**
	 * Clean up CSS Code.
	 */
	protected function cleanCSS($css)
	{
		// remove comments
		$css = trim(preg_replace('@/\*\*.*\*/@Us', '', $css));
		// remove @charset
		/*
		if (preg_match('#\@charset\s+[\'"]([\w\-]+)[\'"]\;#Ui', $css, $arrMatch))
		{
			// TODO convert charset
			$css = str_replace($arrMatch[0], '', $css);
		}
		*/
		return $css;
	}
	
	
	/**
	 * Generate a content element return it as plain text string
	 * @param integer
	 * @return string
	 */
	public function getNewsletterElement($intId, $mode = NL_HTML)
	{
		if (!strlen($intId) || $intId < 1)
		{
			return '';
		}

		$this->import('Database');

		$objElement = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter_content
				WHERE
					id=?")
			->limit(1)
			->execute($intId);

		if ($objElement->numRows < 1)
		{
			return '';
		}
		
		$objNewsletter = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter
				WHERE
					id=?")
			->execute($objElement->pid);
		
		$objCategory = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter_category
				WHERE
					id=?")
			->execute($objNewsletter->pid);
		
		self::$arrCurrentRecipient = $this->getPreviewRecipient($objElement->personalize);
		$this->prepareRecipient($objNewsletter, $objCategory, $arrRecipient, $mode);
		
		$strBuffer = $this->generateNewsletterElement($objElement, $mode, $objElement->personalize);
		$strBuffer = $this->replaceInsertTags($strBuffer);
		
		self::$arrCurrentRecipient = null;
		
		return $strBuffer;
	}

	
	/**
	 * Generate a content element return it as plain text string
	 * @param integer
	 * @return string
	 */
	public function generateNewsletterElement($objElement, $mode = NL_HTML, $personalized = '')
	{
		if ($objElement->personalize == 'private' && $personalized != 'private')
		{
			return '';
		}
		
		$strClass = $this->findNewsletterElement($objElement->type);

		// Return if the class does not exist
		if (!$this->classFileExists($strClass))
		{
			$this->log('Newsletter content element class "'.$strClass.'" (newsletter content element "'.$objElement->type.'") does not exist', 'Avisota getNewsletterElement()', TL_ERROR);
			return '';
		}

		$objElement->typePrefix = 'nle_';
		$objElement = new $strClass($objElement);
		switch ($mode)
		{
		case NL_HTML:
			$strBuffer = $objElement->generateHTML();
			break;
		
		case NL_PLAIN:
			$strBuffer = $objElement->generatePlain();
			break;
		}
		
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getNewsletterElement']) && is_array($GLOBALS['TL_HOOKS']['getNewsletterElement']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getNewsletterElement'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($objElement, $strBuffer, $mode);
			}
		}
		
		return $strBuffer;
	}
	
	
	/**
	 * Find a newsletter content element in the TL_NLE array and return its value
	 * @param string
	 * @return mixed
	 */
	protected function findNewsletterElement($strName)
	{
		foreach ($GLOBALS['TL_NLE'] as $v)
		{
			foreach ($v as $kk=>$vv)
			{
				if ($kk == $strName)
				{
					return $vv;
				}
			}
		}

		return '';
	}
	
	
	public function prepareRecipient(&$objNewsletter, &$objCategory, &$arrRecipient, $mode)
	{
		// add the unsubscribe url
		$this->import('DomainLink');
		
		if ($objCategory->unsubscribePage > 0)
		{
			$objPage = $this->getPageDetails($objCategory->unsubscribePage);
			$arrRecipient['unsubscribe_url'] = $this->DomainLink->generateDomainLink($objPage, '', $this->generateFrontendUrl($objPage->row()) . '?email=' . $arrRecipient['email'] . '&unsubscribe=' . ($objCategory->alias ? $objCategory->alias : $objCategory->id), true);
		}
		else
		{
			$arrRecipient['unsubscribe_url'] = $this->DomainLink->generateDomainLink(null, '', '?email=' . $arrRecipient['email'] . '&unsubscribe=' . ($objCategory->alias ? $objCategory->alias : $objCategory->id), true);
		}
		
		switch ($mode)
		{
		case NL_HTML:
			$arrRecipient['unsubscribe'] = sprintf('<a href="%s">%s</a>', $arrRecipient['unsubscribe_url'], $GLOBALS['TL_LANG']['tl_avisota_newsletter']['unsubscribe']);
			break;
		
		case NL_PLAIN:
			$arrRecipient['unsubscribe'] = sprintf("%s\n[%s]", $GLOBALS['TL_LANG']['tl_avisota_newsletter']['unsubscribe'], $arrRecipient['unsubscribe_url']);
			break;
		}
		
	}

	
	/**
	 * Get a dummy recipient array.
	 */
	public function getPreviewRecipient($personalized)
	{
		$arrRecipient = array();
		if ($personalized == 'private')
		{
			$objMember = $this->Database->prepare("
					SELECT
						*
					FROM
						`tl_member`
					WHERE
							`email`=?
						AND `disable`=''")
				->execute($this->User->email);
			if ($objMember->next())
			{
				$arrRecipient = $objMember->row();
				$arrRecipient['name'] = $arrRecipient['firstname'] . ' ' . $arrRecipient['lastname'];
				$arrRecipient['personalized'] = 'private';
			}
			else
			{
				$arrRecipient = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous'];
				list($arrRecipient['firstname'], $arrRecipient['lastname']) = $this->splitFriendlyName($arrRecipient['name']);
				$arrRecipient['personalized'] = 'anonymous';
			}
		}
		else
		{
			$arrRecipient = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous'];
			$arrRecipient['email'] = $this->User->email;
			$arrRecipient['personalized'] = 'anonymous';
		}
		
		// add the salutation
		if (isset($GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_' . $arrRecipient['gender']]))
		{
			$arrRecipient['salutation'] = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_' . $arrRecipient['gender']];
		}
		else
		{
			$arrRecipient['salutation'] = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation'];
		}
		
		return $arrRecipient;
	}
}

class AvisotaInsertTag extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->import('DomainLink');
	}
	
	
	public function replaceNewsletterInsertTags($strTag)
	{
		$strTag = explode('::', $strTag);
		switch ($strTag[0])
		{
		case 'recipient':
			$arrCurrentRecipient = Avisota::getCurrentRecipient();
			if ($arrCurrentRecipient && isset($arrCurrentRecipient[$strTag[1]]))
			{
				return $arrCurrentRecipient[$strTag[1]];
			}
			else
			{
				return '';
			}
			break;
			
		case 'newsletter':
			$objCategory = Avisota::getCurrentCategory();
			$objNewsletter = Avisota::getCurrentNewsletter();
			if ($objCategory && $objNewsletter)
			{
				switch ($strTag[1])
				{
				case 'href':
					if ($objCategory->jumpTo > 0)
					{
						$objPage = $this->getPageDetails($objCategory->jumpTo);
						return $this->DomainLink->generateDomainLink($objPage, '', $this->generateFrontendUrl($objPage->row(), '/item/' . ($objNewsletter->alias ? $objNewsletter->alias : $objNewsletter->id)), true);
					}
				}
			}
			return '';
		}
		return false;
	}
}
?>