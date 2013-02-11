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
 * Class AvisotaBackendChartPChart
 *
 * Parent class for newsletter content elements.
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBackendChartPChart extends Backend implements AvisotaBackendChart
{
	/**
	 *
	 * @param Database_Result $objNewsletter
	 * @param string $strRecipient
	 */
	public function handleAjax(Database_Result $objNewsletter, $strRecipient)
	{
	}

	/**
	 *
	 * @param Database_Result $objNewsletter
	 * @param string $strRecipient
	 */
	public function generateChart(Database_Result $objNewsletter, $strRecipient)
	{
		switch ($this->Input->get('chart')) {
			case 'sends':
				$this->chartSends($objNewsletter, $strRecipient);
				break;
			case 'links':
				$this->chartLinks($objNewsletter, $strRecipient);
				break;
		}

		$objTemplate = new BackendTemplate('be_avisota_chart_pchart');

		// collect sends
		if ($strRecipient) {
			// total number of recived newsletters
			$objSends = $this->Database
				->prepare("SELECT COUNT(r.send) as sum
						   FROM tl_avisota_newsletter_outbox_recipient r
						   WHERE r.email=? AND r.send>0")
				->execute($strRecipient);
		}
		else
		{
			// total number of recipients for this newsletter
			$objSends = $this->Database
				->prepare("SELECT COUNT(r.send) as sum
						   FROM tl_avisota_newsletter_outbox_recipient r
						   INNER JOIN tl_avisota_newsletter_outbox o
						   ON r.pid=o.id
						   WHERE o.pid=? AND r.send>0")
				->execute($objNewsletter->id);
		}

		// collect reads
		if ($strRecipient) {
			// total number of readed newsletters
			$objReads = $this->Database
				->prepare("SELECT COUNT(tstamp) as sum
						   FROM tl_avisota_statistic_raw_recipient
						   WHERE recipient=? AND readed=?")
				->execute($strRecipient, 1);
		}
		else
		{
			// total number of recipients that reads this newsletter
			$objReads = $this->Database
				->prepare("SELECT COUNT(tstamp) as sum
						   FROM tl_avisota_statistic_raw_recipient
						   WHERE pid=? AND readed=?")
				->execute($objNewsletter->id, 1);
		}

		// collect reacts
		if ($strRecipient) {
			// total number of newsletters the recipients reacts on (clicked a link)
			$objReacts = $this->Database
				->prepare("SELECT SUM(sum) as sum
						   FROM (
						       SELECT MIN(tstamp) as time, 1 as sum
							   FROM tl_avisota_statistic_raw_link_hit
							   WHERE recipient=?
							   GROUP BY recipient
						   ) t")
				->execute($strRecipient);
		}
		else
		{
			// total number ov recipients that reacts on this newsletter (clicked a link)
			$objReacts = $this->Database
				->prepare("SELECT SUM(sum) as sum
						   FROM (
							   SELECT MIN(tstamp) as time, 1 as sum
							   FROM tl_avisota_statistic_raw_link_hit
							   WHERE pid=?
							   GROUP BY linkID,recipientLinkID
						   ) t")
				->execute($objNewsletter->id);
		}

		# collect links hits
		if ($strRecipient) {
			$objLink = $this->Database
				->prepare("SELECT url, (SELECT COUNT(id) FROM tl_avisota_statistic_raw_link_hit h WHERE l.id=h.recipientLinkID) as hits
						   FROM tl_avisota_statistic_raw_recipient_link l
						   WHERE pid=? AND recipient=?
						   ORDER BY hits DESC")
				->execute($objNewsletter->id, $strRecipient);
		}
		else
		{
			$objLink = $this->Database
				->prepare("SELECT url, SUM(hits) as hits FROM
						   (
						       SELECT url, (SELECT COUNT(id) FROM tl_avisota_statistic_raw_link_hit h WHERE l.id=h.linkID) as hits
						       FROM tl_avisota_statistic_raw_link l
						       WHERE pid=?
						   ) t
						   GROUP BY url
						   ORDER BY hits DESC")
				->execute($objNewsletter->id);
		}
		$arrLinks = $objLink->fetchAllAssoc();

		$intHits = array_sum($objLink->fetchEach('hits'));
		for ($i = 0; $i < count($arrLinks); $i++)
		{
			$arrLinks[$i]['percent'] = $intHits > 0 ? intval($arrLinks[$i]['hits'] / $intHits * 100) : 0;
		}
		$objTemplate->links = $arrLinks;

		if ($strRecipient) {
			$objRead                       = $this->Database
				->prepare("SELECT n.id, n.subject, r.readed
						   FROM tl_avisota_statistic_raw_recipient r
						   INNER JOIN tl_avisota_newsletter n
						   ON n.id=r.pid
						   WHERE r.recipient=? AND r.readed=?")
				->execute($strRecipient, 1);
			$objTemplate->newsletter_reads = $objRead->fetchAllAssoc();
		}
		else
		{
			$objTemplate->newsletter_reads = false;
		}

		$objTemplate->timespan = $this->getTstamp($objNewsletter, $strRecipient);

		$objTemplate->sends  = $objSends->sum;
		$objTemplate->reads  = $objReads->sum;
		$objTemplate->reacts = $objReacts->sum;

		$objTemplate->mode       = ($strRecipient) ? 'recipient' : 'newsletter';
		$objTemplate->newsletter = $objNewsletter->row();
		$objTemplate->recipient  = $strRecipient;

		return $objTemplate->parse();
	}

	protected function getTstamp($objNewsletter, $strRecipient)
	{
		if ($strRecipient) {
			$tstamp = $this->Database
				->prepare("SELECT MIN(tstamp) as `min`, MAX(tstamp) as `max` FROM
						   (
						       SELECT tstamp
							   FROM tl_avisota_newsletter_outbox_recipient r
							   WHERE r.email=? AND r.send>0
						   UNION
						       SELECT tstamp
							   FROM tl_avisota_statistic_raw_recipient
							   WHERE recipient=? AND readed=?
						   UNION
						       SELECT tstamp
							   FROM tl_avisota_statistic_raw_link_hit
							   WHERE recipient=?
						   ) t")
				->execute($strRecipient, $strRecipient, 1, $strRecipient)
				->fetchAssoc();
		} else {
			$tstamp = $this->Database
				->prepare("SELECT MIN(tstamp) as `min`, MAX(tstamp) as `max` FROM
						   (
						       SELECT r.tstamp
						       FROM tl_avisota_newsletter_outbox_recipient r
						       INNER JOIN tl_avisota_newsletter_outbox o
						       ON r.pid=o.id
						       WHERE o.pid=? AND r.send>0
						   UNION
						       SELECT tstamp
						       FROM tl_avisota_statistic_raw_recipient
						       WHERE pid=? AND readed=?
						   UNION
						       SELECT MIN(tstamp)
						       FROM tl_avisota_statistic_raw_link_hit
						       WHERE pid=?
						       GROUP BY recipient
						   ) t")
				->execute($objNewsletter->id, $objNewsletter->id, 1, $objNewsletter->id)
				->fetchAssoc();
		}
		if (!$tstamp['min']) {
			$tstamp['min'] = 0;
		}
		if (!$tstamp['max']) {
			$tstamp['max'] = 0;
		}
		return $tstamp;
	}

	protected function getUpScale()
	{
		$args = func_get_args();
		$max = 0;
		foreach ($args as $arg) {
			$max = max($max, $arg);
		}
		$length = strlen($max);
		$div = pow(10, $length-1);
		return ceil($max / $div) * $div;
	}

	protected function addSeries(&$arrSeries, $objResultSet, $strSerie, $timemod)
	{
		$sum       = 0;
		$time      = -1;
		$continued = false;
		while ($objResultSet->next()) {
			$sum += $objResultSet->sum;

			$tmp = floor($objResultSet->time - ($objResultSet->time % $timemod));
			if ($tmp == $time) {
				$continued = true;
				continue;
			}
			$time = $tmp;

			if (!isset($arrSeries[$time])) {
				$arrSeries[$time] = array();
			}

			$arrSeries[$time][$strSerie] = $sum;
			$continued                   = false;
		}
		if ($continued) {
			$arrSeries[$time][$strSerie] = $sum;
		}
	}

	protected function chartSends($objNewsletter, $strRecipient)
	{
		switch ($this->Input->get('size')) {
			case 'giant':
				$intWidth  = 1280;
				$intHeight = 1024;
				break;

			case 'large':
				$intWidth  = 1024;
				$intHeight = 768;
				break;

			default:
				$intWidth  = 690;
				$intHeight = 420;
		}

		$tstamp = $this->getTstamp($objNewsletter, $strRecipient);

		$since = $this->Input->get('since') ? $this->Input->get('since') : $tstamp['min'];
		$until = $this->Input->get('until') ? $this->Input->get('until') : $tstamp['max'];
		if ($since >= $tstamp['min'] && $since < $tstamp['max'] && $since < $until) {
			$tstamp['min'] = $since;
		} else {
			$since = $tstamp['min'];
		}
		if ($until > $tstamp['min'] && $until <= $tstamp['max'] && $since < $until) {
			$tstamp['max'] = $until;
		} else {
			$until = $tstamp['max'];
		}

		$strFile = TL_ROOT . '/system/html/chart-sends-' . $objNewsletter->id . ($strRecipient ? '-' . substr(md5($strRecipient), 0, 8) : '') . '-' . $tstamp['min'] . '-' . $tstamp['max'] . '-' . $intWidth . 'x' . $intHeight . '.png';

		if (!file_exists($strFile)) {
			$xticks   = floor(($intWidth - 150) / 80);
			$timespan = $tstamp['max'] - $tstamp['min'];
			$timemod  = round($timespan / (3*$xticks));

			$this->loadLanguageFile('avisota_tracking');

			require_once(TL_ROOT . '/plugins/pchart/pChart/pData.php');
			require_once(TL_ROOT . '/plugins/pchart/pChart/pChart.php');

			$arrSeries = array();
			for ($i=$tstamp['min']-($tstamp['min'] % $timemod); $i<$tstamp['max']; $i+=$timemod) {
				$arrSeries[$i] = array(
					'sends'  => '',
					'reads'  => '',
					'reacts' => ''
				);
			}

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
			$this->addSeries($arrSeries, $objResultSet, 'sends', $timemod);

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
			$this->addSeries($arrSeries, $objResultSet, 'reads', $timemod);

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
			$this->addSeries($arrSeries, $objResultSet, 'reacts', $timemod);

			ksort($arrSeries);

			$intLastSendSum  = 0;
			$arrSerieSends   = array();
			$intLastReadSum  = 0;
			$arrSerieReads   = array();
			$intLastReactSum = 0;
			$arrSerieReacts  = array();
			$arrDateTimes    = array();
			foreach ($arrSeries as $datetime => $temp) {
				$intLastSendSum  = empty($temp['sends']) ? $intLastSendSum : $temp['sends'];
				$intLastReadSum  = empty($temp['reads']) ? $intLastReadSum : $temp['reads'];
				$intLastReactSum = empty($temp['reacts']) ? $intLastReactSum : $temp['reacts'];
				if ($datetime >= $since && $datetime <= $until) {
					$arrSerieSends[]  =  $intLastSendSum;
					$arrSerieReads[]  =  $intLastReadSum;
					$arrSerieReacts[] = $intLastReactSum;
					$arrDateTimes[]   = $datetime;
				}
			}

			/*
			$arrDateTimes = array();
			for ($i=$tstamp['min']-($tstamp['min'] % $timemod)+$timemod; count($arrDateTimes)<count($arrSerieSends); $i+=3*$timemod) {
				$arrDateTimes[] = $i;
				$arrDateTimes[] = $i;
				$arrDateTimes[] = $i;
			}
			$arrDateTimes = array_slice($arrDateTimes, 0, count($arrSerieSends));
			*/

			/*
			header('Content-Type: text/plain');
			var_dump($arrDateTimes, $arrSerieSends, $arrSerieReads, $arrSerieReacts);
			exit;
			*/

			// Dataset definition
			$objDataSet = new pData;

			$objDataSet->AddPoint($arrSerieSends, 'sends');
			$objDataSet->AddPoint($arrSerieReads, 'reads');
			$objDataSet->AddPoint($arrSerieReacts, 'reacts');
			$objDataSet->AddPoint($arrDateTimes, 'date');

			$objDataSet->AddSerie('sends');
			$objDataSet->AddSerie('reads');
			$objDataSet->AddSerie('reacts');
			$objDataSet->SetAbsciseLabelSerie('date');

			$objDataSet->SetSerieName($GLOBALS['TL_LANG']['avisota_tracking']['newsletter']['sends'], 'sends');
			$objDataSet->SetSerieName($GLOBALS['TL_LANG']['avisota_tracking']['newsletter']['reads'], 'reads');
			$objDataSet->SetSerieName($GLOBALS['TL_LANG']['avisota_tracking']['newsletter']['reacts'], 'reacts');

			$objDataSet->SetXAxisFormat('date');

			// Initialise the graph
			$objChart = new pChart($intWidth, $intHeight);
			$objChart->setFixedScale(0, $this->getUpScale($intLastSendSum, $intLastReadSum, $intLastReactSum));
			$objChart->setFontProperties(TL_ROOT . '/plugins/pchart/Fonts/tahoma.ttf', 9);
			$objChart->setGraphArea(85, 30, $intWidth - 50, $intHeight - 70);
			$objChart->drawGraphArea(252, 252, 252);
			$objChart->drawScale($objDataSet->GetData(), $objDataSet->GetDataDescription(), SCALE_NORMAL, 150, 150, 150, TRUE, 45, 2);
			$objChart->drawGrid(4, TRUE, 230, 230, 230, 255);

			// Draw the line graph
			$objChart->drawLineGraph($objDataSet->GetData(), $objDataSet->GetDataDescription());

			// Finish the graph
			$objChart->setFontProperties(TL_ROOT . '/plugins/pchart/Fonts/tahoma.ttf', 8);
			$objChart->drawLegend(95, 35, $objDataSet->GetDataDescription(), 255, 255, 255);
			$objChart->setFontProperties(TL_ROOT . '/plugins/pchart/Fonts/tahoma.ttf', 10);
			$objChart->drawTitle(85, 22, sprintf($GLOBALS['TL_LANG']['avisota_tracking']['chart']['headline'],
				$GLOBALS['TL_LANG']['avisota_tracking'][($strRecipient) ? 'recipient' : 'newsletter']['stats_legend'],
				$objNewsletter->subject,
				$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $tstamp['min']),
				$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $tstamp['max'])
			), 50, 50, 50);
			$objChart->Render($strFile);
		}

		$this->output($strFile, sprintf($GLOBALS['TL_LANG']['avisota_tracking']['chart']['headline'],
			$GLOBALS['TL_LANG']['avisota_tracking'][($strRecipient) ? 'recipient' : 'newsletter']['stats_legend'],
			$objNewsletter->subject,
			$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $tstamp['min']),
			$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $tstamp['max'])));
	}

	protected function chartLinks($objNewsletter, $strRecipient)
	{
		switch ($this->Input->get('size')) {
			case 'giant':
				$intWidth  = 1280;
				$intHeight = 1024;
				break;

			case 'large':
				$intWidth  = 1024;
				$intHeight = 768;
				break;

			default:
				$intWidth  = 690;
				$intHeight = 420;
		}

		$tstamp = $this->getTstamp($objNewsletter, $strRecipient);

		$since = $this->Input->get('since') ? $this->Input->get('since') : $tstamp['min'];
		$until = $this->Input->get('until') ? $this->Input->get('until') : $tstamp['max'];
		if ($since >= $tstamp['min'] && $since < $tstamp['max'] && $since < $until) {
			$tstamp['min'] = $since;
		} else {
			$since = $tstamp['min'];
		}
		if ($until > $tstamp['min'] && $until <= $tstamp['max'] && $since < $until) {
			$tstamp['max'] = $until;
		} else {
			$until = $tstamp['max'];
		}

		$strFile = TL_ROOT . '/system/html/chart-links-' . $objNewsletter->id . ($strRecipient ? '-' . substr(md5($strRecipient), 0, 8) : '') . '-' . $tstamp['min'] . '-' . $tstamp['max'] . '-' . $intWidth . 'x' . $intHeight . '.png';

		if (!file_exists($strFile)) {
			$xticks   = floor(($intWidth - 150) / 80);
			$timespan = $tstamp['max'] - $tstamp['min'];
			$timemod  = round($timespan / (3*$xticks));

			$this->loadLanguageFile('avisota_tracking');

			require_once(TL_ROOT . '/plugins/pchart/pChart/pData.php');
			require_once(TL_ROOT . '/plugins/pchart/pChart/pChart.php');

			$arrSeries = array();
			for ($i=$tstamp['min']-($tstamp['min'] % $timemod); $i<$tstamp['max']; $i+=$timemod) {
				$arrSeries[$i] = array();
			}

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

			$arrLinks = array();
			while ($objLink->next())
			{
				$arrLinks[] = $objLink->url;

				$objResultSet = $this->Database
					->prepare("SELECT tstamp as time,COUNT(tstamp) as sum
						FROM tl_avisota_statistic_raw_link_hit
						WHERE $strWhere=?
						GROUP BY time")
					->execute($objLink->id);
				$this->addSeries($arrSeries, $objResultSet, $objLink->url, $timemod);
			}

			ksort($arrSeries);

			$arrSeriesLast  = array();
			$arrSeriesLinks = array();
			$arrDateTimes = array();
			foreach ($arrSeries as $datetime => $array) {
				foreach ($arrLinks as $link) {
					if (!isset($arrSeriesLinks[$link])) {
						$arrSeriesLinks[$link] = array();
					}
					if (!isset($arrSeriesLast[$link])) {
						$arrSeriesLast[$link] = 0;
					}

					$arrSeriesLast[$link] = empty($array[$link])
						? $arrSeriesLast[$link]
						: $array[$link];

					if ($datetime >= $since && $datetime <= $until) {
						$arrSeriesLinks[$link][] = $arrSeriesLast[$link];
					}
				}

				if ($datetime >= $since && $datetime <= $until) {
					$arrDateTimes[] = $datetime;
				}
			}

			// Dataset definition
			$objDataSet = new pData;

			foreach ($arrSeriesLinks as $link=>$data) {
				$objDataSet->AddPoint($data, $link);
				$objDataSet->AddSerie($link);
				$objDataSet->SetSerieName($link, $link);
			}
			$objDataSet->AddPoint($arrDateTimes, 'date');

			$objDataSet->SetAbsciseLabelSerie('date');

			$objDataSet->SetXAxisFormat('date');

			// Initialise the graph
			$objChart = new pChart($intWidth, $intHeight + 16 * count($arrSeriesLinks) + 32);
			$objChart->setFontProperties(TL_ROOT . '/plugins/pchart/Fonts/tahoma.ttf', 9);
			$objChart->setGraphArea(85, 30, $intWidth - 50, $intHeight - 70);
			$objChart->drawGraphArea(252, 252, 252);
			$objChart->drawScale($objDataSet->GetData(), $objDataSet->GetDataDescription(), SCALE_NORMAL, 150, 150, 150, TRUE, 45, 2);
			$objChart->drawGrid(4, TRUE, 230, 230, 230, 255);

			// Draw the line graph
			$objChart->drawLineGraph($objDataSet->GetData(), $objDataSet->GetDataDescription());

			// Finish the graph
			$objChart->setFontProperties(TL_ROOT . '/plugins/pchart/Fonts/tahoma.ttf', 8);
			$objChart->drawLegend(85, $intHeight + 8, $objDataSet->GetDataDescription(), 245, 245, 245);
			$objChart->setFontProperties(TL_ROOT . '/plugins/pchart/Fonts/tahoma.ttf', 10);
			$objChart->drawTitle(85, 22, sprintf($GLOBALS['TL_LANG']['avisota_tracking']['chart']['headline'],
				$GLOBALS['TL_LANG']['avisota_tracking'][($strRecipient) ? 'recipient' : 'newsletter']['links_legend'],
				$objNewsletter->subject,
				$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $tstamp['min']),
				$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $tstamp['max'])
			), 50, 50, 50);
			$objChart->Render($strFile);
		}

		$this->output($strFile, sprintf($GLOBALS['TL_LANG']['avisota_tracking']['chart']['headline'],
			$GLOBALS['TL_LANG']['avisota_tracking'][($strRecipient) ? 'recipient' : 'newsletter']['links_legend'],
			$objNewsletter->subject,
			$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $tstamp['min']),
			$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $tstamp['max'])));
	}

	protected function output($strFile, $strName)
	{
		// Make sure no output buffer is active
		// @see http://ch2.php.net/manual/en/function.fpassthru.php#74080
		while (@ob_end_clean());

		// Prevent session locking (see #2804)
		session_write_close();

		// set content type
		header('Content-Type: image/png');

		if ($this->Input->get('download')) {
			// Open the "save as …" dialogue
			header('Content-Transfer-Encoding: binary');
			header('Content-Disposition: attachment; filename="' . str_replace(':', '.', $strName) . '.png"');
			header('Content-Length: ' . filesize($strFile));
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Expires: 0');
			header('Connection: close');
		}

		// send file content
		readfile($strFile);

		exit;
	}
}
