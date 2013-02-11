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
 * Class AvisotaBackendChartHighstock
 *
 * Parent class for newsletter content elements.
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBackendChartHighstock extends Backend implements AvisotaBackendChart
{
	/**
	 * @var AvisotaBackendTrackingAjax
	 */
	protected $Ajax;

	/**
	 *
	 * @param Database_Result $objNewsletter
	 * @param string $strRecipient
	 */
	public function handleAjax(Database_Result $objNewsletter, $strRecipient)
	{
		$this->import('AvisotaBackendTrackingAjax', 'Ajax');

		if ($this->Input->get('data')) {
			switch ($this->Input->get('data'))
			{
				case 'flags':
					$this->Ajax->json_newsletter_flags();

				case 'sends':
					$this->Ajax->json_sends($objNewsletter, $strRecipient, true);

				case 'reads':
					$this->Ajax->json_reads($objNewsletter, $strRecipient, true);

				case 'reacts':
					$this->Ajax->json_reacts($objNewsletter, $strRecipient, true);

				case 'links':
					$this->Ajax->json_links($objNewsletter, $strRecipient, true);
			}
		}
	}

	/**
	 * @param Database_Result $objNewsletter
	 * @param string $strRecipient
	 */
	public function generateChart(Database_Result $objNewsletter, $strRecipient)
	{
		$objTemplate = new BackendTemplate('be_avisota_chart_highstock');

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
				               SELECT url,(SELECT COUNT(id)
						       FROM tl_avisota_statistic_raw_link_hit h
						       WHERE l.id=h.linkID) as hits
						       FROM tl_avisota_statistic_raw_link l WHERE pid=?
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

		$objTemplate->mode       = ($strRecipient) ? 'recipient' : 'newsletter';
		$objTemplate->newsletter = $objNewsletter->row();
		$objTemplate->recipient  = $strRecipient;

		return $objTemplate->parse();
	}
}
