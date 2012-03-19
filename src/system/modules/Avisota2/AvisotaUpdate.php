<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class AvisotaUpdate
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaUpdate extends BackendModule
{
	/**
	 * Updates
	 */
	public static $updates = array
	(
		'0.4.5' => array('required'=>true),
		'1.5.0' => array('required'=>true),
		'1.5.1' => array('required'=>true),
		'2.0.0-u1' => array('required'=>true),
		'2.0.0-u2' => array(),
		'2.0.0-u3' => array()
	);

	/**
	 * @var AvisotaUpdate
	 */
	protected static $objInstance = null;

	public static function getInstance()
	{
		if (self::$objInstance === null) {
			self::$objInstance = new AvisotaUpdate();
		}
		return self::$objInstance;
	}

	/**
	 * @var Database
	 */
	protected $Database;


	/**
	 * Template file
	 * @var string
	 */
	protected $strTemplate = 'be_avisota_update';

	public function hasUpdates()
	{
		foreach (self::$updates as $strVersion=>$arrUpdate) {
			$strMethod = 'check' . preg_replace('#[^\w]#', '_', $strVersion);
			if ($this->$strMethod()) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Generate the backend module.
	 *
	 * @return string
	 */
	public function generate()
	{
		$this->loadLanguageFile('avisota_update');

		if ($this->Environment->isAjaxRequest)
		{
			$strVersion = $this->Input->get('update');

			if (isset(self::$updates[$strVersion]))
			{
				$strMethod = 'upgrade' . preg_replace('#[^\w]#', '_', $strVersion);
				if ($this->$strMethod())
				{
					header('Content-Type: text/plain');
					echo $GLOBALS['TL_LANG']['avisota_update']['updateSuccess'];
					exit;
				}
			}
			else
			{
				$this->log('Try to run illegal update for version ' . $strVersion, 'AvisotaUpdate::update', TL_ERROR);
			}

			header("HTTP/1.0 500 Internal Server Error");
			header('Content-Type: text/plain');
			echo $GLOBALS['TL_LANG']['avisota_update']['updateFailed'];
			exit;
		}

		$GLOBALS['TL_JAVASCRIPT']['avisota_update'] = 'system/modules/Avisota2/html/avisota_update.js';

		return parent::generate();
	}


	/**
	 * Compile the current element
	 */
	protected function compile()
	{
		$this->Template->updates = self::$updates;

		$arrVersions = array();
		$arrStatus = array();
		foreach (self::$updates as $strVersion=>$arrUpdate) {
			$strMethod = 'check' . preg_replace('#[^\w]#', '_', $strVersion);
			$arrStatus[$strVersion] = $this->$strMethod();
			$arrVersions[preg_replace('#^(\d+\.\d+\.\d+).*$#', '$1', $strVersion)] = $arrStatus[$strVersion];
		}
		$this->Template->status = $arrStatus;

		uksort($arrVersions, 'version_compare');

		$strLastVersion = '0.3.x';
		foreach ($arrVersions as $strVersion=>$blnRequireUpdate) {
			if ($blnRequireUpdate) {
				$this->Template->previous = $strLastVersion;
				break;
			}
			$strLastVersion = $strVersion;
		}
	}

	protected function check0_4_5()
	{
		return $this->Database->tableExists('tl_avisota_newsletter_content')
			&& !$this->Database->fieldExists('area', 'tl_avisota_newsletter_content');
	}

	/**
	 * Database upgrade to 0.4.5
	 */
	protected function upgrade0_4_5()
	{
		try {
			if ($this->Database->tableExists('tl_avisota_newsletter_content'))
			{
				if (!$this->Database->fieldExists('area', 'tl_avisota_newsletter_content'))
				{
					$this->Database
						->execute("ALTER TABLE tl_avisota_newsletter_content ADD area varchar(32) NOT NULL default ''");
				}

				$this->Database
					->prepare("UPDATE tl_avisota_newsletter_content SET area=? WHERE area=?")->execute('body', '');
			}
		} catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade_0_4_5', TL_ERROR);
			return false;
		}
		return true;
	}

	/**
	 * Database upgrade to 1.5.0
	 */
	protected function check1_5_0()
	{
		return $this->Database->tableExists('tl_avisota_newsletter_outbox')
			&& (!$this->Database->tableExists('tl_avisota_newsletter_outbox_recipient') ||
				!$this->Database->fieldExists('tstamp', 'tl_avisota_newsletter_outbox') ||
				$this->Database->fieldExists('token',  'tl_avisota_newsletter_outbox')
				&& $this->Database->fieldExists('email',  'tl_avisota_newsletter_outbox')
				&& $this->Database->fieldExists('send',   'tl_avisota_newsletter_outbox')
				&& $this->Database->fieldExists('source', 'tl_avisota_newsletter_outbox')
				&& $this->Database->fieldExists('failed', 'tl_avisota_newsletter_outbox'));
	}

	/**
	 * Database upgrade to 1.5.0
	 */
	protected function upgrade1_5_0()
	{
		try {
			if ($this->Database->tableExists('tl_avisota_newsletter_outbox'))
			{
				if (!$this->Database->tableExists('tl_avisota_newsletter_outbox_recipient'))
				{
					// create outbox recipient table
					$this->Database->execute("CREATE TABLE `tl_avisota_newsletter_outbox_recipient` (
					  `id` int(10) unsigned NOT NULL auto_increment,
					  `pid` int(10) unsigned NOT NULL default '0',
					  `tstamp` int(10) unsigned NOT NULL default '0',
					  `email` varchar(255) NOT NULL default '',
					  `domain` varchar(255) NOT NULL default '',
					  `recipientID` int(10) unsigned NOT NULL default '0',
					  `source` varchar(255) NOT NULL default '',
					  `sourceID` int(10) unsigned NOT NULL default '0',
					  `send` int(10) unsigned NOT NULL default '0',
					  `failed` char(1) NOT NULL default '',
					  `error` blob NULL,
					  PRIMARY KEY  (`id`),
					  KEY `pid` (`pid`),
					  KEY `email` (`email`),
					  KEY `domain` (`domain`),
					  KEY `send` (`send`),
					  KEY `source` (`source`),
					  KEY `sourceID` (`sourceID`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
				}

				// make sure the tstamp field exists
				if (!$this->Database->fieldExists('tstamp', 'tl_avisota_newsletter_outbox'))
				{
					$this->Database
						->execute("ALTER TABLE tl_avisota_newsletter_outbox ADD tstamp int(10) unsigned NOT NULL default '0'");
				}

				// split the outbox table data
				if (   $this->Database->fieldExists('token',  'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('email',  'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('send',   'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('source', 'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('failed', 'tl_avisota_newsletter_outbox'))
				{
					$objOutbox = $this->Database
						->execute("SELECT DISTINCT pid,token FROM tl_avisota_newsletter_outbox");
					$arrNewsletters = $objOutbox->fetchAllAssoc();

					if (count($arrNewsletters))
					{
						$arrOutboxes = array();

						// create the outboxes
						foreach ($arrNewsletters as $arrRow)
						{
							if ($arrRow['token'])
							{
								$time = $this->Database
									->prepare("SELECT IF (tstamp, tstamp, send) as time FROM (SELECT MIN(tstamp) as tstamp, MIN(send) as send FROM tl_avisota_newsletter_outbox WHERE token=? GROUP BY token) t")
									->execute($arrRow['token'])
									->time;

								$arrOutboxes[$arrRow['token']] = $this->Database
									->prepare("INSERT INTO tl_avisota_newsletter_outbox SET pid=?, tstamp=?")
									->execute($arrRow['pid'], $time)
									->insertId;
							}
						}

						// move the recipients
						foreach ($arrOutboxes as $strToken=>$intOutbox)
						{
							$this->Database
								->prepare("INSERT INTO tl_avisota_newsletter_outbox_recipient (pid,tstamp,email,domain,send,source,sourceID,failed)
									SELECT
										?,
										tstamp,
										email,
										SUBSTRING(email, LOCATE('@', email)+1) as domain,
										send,
										SUBSTRING(source, 1, LOCATE(':', source)-1) as source,
										SUBSTRING(source, LOCATE(':', source)+1) as sourceID,
										failed
									FROM tl_avisota_newsletter_outbox
									WHERE token=?")
								->execute($intOutbox, $strToken);
						}

						// update recipientID
						$objRecipient = $this->Database
							->execute("SELECT * FROM tl_avisota_newsletter_outbox_recipient WHERE recipientID=0");
						while ($objRecipient->next())
						{
							switch ($objRecipient->source)
							{
							case 'list':
								$objResult = $this->Database
									->prepare("SELECT id FROM tl_avisota_recipient WHERE email=? AND pid=?")
									->execute($objRecipient->email, $objRecipient->sourceID);
								break;

							case 'mgroup':
								$objResult = $this->Database
									->prepare("SELECT id FROM tl_member WHERE email=?")
									->execute($objRecipient->email);
								break;

							default:
								continue;
							}

							if ($objResult->next())
							{
								$this->Database
									->prepare("UPDATE tl_avisota_newsletter_outbox_recipient SET recipientID=? WHERE id=?")
									->execute($objResult->id, $objRecipient->id);
							}
						}

						// delete old entries from outbox
						$this->Database
							->execute("DELETE FROM tl_avisota_newsletter_outbox WHERE id NOT IN (" . implode(',', $arrOutboxes) . ")");

						// delete old fields
						foreach (array('token', 'email', 'send', 'source', 'failed') as $strField)
						{
							if ($this->Database->fieldExists($strField,  'tl_avisota_newsletter_outbox'))
							{
								$this->Database->execute('ALTER TABLE tl_avisota_newsletter_outbox DROP ' . $strField);
							}
						}
					}
				}
			}
		} catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade_1_5_0', TL_ERROR);
			return false;
		}
		return true;
	}

	protected function check1_5_1()
	{
		return $this->Database->tableExists('tl_avisota_statistic_raw_recipient_link')
			&& !$this->Database->fieldExists('real_url', 'tl_avisota_statistic_raw_recipient_link')
			&& $this->Database->executeUncached("SELECT * FROM tl_avisota_statistic_raw_recipient_link WHERE (real_url='' OR ISNULL(real_url)) AND url REGEXP 'email=[^…]' LIMIT 1")->numRows
			|| $this->Database->tableExists('tl_avisota_statistic_raw_link')
			&& $this->Database->execute("SELECT * FROM tl_avisota_statistic_raw_link WHERE url REGEXP '&#x?[0-9]+;' LIMIT 1")->numRows;
	}

	/**
	 * Database upgrade to 1.5.1
	 */
	protected function upgrade1_5_1()
	{
		try {
			if ($this->Database->tableExists('tl_avisota_statistic_raw_recipient_link'))
			{
				$this->import('AvisotaStatic', 'Static');

				// make sure the real_url field exists
				if (!$this->Database->fieldExists('real_url', 'tl_avisota_statistic_raw_recipient_link'))
				{
					$this->Database
						->execute("ALTER TABLE tl_avisota_statistic_raw_recipient_link ADD real_url blob NULL");
				}

				// temporary caches
				$arrNewsletterCache  = array();
				$arrCategoryCache    = array();
				$arrUnsubscribeCache = array();

				// links that are reduced
				$arrLinks = array();

				$objLink = $this->Database
					->executeUncached("SELECT * FROM tl_avisota_statistic_raw_recipient_link WHERE (real_url='' OR ISNULL(real_url)) AND url REGEXP 'email=[^…]'");
				while ($objLink->next())
				{
					$objNewsletter     = false;
					$objCategory       = false;
					$strUnsubscribeUrl = false;

					if (isset($arrNewsletterCache[$objLink->pid]))
					{
						$objNewsletter = $arrNewsletterCache[$objLink->pid];
					}
					else
					{
						$objNewsletter = $this->Database
							->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
							->execute($objLink->pid);
						if ($objNewsletter->next())
						{
							$objNewsletter = $arrNewsletterCache[$objLink->pid] = (object)$objNewsletter->row();
						}
						else
						{
							$objNewsletter = $arrNewsletterCache[$objLink->pid] = false;
						}
					}

					if ($objNewsletter)
					{
						if (isset($objCategoryCache[$objNewsletter->pid]))
						{
							$objCategory = $objCategoryCache[$objNewsletter->pid];
						}
						else
						{
							$objCategory = $this->Database
								->prepare("SELECT * FROM tl_avisota_newsletter_category WHERE id=?")
								->execute($objNewsletter->pid);
							if ($objCategory->next())
							{
								$objCategory = $objCategoryCache[$objNewsletter->pid] = (object)$objCategory->row();
							}
							else
							{
								$objCategory = $objCategoryCache[$objNewsletter->pid] = false;
							}
						}
					}

					if ($objCategory)
					{
						if (isset($arrUnsubscribeCache[$objLink->recipient]))
						{
							$strUnsubscribeUrl = $arrUnsubscribeCache[$objLink->recipient];
						}
						else
						{
							$arrRecipient = array('email' => $objLink->recipient);
							$this->Static->set($objCategory, $objNewsletter, $arrRecipient);
							$strUnsubscribeUrl = $arrUnsubscribeCache[$objLink->recipient] = $this->replaceInsertTags('{{newsletter::unsubscribe_url}}');
						}
					}

					if ($strUnsubscribeUrl && $strUnsubscribeUrl == $objLink->url)
					{
						// create a new (real) url
						$strRealUrl = $objLink->url;
						$strUrl = preg_replace('#email=[^&]*#', 'email=…', $objLink->url);

						// update the recipient-less-link
						if (!$arrLinks[$strUrl])
						{
							$this->Database
								->prepare("UPDATE tl_avisota_statistic_raw_link SET url=? WHERE id=?")
								->execute($strUrl, $objLink->linkID);
							$arrLinks[$strUrl] = $objLink->linkID;
						}

						// or delete if there is allready a link with this url
						else
						{
							$this->Database
								->prepare("DELETE FROM tl_avisota_statistic_raw_link WHERE id=?")
								->execute($objLink->linkID);
						}

						// update the recipient-link
						$this->Database
							->prepare("UPDATE tl_avisota_statistic_raw_recipient_link SET linkID=?, url=?, real_url=? WHERE id=?")
							->execute($arrLinks[$strUrl], $strUrl, $strRealUrl, $objLink->id);

						// update link hit
						$this->Database
							->prepare("UPDATE tl_avisota_statistic_raw_link_hit SET linkID=? WHERE linkID=? AND recipientLinkID=?")
							->execute($arrLinks[$strUrl], $objLink->linkID, $objLink->id);
					}
				}
			}
		} catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade_1_5_1', TL_ERROR);
			return false;
		}

		try {
			if ($this->Database->tableExists('tl_avisota_statistic_raw_link'))
			{
				// cache url->id
				$arrCache = array();

				// find and clean html entities encoded urls
				$objLink = $this->Database->execute("SELECT * FROM tl_avisota_statistic_raw_link WHERE url REGEXP '&#x?[0-9]+;'");
				while ($objLink->next())
				{
					// decorde url
					$strUrl = html_entity_decode($objLink->url);

					// search cache
					if (isset($arrCache[$objLink->pid][$strUrl]))
					{
						$intId = $arrCache[$objLink->pid][$strUrl];
					}

					// or search existing record
					else
					{
						$objExistingLink = $this->Database
							->prepare("SELECT * FROM tl_avisota_statistic_raw_link WHERE pid=? AND url=?")
							->executeUncached($objLink->pid, $strUrl);

						if ($objExistingLink->next())
						{
							// use existing record
							$intId = $objExistingLink->id;
						}
						else
						{
							// insert new record
							$intId = $this->Database
								->prepare("INSERT INTO tl_avisota_statistic_raw_link (pid,tstamp,url) VALUES (?, ?, ?)")
								->executeUncached($objLink->pid, $objLink->tstamp, $strUrl)
								->insertId;
						}

						// set cache
						$arrCache[$objLink->pid][$strUrl] = $intId;
					}

					// update recipient link
					$this->Database
						->prepare("UPDATE tl_avisota_statistic_raw_recipient_link SET linkId=? WHERE linkId=?")
						->execute($intId, $objLink->id);

					// delete old record
					$this->Database
						->prepare("DELETE FROM tl_avisota_statistic_raw_link WHERE id=?")
						->execute($objLink->id);

					$this->log('Cleaned html encoded url "' . $strUrl . '"', 'AvisotaRunonce::statsClean', TL_INFO);
				}
			}
		} catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::statsClean', TL_ERROR);
			return false;
		}
		return true;
	}

	protected function check2_0_0_u1()
	{
		return true;
	}

	protected function check2_0_0_u2()
	{
		return true;
	}

	protected function check2_0_0_u3()
	{
		return true;
	}

	public function hookMysqlMultiTriggerCreate($strTriggerName, $objTrigger, $return)
	{
		if ($objTrigger->table == 'tl_avisota_recipient') {
			$return['ALTER_CHANGE' ][] = 'DELETE FROM tl_avisota_recipient_to_mailing_list';
			$return['ALTER_CHANGE' ][] = 'INSERT INTO tl_avisota_recipient_to_mailing_list (recipient, list) SELECT r.id, l.id FROM tl_avisota_recipient r INNER JOIN tl_avisota_mailing_list l ON FIND_IN_SET(l.id, r.lists)';
		}

		if ($objTrigger->table == 'tl_member') {
			$return['ALTER_CHANGE' ][] = 'DELETE FROM tl_member_to_mailing_list';
			$return['ALTER_CHANGE' ][] = 'INSERT INTO tl_member_to_mailing_list (member, list) SELECT m.id, l.id FROM tl_member m INNER JOIN tl_avisota_mailing_list l ON FIND_IN_SET(l.id, m.avisota_lists)';
		}

		return $return;
	}
}
