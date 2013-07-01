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


/**
 * Class AvisotaUpdate
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class AvisotaUpdate extends BackendModule
{
	/**
	 * Updates
	 */
	public static $updates = array
	(
		'0.4.5'    => array('required' => true),
		'1.5.0'    => array('required' => true),
		'1.5.1'    => array('required' => true),
		'2.0.0-u1' => array('required' => true),
		'2.0.0-u2' => array(),
		'2.0.0-u3' => array()
	);

	/**
	 * @var AvisotaUpdate
	 */
	protected static $instance = null;

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new AvisotaUpdate();
		}
		return self::$instance;
	}

	/**
	 * @var Database
	 */
	protected $Database;


	/**
	 * Template file
	 *
	 * @var string
	 */
	protected $strTemplate = 'be_avisota_update';

	public function hasUpdates()
	{
		foreach (self::$updates as $version => $updates) {
			$methodName = 'check' . preg_replace('#[^\w]#', '_', $version);
			if ($this->$methodName()) {
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
				$versions = $this->Input->post('update');
				$version  = array_shift($versions);

				try {
					if ($this->runUpdate($version)) {
						$_SESSION['TL_INFO'][] = $GLOBALS['TL_LANG']['avisota_update']['updateSuccess'];
					}

					else {
						array_unshift($versions, $version);
						$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['avisota_update']['updateFailed'];
					}
				}
				catch (Exception $e) {
					array_unshift($versions, $version);
					$_SESSION['TL_ERROR'][] = $e->getMessage();
				}

				if (count($versions)) {
					$_SESSION['TL_INFO'][]       = $GLOBALS['TL_LANG']['avisota_update']['moreUpdates'];
					$_SESSION['AUTORUN_UPDATES'] = $versions;
				}

				else {
					unset($_SESSION['AUTORUN_UPDATES']);
				}
			}

			$this->reload();
		}

		if ($this->Environment->isAjaxRequest) {
			$version = $this->Input->get('update');

			if ($this->runUpdate($version)) {
				header('Content-Type: text/plain');
				echo $GLOBALS['TL_LANG']['avisota_update']['updateSuccess'];
				exit;
			}

			header("HTTP/1.0 500 Internal Server Error");
			header('Content-Type: text/plain');
			echo $GLOBALS['TL_LANG']['avisota_update']['updateFailed'];
			exit;
		}

		$GLOBALS['TL_JAVASCRIPT']['avisota_update'] = 'system/modules/avisota/assets/css/avisota_update.js';

		return parent::generate();
	}


	/**
	 * Compile the current element
	 */
	protected function compile()
	{
		$this->Template->updates = self::$updates;

		$versions = array();
		$statuses   = array();
		foreach (self::$updates as $version => $updates) {
			$methodName              = 'check' . preg_replace('#[^\w]#', '_', $version);
			$statuses[$version] = $this->$methodName();

			$shortVersion               = preg_replace('#^(\d+\.\d+\.\d+).*$#', '$1', $version);
			$versions[$shortVersion] = (isset($versions[$shortVersion]) ? $versions[$shortVersion]
				: false) || $statuses[$version];
		}
		$this->Template->status = $statuses;

		uksort($versions, 'version_compare');

		$lastVersion = '0.3.x';
		foreach ($versions as $version => $requireUpdate) {
			if ($requireUpdate) {
				break;
			}
			$lastVersion = $version;
		}
		$this->Template->previous = $lastVersion;
	}

	protected function runUpdate($version)
	{
		if (isset(self::$updates[$version])) {
			$methodName = 'upgrade' . preg_replace('#[^\w]#', '_', $version);
			return $this->$methodName();
		}

		$this->log('Try to run illegal update to version ' . $version . '!', 'AvisotaUpdate::update', TL_ERROR);
		throw new Exception('Try to run illegal update to version ' . $version . '!');
	}

	protected function check0_4_5()
	{
		return $this->Database->tableExists('orm_avisota_newsletter_content')
			&& !$this->Database->fieldExists('area', 'orm_avisota_newsletter_content');
	}

	/**
	 * Database upgrade to 0.4.5
	 */
	protected function upgrade0_4_5()
	{
		try {
			if ($this->Database->tableExists('orm_avisota_newsletter_content')) {
				if (!$this->Database->fieldExists('area', 'orm_avisota_newsletter_content')) {
					$this->Database
						->execute("ALTER TABLE orm_avisota_newsletter_content ADD area varchar(32) NOT NULL default ''");
				}

				$this->Database
					->prepare("UPDATE orm_avisota_newsletter_content SET area=? WHERE area=?")
					->execute('body', '');
			}
		}
		catch (Exception $e) {
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
		return $this->Database->tableExists('orm_avisota_newsletter_outbox')
			&& (!$this->Database->tableExists('orm_avisota_newsletter_outbox_recipient') ||
				!$this->Database->fieldExists('tstamp', 'orm_avisota_newsletter_outbox') ||
				$this->Database->fieldExists('token', 'orm_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('email', 'orm_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('send', 'orm_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('source', 'orm_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('failed', 'orm_avisota_newsletter_outbox'));
	}

	/**
	 * Database upgrade to 1.5.0
	 */
	protected function upgrade1_5_0()
	{
		try {
			if ($this->Database->tableExists('orm_avisota_newsletter_outbox')) {
				if (!$this->Database->tableExists('orm_avisota_newsletter_outbox_recipient')) {
					// create outbox recipient table
					$this->Database->execute(
						"CREATE TABLE `orm_avisota_newsletter_outbox_recipient` (
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
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
					);
				}

				// make sure the tstamp field exists
				if (!$this->Database->fieldExists('tstamp', 'orm_avisota_newsletter_outbox')) {
					$this->Database
						->execute(
						"ALTER TABLE orm_avisota_newsletter_outbox ADD tstamp int(10) unsigned NOT NULL default '0'"
					);
				}

				// split the outbox table data
				if ($this->Database->fieldExists('token', 'orm_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('email', 'orm_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('send', 'orm_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('source', 'orm_avisota_newsletter_outbox')
					&& $this->Database->fieldExists('failed', 'orm_avisota_newsletter_outbox')
				) {
					$outbox      = $this->Database
						->execute("SELECT DISTINCT pid,token FROM orm_avisota_newsletter_outbox");
					$newsletterDataSets = $outbox->fetchAllAssoc();

					if (count($newsletterDataSets)) {
						$outboxeIds = array();

						// create the outboxes
						foreach ($newsletterDataSets as $newsletterData) {
							if ($newsletterData['token']) {
								$time = $this->Database
									->prepare(
									"SELECT IF (tstamp, tstamp, send) as time FROM (SELECT MIN(tstamp) as tstamp, MIN(send) as send FROM orm_avisota_newsletter_outbox WHERE token=? GROUP BY token) t"
								)
									->execute($newsletterData['token'])
									->time;

								$outboxeIds[$newsletterData['token']] = $this->Database
									->prepare("INSERT INTO orm_avisota_newsletter_outbox SET pid=?, tstamp=?")
									->execute($newsletterData['pid'], $time)
									->insertId;
							}
						}

						// move the recipients
						foreach ($outboxeIds as $token => $outboxId) {
							$this->Database
								->prepare(
								"INSERT INTO orm_avisota_newsletter_outbox_recipient (pid,tstamp,email,domain,send,source,sourceID,failed)
									SELECT
										?,
										tstamp,
										email,
										SUBSTRING(email, LOCATE('@', email)+1) as domain,
										send,
										SUBSTRING(source, 1, LOCATE(':', source)-1) as source,
										SUBSTRING(source, LOCATE(':', source)+1) as sourceID,
										failed
									FROM orm_avisota_newsletter_outbox
									WHERE token=?"
							)
								->execute($outboxId, $token);
						}

						// update recipientID
						$recipient = $this->Database
							->execute("SELECT * FROM orm_avisota_newsletter_outbox_recipient WHERE recipientID=0");
						while ($recipient->next()) {
							switch ($recipient->source) {
								case 'list':
									$resultSet = $this->Database
										->prepare("SELECT id FROM orm_avisota_recipient WHERE email=? AND pid=?")
										->execute($recipient->email, $recipient->sourceID);
									break;

								case 'mgroup':
									$resultSet = $this->Database
										->prepare("SELECT id FROM tl_member WHERE email=?")
										->execute($recipient->email);
									break;

								default:
									continue;
							}

							if ($resultSet->next()) {
								$this->Database
									->prepare(
									"UPDATE orm_avisota_newsletter_outbox_recipient SET recipientID=? WHERE id=?"
								)
									->execute($resultSet->id, $recipient->id);
							}
						}

						// delete old entries from outbox
						$this->Database
							->execute(
							"DELETE FROM orm_avisota_newsletter_outbox WHERE id NOT IN (" . implode(
								',',
								$outboxeIds
							) . ")"
						);

						// delete old fields
						foreach (array('token', 'email', 'send', 'source', 'failed') as $fieldName) {
							if ($this->Database->fieldExists($fieldName, 'orm_avisota_newsletter_outbox')) {
								$this->Database->execute('ALTER TABLE orm_avisota_newsletter_outbox DROP ' . $fieldName);
							}
						}
					}
				}
			}
		}
		catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade_1_5_0', TL_ERROR);
			return false;
		}
		return true;
	}

	protected function check1_5_1()
	{
		return $this->Database->tableExists('orm_avisota_statistic_raw_recipient_link')
			&& !$this->Database->fieldExists('real_url', 'orm_avisota_statistic_raw_recipient_link')
			&& $this->Database->executeUncached(
				"SELECT * FROM orm_avisota_statistic_raw_recipient_link WHERE (real_url='' OR ISNULL(real_url)) AND url REGEXP 'email=[^…]' LIMIT 1"
			)->numRows
			|| $this->Database->tableExists('orm_avisota_statistic_raw_link')
				&& $this->Database->execute(
					"SELECT * FROM orm_avisota_statistic_raw_link WHERE url REGEXP '&#x?[0-9]+;' LIMIT 1"
				)->numRows;
	}

	/**
	 * Database upgrade to 1.5.1
	 */
	protected function upgrade1_5_1()
	{
		try {
			if ($this->Database->tableExists('orm_avisota_statistic_raw_recipient_link')) {
				$this->import('AvisotaStatic', 'Static');

				// make sure the real_url field exists
				if (!$this->Database->fieldExists('real_url', 'orm_avisota_statistic_raw_recipient_link')) {
					$this->Database
						->execute("ALTER TABLE orm_avisota_statistic_raw_recipient_link ADD real_url blob NULL");
				}

				// temporary caches
				$newsletterCache  = array();
				$categoryCache    = array();
				$unsubscribeCache = array();

				// links that are reduced
				$links = array();

				$link = $this->Database
					->executeUncached(
					"SELECT * FROM orm_avisota_statistic_raw_recipient_link WHERE (real_url='' OR ISNULL(real_url)) AND url REGEXP 'email=[^…]'"
				);
				while ($link->next()) {
					$newsletter     = false;
					$category       = false;
					$unsubscribeUrl = false;

					if (isset($newsletterCache[$link->pid])) {
						$newsletter = $newsletterCache[$link->pid];
					}
					else {
						$newsletter = $this->Database
							->prepare("SELECT * FROM orm_avisota_newsletter WHERE id=?")
							->execute($link->pid);
						if ($newsletter->next()) {
							$newsletter = $newsletterCache[$link->pid] = (object) $newsletter->row();
						}
						else {
							$newsletter = $newsletterCache[$link->pid] = false;
						}
					}

					if ($newsletter) {
						if (isset($categoryCache[$newsletter->pid])) {
							$category = $categoryCache[$newsletter->pid];
						}
						else {
							$category = $this->Database
								->prepare("SELECT * FROM orm_avisota_newsletter_category WHERE id=?")
								->execute($newsletter->pid);
							if ($category->next()) {
								$category = $categoryCache[$newsletter->pid] = (object) $category->row();
							}
							else {
								$category = $categoryCache[$newsletter->pid] = false;
							}
						}
					}

					if ($category) {
						if (isset($unsubscribeCache[$link->recipient])) {
							$unsubscribeUrl = $unsubscribeCache[$link->recipient];
						}
						else {
							$recipientData = array('email' => $link->recipient);
							$this->Static->set($category, $newsletter, $recipientData);
							$unsubscribeUrl = $unsubscribeCache[$link->recipient] = $this->replaceInsertTags(
								'{{newsletter::unsubscribe_url}}'
							);
						}
					}

					if ($unsubscribeUrl && $unsubscribeUrl == $link->url) {
						// create a new (real) url
						$realUrl = $link->url;
						$url     = preg_replace('#email=[^&]*#', 'email=…', $link->url);

						// update the recipient-less-link
						if (!$links[$url]) {
							$this->Database
								->prepare("UPDATE orm_avisota_statistic_raw_link SET url=? WHERE id=?")
								->execute($url, $link->linkID);
							$links[$url] = $link->linkID;
						}

						// or delete if there is allready a link with this url
						else {
							$this->Database
								->prepare("DELETE FROM orm_avisota_statistic_raw_link WHERE id=?")
								->execute($link->linkID);
						}

						// update the recipient-link
						$this->Database
							->prepare(
							"UPDATE orm_avisota_statistic_raw_recipient_link SET linkID=?, url=?, real_url=? WHERE id=?"
						)
							->execute($links[$url], $url, $realUrl, $link->id);

						// update link hit
						$this->Database
							->prepare(
							"UPDATE orm_avisota_statistic_raw_link_hit SET linkID=? WHERE linkID=? AND recipientLinkID=?"
						)
							->execute($links[$url], $link->linkID, $link->id);
					}
				}
			}
		}
		catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade_1_5_1()', TL_ERROR);
			return false;
		}

		try {
			if ($this->Database->tableExists('orm_avisota_statistic_raw_link')) {
				// cache url->id
				$cache = array();

				// find and clean html entities encoded urls
				$link = $this->Database->execute(
					"SELECT * FROM orm_avisota_statistic_raw_link WHERE url REGEXP '&#x?[0-9]+;'"
				);
				while ($link->next()) {
					// decorde url
					$url = html_entity_decode($link->url);

					// search cache
					if (isset($cache[$link->pid][$url])) {
						$linkId = $cache[$link->pid][$url];
					}

					// or search existing record
					else {
						$existingLink = $this->Database
							->prepare("SELECT * FROM orm_avisota_statistic_raw_link WHERE pid=? AND url=?")
							->executeUncached($link->pid, $url);

						if ($existingLink->next()) {
							// use existing record
							$linkId = $existingLink->id;
						}
						else {
							// insert new record
							$linkId = $this->Database
								->prepare("INSERT INTO orm_avisota_statistic_raw_link (pid,tstamp,url) VALUES (?, ?, ?)")
								->executeUncached($link->pid, $link->tstamp, $url)
								->insertId;
						}

						// set cache
						$cache[$link->pid][$url] = $linkId;
					}

					// update recipient link
					$this->Database
						->prepare("UPDATE orm_avisota_statistic_raw_recipient_link SET linkId=? WHERE linkId=?")
						->execute($linkId, $link->id);

					// delete old record
					$this->Database
						->prepare("DELETE FROM orm_avisota_statistic_raw_link WHERE id=?")
						->execute($link->id);

					$this->log('Cleaned html encoded url "' . $url . '"', 'AvisotaRunonce::upgrade1_5_1()', TL_INFO);
				}
			}
		}
		catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade1_5_1()', TL_ERROR);
			return false;
		}
		return true;
	}

	protected function check2_0_0_u1()
	{
		return $this->Database->tableExists('orm_avisota_recipient_list')
			&& !$this->Database->tableExists('orm_avisota_mailing_list')
			|| $this->Database->tableExists('orm_avisota_recipient_list')
				&& $this->Database->tableExists('orm_avisota_mailing_list')
				&& $this->Database->execute("SELECT COUNT(id) AS c FROM orm_avisota_recipient_list")->c > 0
				&& $this->Database->execute("SELECT COUNT(id) AS c FROM orm_avisota_mailing_list")->c == 0;
	}

	protected function upgrade2_0_0_u1()
	{
		try {
			if (!$this->Database->tableExists('orm_avisota_mailing_list')) {
				$this->Database->query(
					"CREATE TABLE `orm_avisota_mailing_list` (
					  `id` int(10) unsigned NOT NULL auto_increment,
					  `tstamp` int(10) unsigned NOT NULL default '0',
					  `title` varchar(255) NOT NULL default '',
					  `alias` varbinary(128) NOT NULL default '',
					  `viewOnlinePage` int(10) unsigned NOT NULL default '0',
					  PRIMARY KEY  (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8"
				);
			}
			if (!$this->Database->tableExists('orm_avisota_recipient_to_mailing_list')) {
				$this->Database->query(
					"CREATE TABLE `orm_avisota_recipient_to_mailing_list` (
					  `recipient` int(10) unsigned NOT NULL default '0',
					  `list` int(10) unsigned NOT NULL default '0',
					  `confirmationSent` int(10) unsigned NOT NULL default '0',
					  `reminderSent` int(10) unsigned NOT NULL default '0',
					  `reminderCount` int(1) unsigned NOT NULL default '0',
					  `confirmed` char(1) NOT NULL default '',
					  `token` char(8) NOT NULL default '',
					  PRIMARY KEY  (`recipient`, `list`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8"
				);
			}
			if ($this->Database->tableExists('orm_avisota_recipient_list')) {
				// create mailing lists from recipient lists
				$this->Database->query(
					"INSERT INTO orm_avisota_mailing_list (id, tstamp, title, alias, viewOnlinePage)
										SELECT id, tstamp, title, alias, viewOnlinePage FROM orm_avisota_recipient_list"
				);

				// insert subscriptions into relation table
				$this->Database->query(
					"INSERT INTO orm_avisota_recipient_to_mailing_list (recipient, list, confirmed, confirmationSent, reminderSent, reminderCount, token)
										SELECT id, pid, confirmed, addedOn, 0, IF(notification, notification, 0), token FROM orm_avisota_recipient"
				);

				// fetch recipients that are multiple
				$recipientDataSets = array();
				$recipient  = $this->Database
					->execute(
					"SELECT (SELECT COUNT(email) FROM orm_avisota_recipient r2 WHERE r1.email=r2.email) AS c, r1.*
							   FROM orm_avisota_recipient r1
							   HAVING c>1
							   ORDER BY email,tstamp
							   LIMIT 1000"
				);
				while ($recipient->next()) {
					// convert email to lowercase
					$recipient->email = strtolower($recipient->email);

					// set first existence
					if (!isset($recipientDataSets[$recipient->email])) {
						$recipientDataSets[$recipient->email]         = $recipient->row();
						$recipientDataSets[$recipient->email]['ids']  = array($recipient->id);
						$recipientDataSets[$recipient->email]['pids'] = array($recipient->pid);
					}

					// update fields
					else {
						$recipientData = & $recipientDataSets[$recipient->email];

						// delete duplicate recipient, but use its data
						if (in_array($recipient->pid, $recipientDataSets[$recipient->email]['pids'])) {
							$this->Database
								->prepare("DELETE FROM orm_avisota_recipient WHERE id=?")
								->execute($recipient->id);
						}
						else {
							$recipientData['ids'][]  = $recipient->id;
							$recipientData['pids'][] = $recipient->pid;
						}

						foreach ($recipient->row() as $field => $value) {
							// skip some fields
							if ($field == 'id' || $field == 'pid' || $field == 'tstamp' || $field == 'email' || $field == 'confirmed' || $field == 'token' || $field == 'notification') {
								continue;
							}

							// use the lowest value of addedOn
							else if ($field == 'addedOn') {
								if ($recipientData['addedOn'] > $value && $value > 0 || $recipientData['addedOn'] == 0) {
									$recipientData['addedOn'] = $value;
								}
							}

							// update value if previous value is empty or current value is newer
							else if (!empty($value) && (empty($recipientData[$field]) || $recipientData['tstamp'] < $recipient->tstamp)) {
								$recipientData[$field] = $value;
							}
						}

						if ($recipientData['tstamp'] < $recipient->tstamp) {
							$recipientData['tstamp'] = $recipient->tstamp;
						}
					}
				}
				foreach ($recipientDataSets as &$recipientData) {
					// update subscription
					$this->Database
						->query(
						"UPDATE orm_avisota_recipient_to_mailing_list
								 SET recipient=" . $recipientData['id'] . "
								 WHERE recipient IN (" . implode(',', $recipientData['ids']) . ")"
					);

					// delete waste rows
					$this->Database
						->query(
						"DELETE FROM orm_avisota_recipient
								 WHERE id!=" . $recipientData['id'] . " AND id IN (" . implode(
							',',
							$recipientData['ids']
						) . ")"
					);

					// unset fields that are just virtual
					unset($recipientData['c'], $recipientData['ids'], $recipientData['pids']);

					// update row
					$this->Database
						->prepare("UPDATE orm_avisota_recipient %s WHERE id=?")
						->set($recipientData)
						->execute($recipientData['id']);
				}

				// reload if there are more
				if ($recipient->numRows == 1000) {
					$this->reload();
				}
			}
		}
		catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade2_0_0_u1()', TL_ERROR);
			return false;
		}
		return true;
	}

	protected function check2_0_0_u2()
	{
		return $this->Database->tableExists('orm_avisota_newsletter_category')
			&& (!$this->Database->tableExists('orm_avisota_transport')
				|| !$this->Database->fieldExists('transportMode', 'orm_avisota_newsletter_category')
				|| $this->Database->execute(
					"SELECT COUNT(id) AS c FROM orm_avisota_newsletter_category WHERE transportMode=''"
				)->c > 0);
	}

	protected function upgrade2_0_0_u2()
	{
		try {
			if ($this->Database->tableExists('orm_avisota_newsletter_category')) {
				if (!$this->Database->tableExists('orm_avisota_transport')) {
					$this->Database->query(
						"CREATE TABLE `orm_avisota_transport` (
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
					) ENGINE=MyISAM DEFAULT CHARSET=utf8"
					);
				}

				if (!$this->Database->fieldExists('transportMode', 'orm_avisota_newsletter_category')) {
					$this->Database->query(
						"ALTER TABLE `orm_avisota_newsletter_category` ADD `transportMode` char(22) NOT NULL default ''"
					);
				}

				if (!$this->Database->fieldExists('transport', 'orm_avisota_newsletter_category')) {
					$this->Database->query(
						"ALTER TABLE `orm_avisota_newsletter_category` ADD `transport` int(10) unsigned NOT NULL default '0'"
					);
				}

				if ($this->Database->fieldExists('useSMTP', 'orm_avisota_newsletter_category')) {
					$category = $this->Database
						->execute(
						"SELECT GROUP_CONCAT(id) AS ids, useSMTP, smtpHost, smtpUser, smtpPass, smtpPort, smtpEnc, sender, senderName
								   FROM orm_avisota_newsletter_category
								   WHERE transportMode=''
								   GROUP BY useSMTP, smtpHost, smtpUser, smtpPass, smtpPort, smtpEnc, sender, senderName"
					);

					while ($category->next()) {
						$transport = array(
							'tstamp'        => time(),
							'type'          => 'swift',
							'title'         => 'Swift Transport' . ($category->useSMTP
								? (' (' . ($category->smtpUser
									? $category->smtpUser . '@' : '') . $category->smtpHost . ')') : ''),
							'swiftUseSmtp'  => $category->useSMTP ? 'swiftSmtpOn' : 'swiftSmtpSystemSettings',
							'swiftSmtpHost' => $category->smtpHost,
							'swiftSmtpUser' => $category->smtpUser,
							'swiftSmtpPass' => $category->smtpPass,
							'swiftSmtpEnc'  => $category->smtpEnc,
							'sender'        => $category->sender,
							'senderName'    => $category->senderName
						);

						// create new transport
						$transportId = $this->Database
							->prepare("INSERT INTO orm_avisota_transport %s")
							->set($transport)
							->execute()
							->insertId;

						// update categories to use the transport
						$this->Database
							->query(
							"UPDATE orm_avisota_newsletter_category SET transportMode='byCategory', transport=" . $transportId . " WHERE id IN (" . $category->ids . ")"
						);
					}
				}
			}
		}
		catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade2_0_0_u2()', TL_ERROR);
			return false;
		}
		return true;
	}

	protected function check2_0_0_u3()
	{
		return $this->Database->tableExists('orm_avisota_newsletter') &&
			$this->Database->execute(
				"SELECT COUNT(id) AS c FROM orm_avisota_newsletter WHERE recipients LIKE '%list-%' OR recipients LIKE '%mgroup-%'"
			)->c > 0;
	}

	protected function upgrade2_0_0_u3()
	{
		try {
			if ($this->Database->tableExists('orm_avisota_recipient_list')) {
				if (!$this->Database->tableExists('orm_avisota_recipient_source')) {
					$this->Database->query(
						"CREATE TABLE `orm_avisota_recipient_source` (
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
					) ENGINE=MyISAM DEFAULT CHARSET=utf8"
					);
				}

				if (!$this->Database->fieldExists('recipientsMode', 'orm_avisota_newsletter_category')) {
					$this->Database->query(
						"ALTER TABLE `orm_avisota_newsletter_category` ADD `recipientsMode` char(22) NOT NULL default ''"
					);
				}
				if (!$this->Database->fieldExists('recipients', 'orm_avisota_newsletter_category')) {
					$this->Database->query("ALTER TABLE `orm_avisota_newsletter_category` ADD `recipients` blob NULL");
				}

				$sources               = array();
				$sourcesByNewsletter   = array();
				$newslettersByCategory = array();
				$sourcesByCategory     = array();

				$newsletter = $this->Database
					->execute(
					"SELECT id, pid, recipients
							   FROM orm_avisota_newsletter
							   WHERE recipients LIKE '%list-%' OR recipients LIKE '%mgroup-%'"
				);
				while ($newsletter->next()) {
					if (!isset($newslettersByCategory[$newsletter->pid])) {
						$newslettersByCategory[$newsletter->pid] = array($newsletter->id);
					}
					else {
						$newslettersByCategory[$newsletter->pid][] = $newsletter->id;
					}

					$recipients = deserialize($newsletter->recipients, true);

					foreach ($recipients as $recipientIdentifier) {
						// create a new source, if none exists for this
						if (!isset($sources[$recipientIdentifier])) {
							list($type, $id) = explode('-', $recipientIdentifier, 2);

							switch ($type) {
								case 'list':
									$list = $this->Database
										->prepare("SELECT title FROM orm_avisota_recipient_list WHERE id=?")
										->execute($id);
									if (!$list->next()) {
										$this->log(
											'Recipient list ID ' . $id . ' does not exists (anymore), skipping while convert into recipient source!',
											'AvisotaUpdate::update2_0_0_u3()',
											TL_ERROR
										);
										continue;
									}
									$sourceData = array(
										'type'                   => 'integrated',
										'title'                  => $list->title,
										'integratedBy'           => 'integratedByMailingLists',
										'integratedMailingLists' => serialize(array($id)),
										'integratedDetails'      => $GLOBALS['TL_CONFIG']['avisota_merge_member_details']
											? 'integrated_member_details' : 'integrated_details'
									);
									break;

								case 'mgroup':
									$group = $this->Database
										->prepare("SELECT name FROM tl_member_group WHERE id=?")
										->execute($id);
									if (!$group->next()) {
										$this->log(
											'Member group ID ' . $id . ' does not exists (anymore), skipping while convert into recipient source!',
											'AvisotaUpdate::update2_0_0_u3()',
											TL_ERROR
										);
										continue;
									}
									$sourceData = array(
										'type'         => 'member',
										'title'        => $group->name,
										'memberBy'     => 'memberByGroups',
										'memberGroups' => serialize(array($id))
									);
									break;

								default:
									$this->log(
										'Unknown recipient type "' . $type . '", could not convert into recipient source!',
										'AvisotaUpdate::update2_0_0_u3()',
										TL_ERROR
									);
									continue;
							}

							$sourceData['sorting'] = $this->Database
								->executeUncached('SELECT MAX(sorting) AS sorting FROM orm_avisota_recipient_source')
								->sorting;
							$sourceData['sorting'] = $sourceData['sorting'] ? $sourceData['sorting'] * 2 : 128;
							$sourceData['tstamp']  = time();

							$sourceId = $this->Database
								->prepare("INSERT INTO orm_avisota_recipient_source %s")
								->set($sourceData)
								->execute()
								->insertId;

							$sources[$recipientIdentifier] = $sourceId;
						}
						else {
							$sourceId = $sources[$recipientIdentifier];
						}

						// remember which newsletter use which source
						if (!isset($sourcesByNewsletter[$newsletter->id])) {
							$sourcesByNewsletter[$newsletter->id] = array($sourceId);
						}
						else {
							$sourcesByNewsletter[$newsletter->id][] = $sourceId;
						}
					}
				}

				// break down newsletter sources to category
				foreach ($newslettersByCategory as $categoryId => $newsletterIds) {
					$sourcesByCategory[$categoryId] = array();

					foreach ($newsletterIds as $newsletterId) {
						$tmp = $sourcesByNewsletter[$newsletterId];
						sort($tmp);
						$sourcesByCategory[$categoryId][] = implode(',', $tmp);
					}

					$sourcesByCategory[$categoryId] = array_unique($sourcesByCategory[$categoryId]);

					// all newsletters use the same sources
					if (count($sourcesByCategory[$categoryId]) == 1) {
						$tmp = explode(',', array_shift($sourcesByCategory[$categoryId]));
						foreach ($tmp as $k => $v) {
							$tmp[$k] = $v . ':*';
						}
						$this->Database
							->prepare(
							"UPDATE orm_avisota_newsletter_category SET recipientsMode=?, recipients=? WHERE id=?"
						)
							->execute('byCategory', serialize($tmp), $categoryId);

						$this->Database
							->query(
							"UPDATE orm_avisota_newsletter SET recipients='' WHERE id IN (" . implode(
								',',
								$newsletterIds
							) . ")"
						);
					}

					// every newsletter use its own source
					else {
						$this->Database
							->prepare("UPDATE orm_avisota_newsletter_category SET recipientsMode=? WHERE id=?")
							->execute('byNewsletter', $categoryId);

						// update each newsletter
						foreach ($newsletterIds as $newsletterId) {
							$tmp = $sourcesByNewsletter[$newsletterId];
							foreach ($tmp as $k => $v) {
								$tmp[$k] = $v . ':*';
							}
							$this->Database
								->prepare("UPDATE orm_avisota_newsletter SET recipients=? WHERE id=?")
								->execute(serialize($tmp), $newsletterId);
						}
					}
				}
			}
		}
		catch (Exception $e) {
			$this->log($e->getMessage() . "\n" . $e->getTraceAsString(), 'AvisotaRunonce::upgrade2_0_0_u3()', TL_ERROR);
			return false;
		}
		return true;
	}

	public function hookMysqlMultiTriggerCreate($triggerName, $trigger, $return)
	{
		if ($trigger->table == 'orm_avisota_recipient') {
			$return['ALTER_CHANGE'][] = 'DELETE FROM orm_avisota_recipient_to_mailing_list';
			$return['ALTER_CHANGE'][] = 'INSERT INTO orm_avisota_recipient_to_mailing_list (recipient, list) SELECT r.id, l.id FROM orm_avisota_recipient r INNER JOIN orm_avisota_mailing_list l ON FIND_IN_SET(l.id, r.lists)';
		}

		if ($trigger->table == 'tl_member') {
			$return['ALTER_CHANGE'][] = 'DELETE FROM tl_member_to_mailing_list';
			$return['ALTER_CHANGE'][] = 'INSERT INTO tl_member_to_mailing_list (member, list) SELECT m.id, l.id FROM tl_member m INNER JOIN orm_avisota_mailing_list l ON FIND_IN_SET(l.id, m.avisota_lists)';
		}

		return $return;
	}
}
