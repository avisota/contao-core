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
 * Class AvisotaRunonce
 *
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaRunonce extends Controller
{

	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->import('Database');
	}


	public function run()
	{
		$this->upgrade0_4_5();
		$this->upgrade1_5_0();
	}


	/**
	 * Database upgrade to 0.4.5
	 */
	protected function upgrade0_4_5()
	{
		if (!$this->Database->fieldExists('area', 'tl_avisota_newsletter_content'))
		{
			$this->Database
				->execute("ALTER TABLE tl_avisota_newsletter_content ADD area varchar(32) NOT NULL default ''");
		}

		$this->Database
			->prepare("UPDATE tl_avisota_newsletter_content SET area=? WHERE area=?")->execute('body', '');
	}


	/**
	 * Database upgrade to 1.5.0
	 */
	protected function upgrade1_5_0()
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
		if (   $this->Database->fieldExists('email',  'tl_avisota_newsletter_outbox')
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
					$time = $this->Database
						->prepare("SELECT MIN(tstamp) as tstamp FROM tl_avisota_newsletter_outbox WHERE token=?")
						->execute($arrRow['token'])
						->tstamp;

					$arrOutboxes[$arrRow['token']] = $this->Database
						->prepare("INSERT INTO tl_avisota_newsletter_outbox SET pid=?, tstamp=?")
						->execute($arrRow['pid'], $time)
						->insertId;
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
					->prepare("SELECT * FROM tl_avisota_newsletter_outbox_recipient WHERE recipientID=0");
				while ($objRecipient->next())
				{
					switch ($objRecipient->list)
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
			}
		}
	}
}

/**
 * Instantiate controller
 */
$objAvisotaRunonce = new AvisotaRunonce();
$objAvisotaRunonce->run();

?>