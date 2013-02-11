<?php

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
		'0.4.5'    => array('required'=> true),
		'1.5.0'    => array('required'=> true),
		'1.5.1'    => array('required'=> true),
		'2.0.0-u1' => array('required'=> true),
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
		foreach (self::$updates as $strVersion=> $arrUpdate) {
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

		if ($this->Input->post('FORM_SUBMIT') == 'avisota_update') {
			// on db update, redirect to er client
			if ($this->Input->post('dbupdate')) {
				$this->redirect('contao/main.php?do=repository_manager&update=database');
			}

			// check for updates
			if ($this->Input->post('update')) {
				$arrVersions = $this->Input->post('update');
				$strVersion = array_shift($arrVersions);

				try {
					if ($this->runUpdate($strVersion)) {
						$_SESSION['TL_INFO'][] = $GLOBALS['TL_LANG']['avisota_update']['updateSuccess'];
					}

					else {
						array_unshift($arrVersions, $strVersion);
						$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['avisota_update']['updateFailed'];
					}
				} catch(Exception $e) {
					array_unshift($arrVersions, $strVersion);
					$_SESSION['TL_ERROR'][] = $e->getMessage();
				}

				if (count($arrVersions)) {
					$_SESSION['TL_INFO'][] = $GLOBALS['TL_LANG']['avisota_update']['moreUpdates'];
					$_SESSION['AUTORUN_UPDATES'] = $arrVersions;
				}

				else {
					unset($_SESSION['AUTORUN_UPDATES']);
				}
			}

			$this->reload();
		}

		if ($this->Environment->isAjaxRequest) {
			$strVersion = $this->Input->get('update');

			if ($this->runUpdate($strVersion)) {
				header('Content-Type: text/plain');
				echo $GLOBALS['TL_LANG']['avisota_update']['updateSuccess'];
				exit;
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
		$arrStatus   = array();
		foreach (self::$updates as $strVersion=> $arrUpdate) {
			$strMethod              = 'check' . preg_replace('#[^\w]#', '_', $strVersion);
			$arrStatus[$strVersion] = $this->$strMethod();

			$strShort               = preg_replace('#^(\d+\.\d+\.\d+).*$#', '$1', $strVersion);
			$arrVersions[$strShort] = (isset($arrVersions[$strShort]) ? $arrVersions[$strShort] : false) || $arrStatus[$strVersion];
		}
		$this->Template->status = $arrStatus;

		uksort($arrVersions, 'version_compare');

		$strLastVersion = '0.3.x';
		foreach ($arrVersions as $strVersion=> $blnRequireUpdate) {
			if ($blnRequireUpdate) {
				break;
			}
			$strLastVersion = $strVersion;
		}
		$this->Template->previous = $strLastVersion;
	}

	protected function runUpdate($strVersion)
	{
		if (isset(self::$updates[$strVersion])) {
			$strMethod = 'upgrade' . preg_replace('#[^\w]#', '_', $strVersion);
			return $this->$strMethod();
		}

		$this->log('Try to run illegal update to version ' . $strVersion . '!', 'AvisotaUpdate::update', TL_ERROR);
		throw new Exception('Try to run illegal update to version ' . $strVersion . '!');
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
			if ($this->Database->tableExists('tl_avisota_newsletter_content')) {
				if (!$this->Database->fieldExists('area', 'tl_avisota_newsletter_content')) {
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
				$this->Database->fieldExists('token', 'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('email', 'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('send', 'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('source', 'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('failed', 'tl_avisota_newsletter_outbox'));
	}

	/**
	 * Database upgrade to 1.5.0
	 */
	protected function upgrade1_5_0()
	{
		try {
			if ($this->Database->tableExists('tl_avisota_newsletter_outbox')) {
				if (!$this->Database->tableExists('tl_avisota_newsletter_outbox_recipient')) {
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
				if (!$this->Database->fieldExists('tstamp', 'tl_avisota_newsletter_outbox')) {
					$this->Database
						->execute("ALTER TABLE tl_avisota_newsletter_outbox ADD tstamp int(10) unsigned NOT NULL default '0'");
				}

				// split the outbox table data
				if ($this->Database->fieldExists('token', 'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('email', 'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('send', 'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('source', 'tl_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('failed', 'tl_avisota_newsletter_outbox')
				) {
					$objOutbox      = $this->Database
						->execute("SELECT DISTINCT pid,token FROM tl_avisota_newsletter_outbox");
					$arrNewsletters = $objOutbox->fetchAllAssoc();

					if (count($arrNewsletters)) {
						$arrOutboxes = array();

						// create the outboxes
						foreach ($arrNewsletters as $arrRow)
						{
							if ($arrRow['token']) {
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
						foreach ($arrOutboxes as $strToken=> $intOutbox)
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

							if ($objResult->next()) {
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
							if ($this->Database->fieldExists($strField, 'tl_avisota_newsletter_outbox')) {
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
			if ($this->Database->tableExists('tl_avisota_statistic_raw_recipient_link')) {
				$this->import('AvisotaStatic', 'Static');

				// make sure the real_url field exists
				if (!$this->Database->fieldExists('real_url', 'tl_avisota_statistic_raw_recipient_link')) {
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

					if (isset($arrNewsletterCache[$objLink->pid])) {
						$objNewsletter = $arrNewsletterCache[$objLink->pid];
					}
					else
					{
						$objNewsletter = $this->Database
							->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
							->execute($objLink->pid);
						if ($objNewsletter->next()) {
							$objNewsletter = $arrNewsletterCache[$objLink->pid] = (object) $objNewsletter->row();
						}
						else
						{
							$objNewsletter = $arrNewsletterCache[$objLink->pid] = false;
						}
					}

					if ($objNewsletter) {
						if (isset($objCategoryCache[$objNewsletter->pid])) {
							$objCategory = $objCategoryCache[$objNewsletter->pid];
						}
						else
						{
							$objCategory = $this->Database
								->prepare("SELECT * FROM tl_avisota_newsletter_category WHERE id=?")
								->execute($objNewsletter->pid);
							if ($objCategory->next()) {
								$objCategory = $objCategoryCache[$objNewsletter->pid] = (object) $objCategory->row();
							}
							else
							{
								$objCategory = $objCategoryCache[$objNewsletter->pid] = false;
							}
						}
					}

					if ($objCategory) {
						if (isset($arrUnsubscribeCache[$objLink->recipient])) {
							$strUnsubscribeUrl = $arrUnsubscribeCache[$objLink->recipient];
						}
						else
						{
							$arrRecipient = array('email' => $objLink->recipient);
							$this->Static->set($objCategory, $objNewsletter, $arrRecipient);
							$strUnsubscribeUrl = $arrUnsubscribeCache[$objLink->recipient] = $this->replaceInsertTags('{{newsletter::unsubscribe_url}}');
						}
					}

					if ($strUnsubscribeUrl && $strUnsubscribeUrl == $objLink->url) {
						// create a new (real) url
						$strRealUrl = $objLink->url;
						$strUrl     = preg_replace('#email=[^&]*#', 'email=…', $objLink->url);

						// update the recipient-less-link
						if (!$arrLinks[$strUrl]) {
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
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade_1_5_1()', TL_ERROR);
			return false;
		}

		try {
			if ($this->Database->tableExists('tl_avisota_statistic_raw_link')) {
				// cache url->id
				$arrCache = array();

				// find and clean html entities encoded urls
				$objLink = $this->Database->execute("SELECT * FROM tl_avisota_statistic_raw_link WHERE url REGEXP '&#x?[0-9]+;'");
				while ($objLink->next())
				{
					// decorde url
					$strUrl = html_entity_decode($objLink->url);

					// search cache
					if (isset($arrCache[$objLink->pid][$strUrl])) {
						$intId = $arrCache[$objLink->pid][$strUrl];
					}

					// or search existing record
					else
					{
						$objExistingLink = $this->Database
							->prepare("SELECT * FROM tl_avisota_statistic_raw_link WHERE pid=? AND url=?")
							->executeUncached($objLink->pid, $strUrl);

						if ($objExistingLink->next()) {
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

					$this->log('Cleaned html encoded url "' . $strUrl . '"', 'AvisotaRunonce::upgrade1_5_1()', TL_INFO);
				}
			}
		} catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade1_5_1()', TL_ERROR);
			return false;
		}
		return true;
	}

	protected function check2_0_0_u1()
	{
		return $this->Database->tableExists('tl_avisota_recipient_list')
			&& !$this->Database->tableExists('tl_avisota_mailing_list')
			|| $this->Database->tableExists('tl_avisota_recipient_list')
			&& $this->Database->tableExists('tl_avisota_mailing_list')
			&& $this->Database->execute("SELECT COUNT(id) AS c FROM tl_avisota_recipient_list")->c > 0
			&& $this->Database->execute("SELECT COUNT(id) AS c FROM tl_avisota_mailing_list")->c == 0;
	}

	protected function upgrade2_0_0_u1()
	{
		try {
			if (!$this->Database->tableExists('tl_avisota_mailing_list')) {
				$this->Database->query("CREATE TABLE `tl_avisota_mailing_list` (
					  `id` int(10) unsigned NOT NULL auto_increment,
					  `tstamp` int(10) unsigned NOT NULL default '0',
					  `title` varchar(255) NOT NULL default '',
					  `alias` varbinary(128) NOT NULL default '',
					  `viewOnlinePage` int(10) unsigned NOT NULL default '0',
					  PRIMARY KEY  (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8");
			}
			if (!$this->Database->tableExists('tl_avisota_recipient_to_mailing_list')) {
				$this->Database->query("CREATE TABLE `tl_avisota_recipient_to_mailing_list` (
					  `recipient` int(10) unsigned NOT NULL default '0',
					  `list` int(10) unsigned NOT NULL default '0',
					  `confirmationSent` int(10) unsigned NOT NULL default '0',
					  `reminderSent` int(10) unsigned NOT NULL default '0',
					  `reminderCount` int(1) unsigned NOT NULL default '0',
					  `confirmed` char(1) NOT NULL default '',
					  `token` char(8) NOT NULL default '',
					  PRIMARY KEY  (`recipient`, `list`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8");
			}
			if ($this->Database->tableExists('tl_avisota_recipient_list')) {
				// create mailing lists from recipient lists
				$this->Database->query("INSERT INTO tl_avisota_mailing_list (id, tstamp, title, alias, viewOnlinePage)
										SELECT id, tstamp, title, alias, viewOnlinePage FROM tl_avisota_recipient_list");

				// insert subscriptions into relation table
				$this->Database->query("INSERT INTO tl_avisota_recipient_to_mailing_list (recipient, list, confirmed, confirmationSent, reminderSent, reminderCount, token)
										SELECT id, pid, confirmed, addedOn, 0, IF(notification, notification, 0), token FROM tl_avisota_recipient");

				// fetch recipients that are multiple
				$arrRecipients = array();
				$objRecipient  = $this->Database
					->execute("SELECT (SELECT COUNT(email) FROM tl_avisota_recipient r2 WHERE r1.email=r2.email) AS c, r1.*
							   FROM tl_avisota_recipient r1
							   HAVING c>1
							   ORDER BY email,tstamp
							   LIMIT 1000");
				while ($objRecipient->next()) {
					// convert email to lowercase
					$objRecipient->email = strtolower($objRecipient->email);

					// set first existence
					if (!isset($arrRecipients[$objRecipient->email])) {
						$arrRecipients[$objRecipient->email]         = $objRecipient->row();
						$arrRecipients[$objRecipient->email]['ids']  = array($objRecipient->id);
						$arrRecipients[$objRecipient->email]['pids'] = array($objRecipient->pid);
					}

					// update fields
					else {
						$arrRecipient          = &$arrRecipients[$objRecipient->email];

						// delete duplicate recipient, but use its data
						if (in_array($objRecipient->pid, $arrRecipients[$objRecipient->email]['pids'])) {
							$this->Database
								->prepare("DELETE FROM tl_avisota_recipient WHERE id=?")
								->execute($objRecipient->id);
						} else {
							$arrRecipient['ids'][] = $objRecipient->id;
							$arrRecipient['pids'][] = $objRecipient->pid;
						}

						foreach ($objRecipient->row() as $field=> $value) {
							// skip some fields
							if ($field == 'id' || $field == 'pid' || $field == 'tstamp' || $field == 'email' || $field == 'confirmed' || $field == 'token' || $field == 'notification') {
								continue;
							}

							// use the lowest value of addedOn
							else if ($field == 'addedOn') {
								if ($arrRecipient['addedOn'] > $value && $value > 0 || $arrRecipient['addedOn'] == 0) {
									$arrRecipient['addedOn'] = $value;
								}
							}

							// update value if previous value is empty or current value is newer
							else if (!empty($value) && (empty($arrRecipient[$field]) || $arrRecipient['tstamp'] < $objRecipient->tstamp)) {
								$arrRecipient[$field] = $value;
							}
						}

						if ($arrRecipient['tstamp'] < $objRecipient->tstamp) {
							$arrRecipient['tstamp'] = $objRecipient->tstamp;
						}
					}
				}
				foreach ($arrRecipients as &$arrRecipient) {
					// update subscription
					$this->Database
						->query("UPDATE tl_avisota_recipient_to_mailing_list
								 SET recipient=" . $arrRecipient['id'] . "
								 WHERE recipient IN (" . implode(',', $arrRecipient['ids']) . ")");

					// delete waste rows
					$this->Database
						->query("DELETE FROM tl_avisota_recipient
								 WHERE id!=" . $arrRecipient['id'] . " AND id IN (" . implode(',', $arrRecipient['ids']) . ")");

					// unset fields that are just virtual
					unset($arrRecipient['c'], $arrRecipient['ids'], $arrRecipient['pids']);

					// update row
					$this->Database
						->prepare("UPDATE tl_avisota_recipient %s WHERE id=?")
						->set($arrRecipient)
						->execute($arrRecipient['id']);
				}

				// reload if there are more
				if ($objRecipient->numRows == 1000) {
					$this->reload();
				}
			}
		} catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade2_0_0_u1()', TL_ERROR);
			return false;
		}
		return true;
	}

	protected function check2_0_0_u2()
	{
		return $this->Database->tableExists('tl_avisota_newsletter_category')
			&& (!$this->Database->tableExists('tl_avisota_transport')
				|| !$this->Database->fieldExists('transportMode', 'tl_avisota_newsletter_category')
				|| $this->Database->execute("SELECT COUNT(id) AS c FROM tl_avisota_newsletter_category WHERE transportMode=''")->c > 0);
	}

	protected function upgrade2_0_0_u2()
	{
		try {
			if ($this->Database->tableExists('tl_avisota_newsletter_category')) {
				if (!$this->Database->tableExists('tl_avisota_transport')) {
					$this->Database->query("CREATE TABLE `tl_avisota_transport` (
					  `id` int(10) unsigned NOT NULL auto_increment,
					  `tstamp` int(10) unsigned NOT NULL default '0',
					  `type` varchar(255) NOT NULL default '',
					  `title` varchar(255) NOT NULL default '',
					  `sender` varchar(128) NOT NULL default '',
					  `senderName` varchar(128) NOT NULL default '',
					  `replyTo` varchar(128) NOT NULL default '',
					  `replyToName` varchar(128) NOT NULL default '',
					  `swiftUseSmtp` char(23) NOT NULL default '',
					  `swiftSmtpHost` varchar(255) NOT NULL default '',
					  `swiftSmtpUser` varchar(255) NOT NULL default '',
					  `swiftSmtpPass` varchar(255) NOT NULL default '',
					  `swiftSmtpEnc` char(3) NOT NULL default '',
					  `swiftSmtpPort` int(5) unsigned NOT NULL default '25',
					  PRIMARY KEY  (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8");
				}

				if (!$this->Database->fieldExists('transportMode', 'tl_avisota_newsletter_category')) {
					$this->Database->query("ALTER TABLE `tl_avisota_newsletter_category` ADD `transportMode` char(22) NOT NULL default ''");
				}

				if (!$this->Database->fieldExists('transport', 'tl_avisota_newsletter_category')) {
					$this->Database->query("ALTER TABLE `tl_avisota_newsletter_category` ADD `transport` int(10) unsigned NOT NULL default '0'");
				}

				if ($this->Database->fieldExists('useSMTP', 'tl_avisota_newsletter_category')) {
					$objCategory = $this->Database
						->execute("SELECT GROUP_CONCAT(id) AS ids, useSMTP, smtpHost, smtpUser, smtpPass, smtpPort, smtpEnc, sender, senderName
								   FROM tl_avisota_newsletter_category
								   WHERE transportMode=''
								   GROUP BY useSMTP, smtpHost, smtpUser, smtpPass, smtpPort, smtpEnc, sender, senderName");

					while ($objCategory->next()) {
						$arrTransport = array(
							'tstamp'        => time(),
							'type'          => 'swift',
							'title'         => 'Swift Transport' . ($objCategory->useSMTP ? (' (' . ($objCategory->smtpUser ? $objCategory->smtpUser . '@' : '') . $objCategory->smtpHost . ')') : ''),
							'swiftUseSmtp'  => $objCategory->useSMTP ? 'swiftSmtpOn' : 'swiftSmtpSystemSettings',
							'swiftSmtpHost' => $objCategory->smtpHost,
							'swiftSmtpUser' => $objCategory->smtpUser,
							'swiftSmtpPass' => $objCategory->smtpPass,
							'swiftSmtpEnc'  => $objCategory->smtpEnc,
							'sender'        => $objCategory->sender,
							'senderName'    => $objCategory->senderName
						);

						// create new transport
						$intId = $this->Database
							->prepare("INSERT INTO tl_avisota_transport %s")
							->set($arrTransport)
							->execute()
							->insertId;

						// update categories to use the transport
						$this->Database
							->query("UPDATE tl_avisota_newsletter_category SET transportMode='byCategory', transport=" . $intId . " WHERE id IN (" . $objCategory->ids . ")");
					}
				}
			}
		} catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade2_0_0_u2()', TL_ERROR);
			return false;
		}
		return true;
	}

	protected function check2_0_0_u3()
	{
		return $this->Database->tableExists('tl_avisota_newsletter') &&
			$this->Database->execute("SELECT COUNT(id) AS c FROM tl_avisota_newsletter WHERE recipients LIKE '%list-%' OR recipients LIKE '%mgroup-%'")->c > 0;
	}

	protected function upgrade2_0_0_u3()
	{
		try {
			if ($this->Database->tableExists('tl_avisota_recipient_list')) {
				if (!$this->Database->tableExists('tl_avisota_recipient_source')) {
					$this->Database->query("CREATE TABLE `tl_avisota_recipient_source` (
					  `id` int(10) unsigned NOT NULL auto_increment,
					  `sorting` int(10) unsigned NOT NULL default '0',
					  `tstamp` int(10) unsigned NOT NULL default '0',
					  `type` varchar(255) NOT NULL default '',
					  `title` varchar(255) NOT NULL default '',
					  `integratedBy` char(32) NOT NULL default '',
					  `integratedMailingLists` blob NULL,
					  `integratedAllowSingleListSelection` char(1) NOT NULL default '',
					  `integratedAllowSingleSelection` char(1) NOT NULL default '',
					  `integratedDetails` varchar(255) NOT NULL default '',
					  `integratedFilterByColumns` blob NULL,
					  `memberBy` char(32) NOT NULL default '',
					  `memberMailingLists` blob NULL,
					  `memberAllowSingleMailingListSelection` char(1) NOT NULL default '',
					  `memberGroups` blob NULL,
					  `memberAllowSingleGroupSelection` char(1) NOT NULL default '',
					  `memberAllowSingleSelection` char(1) NOT NULL default '',
					  `memberFilterByColumns` blob NULL,
					  `filter` char(1) NOT NULL default '',
					  `disable` char(1) NOT NULL default '',
					  PRIMARY KEY  (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8");
				}

				if (!$this->Database->fieldExists('recipientsMode', 'tl_avisota_newsletter_category')) {
					$this->Database->query("ALTER TABLE `tl_avisota_newsletter_category` ADD `recipientsMode` char(22) NOT NULL default ''");
				}
				if (!$this->Database->fieldExists('recipients', 'tl_avisota_newsletter_category')) {
					$this->Database->query("ALTER TABLE `tl_avisota_newsletter_category` ADD `recipients` blob NULL");
				}

				$arrSources = array();
				$arrSourcesByNewsletter = array();
				$arrNewslettersByCategory = array();
				$arrSourcesByCategory   = array();

				$objNewsletter = $this->Database
					->execute("SELECT id, pid, recipients
							   FROM tl_avisota_newsletter
							   WHERE recipients LIKE '%list-%' OR recipients LIKE '%mgroup-%'");
				while ($objNewsletter->next()) {
					if (!isset($arrNewslettersByCategory[$objNewsletter->pid])) {
						$arrNewslettersByCategory[$objNewsletter->pid] = array($objNewsletter->id);
					} else {
						$arrNewslettersByCategory[$objNewsletter->pid][] = $objNewsletter->id;
					}

					$arrRecipients = deserialize($objNewsletter->recipients, true);

					foreach ($arrRecipients as $strRecipient) {
						// create a new source, if none exists for this
						if (!isset($arrSources[$strRecipient])) {
							list($type, $id) = explode('-', $strRecipient, 2);

							switch ($type) {
								case 'list':
									$objList = $this->Database
										->prepare("SELECT title FROM tl_avisota_recipient_list WHERE id=?")
										->execute($id);
									if (!$objList->next()) {
										$this->log('Recipient list ID ' . $id . ' does not exists (anymore), skipping while convert into recipient source!', 'AvisotaUpdate::update2_0_0_u3()', TL_ERROR);
										continue;
									}
									$arrSource = array(
										'type' => 'integrated',
										'title' => $objList->title,
										'integratedBy' => 'integratedByMailingLists',
										'integratedMailingLists' => serialize(array($id)),
										'integratedDetails' => $GLOBALS['TL_CONFIG']['avisota_merge_member_details'] ? 'integrated_member_details' : 'integrated_details'
									);
									break;

								case 'mgroup':
									$objGroup = $this->Database
										->prepare("SELECT name FROM tl_member_group WHERE id=?")
										->execute($id);
									if (!$objGroup->next()) {
										$this->log('Member group ID ' . $id . ' does not exists (anymore), skipping while convert into recipient source!', 'AvisotaUpdate::update2_0_0_u3()', TL_ERROR);
										continue;
									}
									$arrSource = array(
										'type' => 'member',
										'title' => $objGroup->name,
										'memberBy' => 'memberByGroups',
										'memberGroups' => serialize(array($id))
									);
									break;

								default:
									$this->log('Unknown recipient type "' . $type . '", could not convert into recipient source!', 'AvisotaUpdate::update2_0_0_u3()', TL_ERROR);
									continue;
							}

							$arrSource['sorting'] = $this->Database
								->executeUncached('SELECT MAX(sorting) AS sorting FROM tl_avisota_recipient_source')
								->sorting;
							$arrSource['sorting'] = $arrSource['sorting'] ? $arrSource['sorting'] * 2 : 128;
							$arrSource['tstamp'] = time();

							$intId = $this->Database
								->prepare("INSERT INTO tl_avisota_recipient_source %s")
								->set($arrSource)
								->execute()
								->insertId;

							$arrSources[$strRecipient] = $intId;
						}
						else {
							$intId = $arrSources[$strRecipient];
						}

						// remember which newsletter use which source
						if (!isset($arrSourcesByNewsletter[$objNewsletter->id])) {
							$arrSourcesByNewsletter[$objNewsletter->id] = array($intId);
						} else {
							$arrSourcesByNewsletter[$objNewsletter->id][] = $intId;
						}
					}
				}

				// break down newsletter sources to category
				foreach ($arrNewslettersByCategory as $intCategoryId=>$arrNewsletterIds) {
					$arrSourcesByCategory[$intCategoryId] = array();

					foreach ($arrNewsletterIds as $intNewsletterId) {
						$tmp = $arrSourcesByNewsletter[$intNewsletterId];
						sort($tmp);
						$arrSourcesByCategory[$intCategoryId][] = implode(',', $tmp);
					}

					$arrSourcesByCategory[$intCategoryId] = array_unique($arrSourcesByCategory[$intCategoryId]);

					// all newsletters use the same sources
					if (count($arrSourcesByCategory[$intCategoryId]) == 1) {
						$tmp = explode(',', array_shift($arrSourcesByCategory[$intCategoryId]));
						foreach ($tmp as $k=>$v) {
							$tmp[$k] = $v . ':*';
						}
						$this->Database
							->prepare("UPDATE tl_avisota_newsletter_category SET recipientsMode=?, recipients=? WHERE id=?")
							->execute('byCategory', serialize($tmp), $intCategoryId);

						$this->Database
							->query("UPDATE tl_avisota_newsletter SET recipients='' WHERE id IN (" . implode(',', $arrNewsletterIds) . ")");
					}

					// every newsletter use its own source
					else {
						$this->Database
							->prepare("UPDATE tl_avisota_newsletter_category SET recipientsMode=? WHERE id=?")
							->execute('byNewsletter', $intCategoryId);

						// update each newsletter
						foreach ($arrNewsletterIds as $intNewsletterId) {
							$tmp = $arrSourcesByNewsletter[$intNewsletterId];
							foreach ($tmp as $k=>$v) {
								$tmp[$k] = $v . ':*';
							}
							$this->Database
								->prepare("UPDATE tl_avisota_newsletter SET recipients=? WHERE id=?")
								->execute(serialize($tmp), $intNewsletterId);
						}
					}
				}
			}
		} catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade2_0_0_u3()', TL_ERROR);
			return false;
		}
		return true;
	}

	public function hookMysqlMultiTriggerCreate($strTriggerName, $objTrigger, $return)
	{
		if ($objTrigger->table == 'tl_avisota_recipient') {
			$return['ALTER_CHANGE'][] = 'DELETE FROM tl_avisota_recipient_to_mailing_list';
			$return['ALTER_CHANGE'][] = 'INSERT INTO tl_avisota_recipient_to_mailing_list (recipient, list) SELECT r.id, l.id FROM tl_avisota_recipient r INNER JOIN tl_avisota_mailing_list l ON FIND_IN_SET(l.id, r.lists)';
		}

		if ($objTrigger->table == 'tl_member') {
			$return['ALTER_CHANGE'][] = 'DELETE FROM tl_member_to_mailing_list';
			$return['ALTER_CHANGE'][] = 'INSERT INTO tl_member_to_mailing_list (member, list) SELECT m.id, l.id FROM tl_member m INNER JOIN tl_avisota_mailing_list l ON FIND_IN_SET(l.id, m.avisota_lists)';
		}

		return $return;
	}
}
