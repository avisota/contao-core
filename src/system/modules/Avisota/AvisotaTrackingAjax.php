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
 * Class AvisotaTrackingAjax
 *
 * Parent class for newsletter content elements.
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaTrackingAjax extends Backend
{
	public function json_recipients($objNewsletter)
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
			if ($n++ > 0) {
				echo ",\n";
			}
			echo json_encode(array('value' => $objResultSet->recipient,
			                       'text'  => $objResultSet->recipient));
		}
		echo "\n" . ']';
		exit;
	}

	public function json_newsletter_flags()
	{
		$objResultSet = $this->Database
			->execute("SELECT * FROM tl_avisota_newsletter WHERE sendOn>0 ORDER BY sendOn");

		header('Content-Type: application/json');
		echo '[' . "\n";
		$n = 0;
		while ($objResultSet->next())
		{
			if ($n++ > 0) {
				echo ",\n";
			}
			echo '{' . "\n";
			echo '"x": ' . ($objResultSet->sendOn * 1000) . ",\n";
			echo '"title": "N",' . "\n";
			echo '"text": ' . json_encode('<b>' . $objResultSet->subject . '</b> ' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objResultSet->sendOn)) . "\n";
			echo '}' . "\n";
		}
		echo "\n" . ']';

		exit;
	}

	public function json_sends($objNewsletter, $strRecipient, $blnHighstockMode)
	{
		// collect newsletter/recipient, reads and reacts count
		if ($strRecipient) {
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
					ON r.pid=o.id
					WHERE o.pid=? AND r.send>0
					GROUP BY time
					ORDER BY time")
				->execute($objNewsletter->id);
		}
		$this->json_output($objResultSet, $blnHighstockMode);
	}

	public function json_reads($objNewsletter, $strRecipient, $blnHighstockMode)
	{
		// collect newsletter/recipient, reads and reacts count
		if ($strRecipient) {
			// total number of readed newsletters
			$objResultSet = $this->Database
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
		$this->json_output($objResultSet, $blnHighstockMode);
	}

	public function json_reacts($objNewsletter, $strRecipient, $blnHighstockMode)
	{
		// collect newsletter/recipient, reads and reacts count
		if ($strRecipient) {
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
		$this->json_output($objResultSet, $blnHighstockMode);
	}

	public function json_links($objNewsletter, $strRecipient, $blnHighstockMode)
	{
		// collect newsletter/recipient, reads and reacts count
		if ($strRecipient) {
			// total number of newsletters the recipients reacts on (clicked a link)
			$objLink  = $this->Database
				->prepare("SELECT id,url FROM tl_avisota_statistic_raw_recipient_link WHERE pid=? AND recipient=?")
				->execute($objNewsletter->id, $strRecipient);
			$strWhere = 'recipientLinkId';
		}
		else
		{
			// total number ov recipients that reacts on this newsletter (clicked a link)
			$objLink  = $this->Database
				->prepare("SELECT id,url FROM tl_avisota_statistic_raw_link WHERE pid=?")
				->execute($objNewsletter->id);
			$strWhere = 'linkId';
		}

		header('Content-Type: application/json');
		$n = 0;
		echo '[' . "\n";
		while ($objLink->next())
		{
			if ($n++ > 0) {
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
			$this->json_output_array($objResultSet, $blnHighstockMode);
			echo "\n" . '}';
		}
		echo "\n" . ']';

		exit;
	}

	public function json_output(Database_Result $objResultSet, $blnHighstockMode)
	{
		header('Content-Type: application/json');
		$this->json_output_array($objResultSet, $blnHighstockMode);
		exit;
	}

	public function json_output_array(Database_Result $objResultSet, $blnHighstockMode)
	{
		// highstock require local time, jqplot use utc time
		$intTimezoneOffset = $blnHighstockMode ? -$this->parseDate('Z', time()) : 0;
		echo '[' . "\n";
		$n    = 0;
		$sum  = 0;
		$time = -1;
		$continued = false;
		if ($objResultSet->numRows) {
			while ($objResultSet->next())
			{
				$sum += $objResultSet->sum;
				if (!$blnHighstockMode) {
					$temp = floor($objResultSet->time - ($objResultSet->time % (60)));
					if ($temp == $time) {
						$continued = true;
						continue;
					}
					$time = $temp;
				}
				else
				{
					$time = $objResultSet->time;
				}
				if ($n++ > 0) {
					echo ",\n";
				}
				echo '[' . (($time + $intTimezoneOffset) * 1000) . ',' . $sum . ']';
				$continued = false;
			}
			if ($continued) {
				if ($n++ > 0) {
					echo ",\n";
				}
				echo '[' . (($time + $intTimezoneOffset) * 1000) . ',' . $sum . ']';
			}
		}
		else
		{
			echo '[' . (time() * 1000) . ',0]';
		}
		echo "\n" . ']';
	}

	public function search_intersect($a, $b)
	{
		foreach ($a as $e)
		{
			if (in_array($e, $b)) {
				return true;
			}
		}
		return false;
	}
}
