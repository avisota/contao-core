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
class AvisotaTracking extends BackendModule
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_avisota_tracking';


	protected $blnUseHighstock = false;


	public function compile()
	{
		$this->loadLanguageFile('avisota_tracking');

		# load the session settings
		$intNewsletter = $this->Session->get('AVISOTA_TRACKING');
		$strRecipient = '';

		# evaluate the post and get parameters
		if ($this->Input->post('newsletter'))
		{
			$intNewsletter = $this->Input->post('newsletter');
		}
		else if ($this->Input->get('newsletter'))
		{
			$intNewsletter = $this->Input->get('newsletter');
		}
		if ($this->Input->post('recipient'))
		{
			$strRecipient = urldecode($this->Input->post('recipient'));
		}
		else if ($this->Input->get('recipient'))
		{
			$strRecipient = urldecode($this->Input->get('recipient'));
		}

		# where statement, if the newsletters have to filter by a specific recipient
		$strWhere = '';

		$objRecipient = $this->Database
			->prepare("SELECT * FROM tl_avisota_statistic_raw_recipient WHERE recipient=?")
			->limit(1)
			->execute($strRecipient);
		if (!$objRecipient->numRows)
		{
			$strRecipient = '';
		}

		# collect read state and build where statement for a specific recipient
		else
		{
			$arrIds = $objRecipient->fetchEach('pid');
			if (count($arrIds))
			{
				$strWhere = ' AND id IN (' . implode(',', $arrIds) . ')';
			}
			else
			{
				$strWhere = ' AND id=0';
			}
		}

		# read all available newsletters (if set, only for a specific recipient)
		$arrNewsletters = array();
		$objNewsletters = $this->Database->execute("SELECT * FROM tl_avisota_newsletter WHERE sendOn!='' $strWhere ORDER BY sendOn DESC");
		while ($objNewsletters->next())
		{
			$arrNewsletters[$objNewsletters->id] = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objNewsletters->sendOn) . ' ' . $objNewsletters->subject;
		}

		// cancel, if no newsletters
		if (count($arrNewsletters) == 0)
		{
			$this->Template->empty = true;
			return;
		}

		# find last sended newsletter
		if (!$intNewsletter)
		{
			$arrIds = array_keys($arrNewsletters);
			$intNewsletter = array_shift($arrIds);
		}

		$objNewsletter = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")->execute($intNewsletter);
		if ($objNewsletter->next())
		{
			$this->Template->newsletter = $objNewsletter->row();
		}

		// Newsletter does not exists, use another one and reload
		else
		{
			$arrIds = array_keys($arrNewsletters);
			$intNewsletter = array_shift($arrIds);
			$this->Session->set('AVISOTA_TRACKING', $intNewsletter);
			$this->reload();
		}

		$this->blnUseHighstock = ($GLOBALS['TL_CONFIG']['avisota_chart_highstock'] && $GLOBALS['TL_CONFIG']['avisota_chart_highstock_confirmed']) ? true : false;
		$this->Template->chart = $this->blnUseHighstock ? 'highstock' : 'jqplot';

		if ($this->Input->get('data'))
		{
			switch ($this->Input->get('data'))
			{
			case 'recipients':
				$this->json_recipients($objNewsletter);

			case 'flags':
				$this->json_newsletter_flags();

			case 'sends':
				$this->json_sends($objNewsletter, $strRecipient);

			case 'reads':
				$this->json_reads($objNewsletter, $strRecipient);

			case 'reacts':
				$this->json_reacts($objNewsletter, $strRecipient);

			case 'links':
				$this->json_links($objNewsletter, $strRecipient);

			default:
				exit;
			}
		}

		# collect links hits
		$arrLinks = array();
		if ($strRecipient)
		{
			$objLink = $this->Database->prepare("SELECT url,(SELECT COUNT(id) FROM tl_avisota_statistic_raw_link_hit h WHERE l.id=h.recipientLinkID) as hits FROM tl_avisota_statistic_raw_recipient_link l WHERE pid=? AND recipient=? ORDER BY hits DESC")->execute($intNewsletter, $strRecipient);
		}
		else
		{
			$objLink = $this->Database->prepare("SELECT url,SUM(hits) as hits FROM (SELECT url,(SELECT COUNT(id) FROM tl_avisota_statistic_raw_link_hit h WHERE l.id=h.linkID) as hits FROM tl_avisota_statistic_raw_link l WHERE pid=?) t GROUP BY url ORDER BY hits DESC")->execute($intNewsletter);
		}
		$arrLinks = $objLink->fetchAllAssoc();
		$intHits = array_sum($objLink->fetchEach('hits'));
		for ($i=0; $i<count($arrLinks); $i++)
		{
			$arrLinks[$i]['percent'] = $intHits > 0 ? intval($arrLinks[$i]['hits']/$intHits*100) : 0;
		}
		$this->Template->links = $arrLinks;

		if ($strRecipient)
		{
			$objRead = $this->Database
				->prepare("SELECT n.id, n.subject, r.readed
					FROM tl_avisota_statistic_raw_recipient r
					INNER JOIN tl_avisota_newsletter n
					ON n.id=r.pid
					WHERE r.recipient=? AND r.readed=?")
				->execute($strRecipient, 1);
			$this->Template->newsletter_reads = $objRead->fetchAllAssoc();
		}
		else
		{
			$this->Template->newsletter_reads = false;
		}

		$this->Template->mode = ($strRecipient) ? 'recipient' : 'newsletter';
		$this->Template->newsletters = $arrNewsletters;
		$this->Template->recipients = $arrRecipients;
		$this->Template->recipient = $strRecipient;

		$this->Session->set('AVISOTA_TRACKING', $intNewsletter);
	}

	protected function json_recipients($objNewsletter)
	{
		$objResultSet = $this->Database
			->prepare("SELECT recipient
				FROM tl_avisota_statistic_raw_recipient
				WHERE pid=? AND recipient LIKE ?
				ORDER BY recipient")
			->limit($this->Input->get('limit') ? $this->Input->get('limit') : 20)
			->execute($objNewsletter->id, '%' . $this->Input->get('q') . '%');

		header('Content-Type: application/json');
		echo '[' . "\n";
		$n = 0;
		while ($objResultSet->next())
		{
			if ($n++ > 0)
			{
				echo ",\n";
			}
			echo json_encode(array('value' => $objResultSet->recipient, 'text' => $objResultSet->recipient));
		}
		echo "\n" . ']';
		exit;
	}

	protected function json_newsletter_flags()
	{
		$objResultSet = $this->Database
			->execute("SELECT * FROM tl_avisota_newsletter WHERE sendOn>0 ORDER BY sendOn");

		header('Content-Type: application/json');
		echo '[' . "\n";
		$n = 0;
		while ($objResultSet->next())
		{
			if ($n++ > 0)
			{
				echo ",\n";
			}
			echo '{' . "\n";
			echo '"x": ' . ($objResultSet->sendOn*1000) . ",\n";
			echo '"title": "N",' . "\n";
			echo '"text": ' . json_encode('<b>' . $objResultSet->subject . '</b> ' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objResultSet->sendOn)) . "\n";
			echo '}' . "\n";
		}
		echo "\n" . ']';

		exit;
	}

	protected function json_sends($objNewsletter, $strRecipient)
	{
		// collect newsletter/recipient, reads and reacts count
		if ($strRecipient)
		{
			// total number of recived newsletters
			$objResultSet = $this->Database
				->prepare("SELECT r.send as time, COUNT(r.id) as sum
					FROM tl_avisota_newsletter_outbox_recipient r
					WHERE r.email=? AND r.send>0
					GROUP BY time
					ORDER BY time")
				->execute($strRecipient);
		}
		else
		{
			// total number of recipients for this newsletter
			$objResultSet = $this->Database
				->prepare("SELECT r.send as time, COUNT(r.id) as sum
					FROM tl_avisota_newsletter_outbox_recipient r
					INNER JOIN tl_avisota_newsletter_outbox o
					WHERE o.pid=? AND r.send>0
					GROUP BY time
					ORDER BY time")
				->execute($objNewsletter->id);
		}
		$this->json_output($objResultSet);
	}

	protected function json_reads($objNewsletter, $strRecipient)
	{
		// collect newsletter/recipient, reads and reacts count
		if ($strRecipient)
		{
			// total number of readed newsletters
			$objResultSet  = $this->Database
				->prepare("SELECT tstamp as time, COUNT(id) as sum
					FROM tl_avisota_statistic_raw_recipient
					WHERE recipient=? AND readed=?
					GROUP BY time
					ORDER BY time")
				->execute($strRecipient, 1);
		}
		else
		{
			// total number of recipients that reads this newsletter
			$objResultSet = $this->Database
				->prepare("SELECT tstamp as time, COUNT(id) as sum
					FROM tl_avisota_statistic_raw_recipient
					WHERE pid=? AND readed=?
					GROUP BY time
					ORDER BY time")
				->execute($objNewsletter->id, 1);
		}
		$this->json_output($objResultSet);
	}

	protected function json_reacts($objNewsletter, $strRecipient)
	{
		// collect newsletter/recipient, reads and reacts count
		if ($strRecipient)
		{
			// total number of newsletters the recipients reacts on (clicked a link)
			$objResultSet = $this->Database
				->prepare("SELECT time, SUM(sum) as sum
					FROM (
						SELECT MIN(tstamp) as time, 1 as sum
						FROM tl_avisota_statistic_raw_link_hit
						WHERE recipient=?
						GROUP BY recipient
					) t
					GROUP BY time")
				->execute($strRecipient);
		}
		else
		{
			// total number ov recipients that reacts on this newsletter (clicked a link)
			$objResultSet = $this->Database
				->prepare("SELECT time, SUM(sum) as sum
					FROM (
						SELECT MIN(tstamp) as time, 1 as sum
						FROM tl_avisota_statistic_raw_link_hit
						WHERE pid=?
						GROUP BY linkID,recipientLinkID
					) t
					GROUP BY time")
				->execute($objNewsletter->id);
		}
		$this->json_output($objResultSet);
	}

	protected function json_links($objNewsletter, $strRecipient)
	{
		// collect newsletter/recipient, reads and reacts count
		if ($strRecipient)
		{
			// total number of newsletters the recipients reacts on (clicked a link)
			$objLink = $this->Database
				->prepare("SELECT id,url FROM tl_avisota_statistic_raw_recipient_link WHERE pid=? AND recipient=?")
				->execute($objNewsletter->id, $strRecipient);
			$strWhere = 'recipientLinkId';
		}
		else
		{
			// total number ov recipients that reacts on this newsletter (clicked a link)
			$objLink = $this->Database
				->prepare("SELECT id,url FROM tl_avisota_statistic_raw_link WHERE pid=?")
				->execute($objNewsletter->id);
			$strWhere = 'linkId';
		}

		header('Content-Type: application/json');
		$n = 0;
		echo '[' . "\n";
		while ($objLink->next())
		{
			if ($n++ > 0)
			{
				echo ',' . "\n";
			}
			echo '{' . "\n";
			echo '"name": ' . json_encode($objLink->url) . ',' . "\n";
			echo '"data": ';
			$objResultSet = $this->Database
				->prepare("SELECT tstamp as time,COUNT(tstamp) as sum
					FROM tl_avisota_statistic_raw_link_hit
					WHERE $strWhere=?
					GROUP BY time")
				->execute($objLink->id);
			$this->json_output_array($objResultSet);
			echo "\n" . '}';
		}
		echo "\n" . ']';

		exit;
	}

	protected function json_output(Database_Result $objResultSet)
	{
		header('Content-Type: application/json');
		$this->json_output_array($objResultSet);
		exit;
	}

	protected function json_output_array(Database_Result $objResultSet)
	{
		// highstock require local time, jqplot use utc time
		$intTimezoneOffset = $this->blnUseHighstock ? $this->parseDate('Z', time()) : 0;
		echo '[' . "\n";
		$n = 0;
		$sum = 0;
		if ($objResultSet->numRows)
		{
			while ($objResultSet->next())
			{
				$sum += $objResultSet->sum;
				if ($n++ > 0)
				{
					echo ",\n";
				}
				echo '[' . (($objResultSet->time + $intTimezoneOffset) * 1000) . ',' . $sum . ']';
			}
		}
		else
		{
			echo '[' . (time() * 1000) . ',0]';
		}
		echo "\n" . ']';
	}

	protected function search_intersect($a, $b)
	{
		foreach ($a as $e)
		{
			if (in_array($e, $b))
			{
				return true;
			}
		}
		return false;
	}
}
