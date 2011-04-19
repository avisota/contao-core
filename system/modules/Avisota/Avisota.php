<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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


/**
 * Class Avisota
 *
 * Parent class for newsletter content elements.
 * @copyright  InfinitySoft 2010,2011
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
	
	
	public function __construct()
	{
		parent::__construct();
		$this->import('DomainLink');
		$this->import('BackendUser', 'User');
		$this->import('AvisotaBase', 'Base');
		$this->loadLanguageFile('tl_avisota_newsletter');
	}
	
	protected function allowBackendSending()
	{
		if ($GLOBALS['TL_CONFIG']['avisota_backend_send'])
		{
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
		
		self::$objCurrentCategory = $objCategory;
		self::$objCurrentNewsletter = $objNewsletter;
		self::$arrCurrentRecipient = $arrRecipient;
		
		// generate the preview
		switch ($mode)
		{
		case NL_HTML:
			header('Content-Type: text/html; charset=utf-8');
			echo $this->replaceInsertTags($this->prepareBeforeSending($this->generateHtml($objNewsletter, $objCategory, $personalized)));
			exit(0);
			
		case NL_PLAIN:
			header('Content-Type: text/plain; charset=utf-8');
			echo $this->replaceInsertTags($this->prepareBeforeSending($this->generatePlain($objNewsletter, $objCategory, $personalized)));
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
		
		self::$objCurrentCategory = $objCategory;
		self::$objCurrentNewsletter = $objNewsletter;
		
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
					'anonymous' => $this->prepareBeforeSending($this->generatePlain($objNewsletter, $objCategory, 'anonymous')),
					'private' => $this->prepareBeforeSending($this->generatePlain($objNewsletter, $objCategory, 'private'))
				);
				$html = array
				(
					'anonymous' => $this->prepareBeforeSending($this->generateHtml($objNewsletter, $objCategory, 'anonymous')),
					'private' => $this->prepareBeforeSending($this->generateHtml($objNewsletter, $objCategory, 'private'))
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
			
			$time = time();
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
						// Note: do not try to use "r.pid IN (..)", this will cause multiple inserts of the same email
						$intIdTmp = $arrMatch[2];
						$this->Database->prepare("
								INSERT INTO
									`tl_avisota_newsletter_outbox`
									(`pid`, `tstamp`, `token`, `email`, `source`)
								SELECT DISTINCT
									?,
									?,
									?,
									r.`email`,
									CONCAT('list:', r.pid)
								FROM
									`tl_avisota_recipient` r
								LEFT OUTER JOIN
									`tl_avisota_newsletter_outbox` o
								ON
										o.`email`=r.`email`
									AND o.`token`=?
								WHERE
										r.`pid`=?
									AND r.`confirmed`='1'
									AND o.`id` IS NULL")
						   ->execute($objNewsletter->id, $time, $strToken, $strToken, $intIdTmp);
						break;
						
					case 'mgroup':
						$intIdTmp = $arrMatch[2];
						$objMgroup = $this->Database->prepare("
								SELECT
									*
								FROM
									`tl_member_group`
								WHERE
										`id`=?
									AND `disable`=''")
							->execute($intIdTmp);
						if ($objMgroup->numRows > 0)
						{
							$arrMgroups[] = $intIdTmp;
						}
						break;
					}
				}
			}
			
			if (count($arrMgroups) > 0)
			{
				$objMember = $this->Database->execute("
						SELECT
							*
						FROM
							`tl_member`
						WHERE
							`disable`=''");
				while ($objMember->next())
				{
					$arrMemberGroups = deserialize($objMember->groups, true);
					$arrIntersect = array_intersect($arrMgroups, $arrMemberGroups);
					if (count($arrIntersect) > 0)
					{
						$this->Database->prepare("
								INSERT INTO
									`tl_avisota_newsletter_outbox`
									(`pid`, `tstamp`, `token`, `email`, `source`)
								VALUES
									(?, ?, ?, ?, ?)")
						   ->execute($objNewsletter->id, $time, $strToken, $objMember->email, 'mgroup:' . array_shift($arrIntersect));
					}
				}
			}
			
			// cleanup multiple inserts
			$objOutput = $this->Database->prepare("SELECT GROUP_CONCAT(id) as id, COUNT(id) as count FROM tl_avisota_newsletter_outbox WHERE token=? GROUP BY email HAVING count>1")->execute($strToken);
			$arrCleanIds = array();
			while ($objOutput->next())
			{
				$arrIds = explode(',', $objOutput->id);
				array_shift($arrIds);
				$arrCleanIds = array_merge($arrIds, $arrCleanIds);
			}
			$arrCleanIds = array_filter(array_map('intval', $arrCleanIds));
			if (count($arrCleanIds) > 0)
			{
				$this->Database->execute("DELETE FROM tl_avisota_newsletter_outbox WHERE id IN (" . implode(',', $arrCleanIds) . ")");
			}
			
			$this->redirect('contao/main.php?do=avisota_outbox' . ($this->allowBackendSending() ? '&id=' . $objNewsletter->id . '&highlight=' . $strToken : ''));
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
					$arrMgroups[$intIdTmp] = $objMgroup->name;
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
	
	
	/**
	 * Show outbox and send newsletter.
	 */
	protected function outbox()
	{
		$this->loadLanguageFile('tl_avisota_newsletter_outbox');
		$this->loadLanguageFile('tl_avisota_newsletter');
		
		if ($this->Input->get('id') && $this->Input->get('token'))
		{
			$referer = preg_replace('/&(amp;)?(act|id|token)=[^&]*/', '', $this->Environment->request);
			
			$intId = $this->Input->get('id');
			$strToken = $this->Input->get('token');
			
			switch ($this->Input->get('act'))
			{
			case 'details':
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
		
				$objTemplate = new BackendTemplate('be_avisota_outbox_details');
				$objTemplate->newsletter = $objNewsletter->subject;
				
				$arrRecipients = array();
				$objRecipients = $this->Database->prepare("
						SELECT
							*
						FROM
							`tl_avisota_newsletter_outbox`
						WHERE
								`pid`=?
							AND `token`=?")
					->execute($intId, $strToken);
				while ($objRecipients->next())
				{
					$arrRecipient = $objRecipients->row();
					
					$arrSource = explode(':', $arrRecipient['source'], 2);
					switch ($arrSource[0])
					{
					case 'list':
						$objList = $this->Database->prepare("
								SELECT
									*
								FROM
									`tl_avisota_recipient_list`
								WHERE
									`id`=?")
							->execute($arrSource[1]);
						if ($objList->next())
						{
							$arrRecipient['source'] = sprintf('%s: %s', $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['recipient_list'], $objList->title);
						}
						break;

					case 'mgroup':
						$objMgroup = $this->Database->prepare("
								SELECT
									*
								FROM
									`tl_member_group`
								WHERE
									`id`=?")
							->execute($arrSource[1]);
						if ($objMgroup->next())
						{
							$arrRecipient['source'] = sprintf('%s: %s', $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['mgroup'], $objMgroup->name);
						}
						break;
					}
					
					$arrRecipients[] = $arrRecipient;
				}
				$objTemplate->recipients = $arrRecipients;
				
				return $objTemplate->parse();
				break;
				
			case 'remove':
				$this->Database->prepare("
						DELETE FROM
							`tl_avisota_newsletter_outbox`
						WHERE
								`pid`=?
							AND `token`=?")
					->execute($intId, $strToken);
				$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['removed'];
				$this->redirect($referer);
				break;
			
			default:
				if (!$this->allowBackendSending())
				{
					$this->redirect($referer);
				}
				
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
				
				self::$objCurrentCategory = $objCategory;
				self::$objCurrentNewsletter = $objNewsletter;
		
				// get total email count
				$objTotal = $this->Database->prepare("
						SELECT
							COUNT(*) as `total`
						FROM
							`tl_avisota_newsletter_outbox`
						WHERE
								`pid`=?
							AND `token`=?
							AND `send`=0")
					->execute($intId, $strToken);
		
				// Return if there are no recipients
				if ($objTotal->total < 1)
				{
					$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirm'];
		
					$this->redirect($referer);
				}
		
				// Set timeout and count
				$intTimeout = 1;
				$intCount = 10;
			
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
					'anonymous' => $this->prepareBeforeSending($this->generatePlain($objNewsletter, $objCategory, 'anonymous')),
					'private' => $this->prepareBeforeSending($this->generatePlain($objNewsletter, $objCategory, 'private'))
				);
				$html = array
				(
					'anonymous' => $this->prepareBeforeSending($this->generateHtml($objNewsletter, $objCategory, 'anonymous')),
					'private' => $this->prepareBeforeSending($this->generateHtml($objNewsletter, $objCategory, 'private'))
				);
				
				// Get recipients
				$objRecipients = $this->Database->prepare("
					SELECT
						t.*,
						t.`outbox_email` as `email`
					FROM (
						SELECT
							m.*,
							o.email as `outbox_email`,
							o.id as `outbox`,
							o.source as `outbox_source`,
							SUBSTRING(o.`email`, LOCATE('@', o.`email`)) as `domain`
						FROM
							tl_avisota_newsletter_outbox o
						LEFT JOIN
							tl_member m
						ON
								o.`email`=m.`email`
							AND m.`disable`=''
						WHERE
								o.`pid`=?
							AND o.`token`=?
							AND o.`send`=0) t
					GROUP BY
						`domain`")
					->limit($intCount)
					->execute($intId, $strToken);
		
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
						if (!$this->sendNewsletter($objEmail, $objNewsletter, $objCategory, $plain[$personalized], $html[$personalized], $arrRecipient, $personalized))
						{
							$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['rejected'], $objRecipients->email);
							
							$this->Database->prepare("
									UPDATE
										`tl_avisota_newsletter_outbox`
									SET
										`failed`='1'
									WHERE
										`id`=?")
								->execute($objRecipients->outbox);
							
							$this->Database->prepare("
									UPDATE
										`tl_avisota_recipient`
									SET
										`confirmed`=''
									WHERE
										`email`=?")
								->execute($objRecipients->email);
							
							$this->log('Recipient address "' . $objRecipients->email . '" was rejected and has been deactivated', 'Avisota outbox()', TL_ERROR);
						}
						
						$this->Database->prepare("
								UPDATE
									`tl_avisota_newsletter_outbox`
								SET
									`send`=?
								WHERE
									`id`=?")
							->execute(time(), $objRecipients->outbox);
						
						echo 'Sending newsletter to <strong>' . $objRecipients->email . '</strong><br />';
					}
				}
				
				echo '<div style="margin-top:12px;">';
		
				// Redirect back home
				if ($objRecipients->numRows == 0)
				{
					$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirm'];
		
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
		}
		else
		{
			$objTemplate = new BackendTemplate('be_avisota_outbox');
			
			// allow backend sending
			$objTemplate->beSend = $this->allowBackendSending();
			
			$arrOutbox = array
			(
				'open' => array(),
				'incomplete' => array(),
				'complete' => array()
			);
			$objOutbox = $this->Database->execute("
					SELECT
						n.`id` as `id`,
						n.`subject` as `newsletter`,
						MIN(o.`tstamp`) as `date`,
						COUNT(o.`email`) as `recipients`,
						(SELECT COUNT(*) FROM `tl_avisota_newsletter_outbox` o2 WHERE o.`token`=o2.`token` AND o2.`send`=0) as `outstanding`,
						(SELECT COUNT(*) FROM `tl_avisota_newsletter_outbox` o2 WHERE o.`token`=o2.`token` AND o2.`failed`='1') as `failed`,
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
						o.`tstamp` DESC,
						n.`subject` ASC");
			while ($objOutbox->next())
			{
				if ($objOutbox->outstanding == $objOutbox->recipients)
				{
					$arrOutbox['open'][] = $objOutbox->row();
				}
				elseif ($objOutbox->outstanding > 0)
				{
					$arrOutbox['incomplete'][] = $objOutbox->row();
				}
				else
				{
					$arrOutbox['complete'][] = $objOutbox->row();
				}
				if ($objOutbox->failed > 0)
				{
					$objTemplate->display_failed = true;
				}
			}
			if (count($arrOutbox['open']) || count($arrOutbox[incomplete]) || count($arrOutbox['complete']))
			{
				$objTemplate->outbox = $arrOutbox;
			}
			else
			{
				$objTemplate->outbox = false;
			}
			
			return $objTemplate->parse();
		}
	}
	
	
	/**
	 * Prepare the html body code before sending.
	 * 
	 * @param string
	 * @return string
	 */
	protected function prepareBeforeSending($strContent)
	{
		$strContent = str_replace('{{env::request}}', '{{newsletter::href}}', $strContent);
		$strContent = preg_replace('#\{\{env::.*\}\}#U', '', $strContent);
		
		return $strContent;
	}
	
	
	/**
	 * Generate the e-mail object and return it
	 * @param object
	 * @param array
	 * @return object
	 */
	protected function generateEmailObject(Database_Result &$objNewsletter, Database_Result &$objCategory, $arrAttachments)
	{
		$objEmail = new AvisotaEmail();

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
		self::$arrCurrentRecipient = &$arrRecipient;
		
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
		
		self::$arrCurrentRecipient = null;
		
		return !$blnFailed;
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
	protected function generateContent(Database_Result &$objNewsletter, Database_Result &$objCategory, $personalized, $mode, $area = false)
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
					AND `area`=?
				ORDER BY
					`sorting`")
			->execute($objNewsletter->id, $area ? $area : 'body');
		
		while ($objContent->next())
		{
			$strContent .= $this->generateNewsletterElement($objContent, $mode, $personalized);
		}
		
		return $strContent;
	}
	
	
	/**
	 * 
	 */
	public function generateOnlineNewsletter($strId)
	{
		// get the newsletter
		$objNewsletter = $this->Database->prepare("
				SELECT
					*
				FROM
					`tl_avisota_newsletter`
				WHERE
						`id`=?
					OR  `alias`=?")
			->execute($strId, $strId);
		
		if (!$objNewsletter->next())
		{
			return false;
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
			return false;
		}
		
		self::$objCurrentCategory = $objCategory;
		self::$objCurrentNewsletter = $objNewsletter;
		
		self::$arrCurrentRecipient = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous'];
		self::$arrCurrentRecipient['outbox_source'] = 'list:0';
		
		$personalized = 'anonymous';
		
		return $this->replaceInsertTags($this->generateHtml($objNewsletter, $objCategory, $personalized));
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
			$head .= sprintf('<base href="%s">', $this->DomainLink->absolutizeUrl('')) . "\n";
			
			// Add style sheet newsletter.css
			if (file_exists(TL_ROOT . '/newsletter.css'))
			{
				$head .= '<style type="text/css">' . "\n" . $this->cleanCSS(file_get_contents(TL_ROOT . '/newsletter.css')) . "\n" . '</style>' . "\n";
			}
			
			if (in_array('layout_additional_sources', $this->Config->getActiveModules()))
			{
				$arrStylesheet = unserialize($objCategory->stylesheets);
				if (is_array($arrStylesheet) && count($arrStylesheet))
				{
					$this->import('LayoutAdditionalSources');
					$this->LayoutAdditionalSources->productive = true;
					$head .= implode("\n", $this->LayoutAdditionalSources->generateIncludeHtml($arrStylesheet, true, $this->Base->getViewOnlinePage($objCategory)));
				}
			}
			
			$this->htmlHeadCache = $head;
		}
		else
		{
			$head = $this->htmlHeadCache;
		}
		
		$objTemplate = new FrontendTemplate($objNewsletter->template_html ? $objNewsletter->template_html : $objCategory->template_html);
		$objTemplate->title = $objNewsletter->subject;
		$objTemplate->head = $head;
		foreach ($this->getNewsletterAreas($objCategory) as $strArea)
		{
			$objTemplate->$strArea = $this->generateContent($objNewsletter, $objCategory, $personalized, NL_HTML, $strArea);
		}
		$objTemplate->newsletter = $objNewsletter->row();
		$objTemplate->category = $objCategory->row();
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
		$objTemplate = new FrontendTemplate($objNewsletter->template_plain ? $objNewsletter->template_plain : $objCategory->template_plain);
		foreach ($this->getNewsletterAreas($objCategory) as $strArea)
		{
			$objTemplate->$strArea = $this->generateContent($objNewsletter, $objCategory, $personalized, NL_PLAIN, $strArea);
		}
		$objTemplate->newsletter = $objNewsletter->row();
		$objTemplate->category = $objCategory->row();
		return $objTemplate->parse();
	}
	
	
	/**
	 * Clean up CSS Code.
	 */
	protected function cleanCSS($css, $source = '')
	{
		if ($source)
		{
			$source = dirname($source);
		}
		
		// remove comments
		$css = trim(preg_replace('@/\*\*.*\*/@Us', '', $css));
		
		// handle @charset
		if (preg_match('#\@charset\s+[\'"]([\w\-]+)[\'"]\;#Ui', $css, $arrMatch))
		{
			// convert character encoding to utf-8
			if (strtoupper($arrMatch[1]) != 'UTF-8')
			{
				$css = iconv(strtoupper($arrMatch[1]), 'UTF-8', $css);
			}
			// remove @charset tag
			$css = str_replace($arrMatch[0], '', $css);
		}
		
		// extends css urls
		if (preg_match_all('#url\((.+)\)#U', $css, $arrMatches, PREG_SET_ORDER))
		{
			foreach ($arrMatches as $arrMatch)
			{
				$path = $source;
				
				$strUrl = $arrMatch[1];
				if (preg_match('#^".*"$#', $strUrl) || preg_match("#^'.*'$#", $strUrl))
				{
					$strUrl = substr($strUrl, 1, -1);
				}
				while (preg_match('#^\.\./#', $strUrl))
				{
					$path = dirname($path);
					$strUrl = substr($strUrl, 3);
				}
				if (!preg_match('#^\w+://#', $strUrl) && $strUrl[0] != '/')
				{
					$strUrl = ($path ? $path . '/' : '') . $strUrl;
				}
				
				$css = str_replace($arrMatch[0], sprintf('url("%s")', $this->Base->extendURL($strUrl)), $css);
			}
		}
		
		return trim($css);
	}
	
	
	/**
	 * Get a list of areas.
	 * 
	 * @param Database_Result $objCategory
	 */
	protected function getNewsletterAreas(Database_Result $objCategory)
	{
		return array_unique(array_filter(array_merge(array('body'), trimsplit(',', $objCategory->areas))));
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
				$arrRecipient['email'] = $this->User->email;
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
		
		$arrRecipient['outbox_source'] = 'list:0';
		
		return $arrRecipient;
	}
}
?>