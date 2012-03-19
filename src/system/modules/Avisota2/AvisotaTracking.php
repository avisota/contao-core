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
 * Class Avisota
 *
 * Parent class for newsletter content elements.
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaTracking extends BackendModule
{
	/**
	 * @var AvisotaTrackingAjax
	 */
	protected $Ajax;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_avisota_tracking';


	protected $blnUseHighstock = false;


	public function generate()
	{
		if ($this->Input->get('table')) {
			return $this->objDc->edit();
		}

		$this->import('AvisotaTrackingAjax', 'Ajax');

		return parent::generate();
	}


	public function compile()
	{
		$this->loadLanguageFile('avisota_tracking');

		// generate chart
		switch ($GLOBALS['TL_CONFIG']['avisota_chart']) {
			case 'jqplot':
				$objChart = new AvisotaChartJqPlot();
				break;
			case 'highstock':
				if (is_dir(TL_ROOT . '/system/modules/Avisota2/highstock') &&
					!is_file(TL_ROOT . '/system/modules/Avisota2/highstock/js/highstock.js') &&
					$GLOBALS['TL_CONFIG']['avisota_chart_highstock_confirm']
				) {
					$objChart = new AvisotaChartHighstock();
				} else {
					$this->log('Highstock.js is not installed or confirmed to licensed!', 'AvisotaTracking::compile()', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
			case 'pchart':
				$objChart = new AvisotaChartPChart();
				break;
			default:
				$this->log('No chart renderer found.', 'AvisotaTracking::compile()', TL_ERROR);
				$this->redirect('contao/main.php?act=error');
		}

		if ($this->Environment->isAjaxRequest) {
			$objNewsletter = $this->Database
				->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
				->execute($this->Input->get('newsletter'));
			$strRecipient = $this->Input->get('recipient');

			if ($objNewsletter->next()) {
				switch ($this->Input->get('data'))
				{
					case 'recipients':
						$this->Ajax->json_recipients($objNewsletter);
				}

				$objChart->handleAjax($objNewsletter, $strRecipient);
			}

			header('Content-Type: application/json');
			echo json_encode(array('error'=>'Invalid newsletter ID.'));
			exit;
		}

		# load the session settings
		list($intNewsletter, $strRecipient) = $this->Session->get('AVISOTA_TRACKING');

		# evaluate the post and get parameters
		if ($this->Input->post('FORM_SUBMIT') == 'tl_filters') {
			$this->Session->set('AVISOTA_TRACKING', array(
				$this->Input->post('newsletter'),
				urldecode($this->Input->post('recipient'))
			));
			$this->redirect('contao/main.php?do=avisota_tracking');
		}

		if ($this->Input->get('recipient')) {
			$this->Session->set('AVISOTA_TRACKING', array($intNewsletter, urldecode($this->Input->get('recipient'))));
			$this->redirect('contao/main.php?do=avisota_tracking');
		}

		# where statement, if the newsletters have to filter by a specific recipient
		$strWhere = '';

		if ($strRecipient) {
			$objRecipient = $this->Database
				->prepare("SELECT * FROM tl_avisota_statistic_raw_recipient WHERE recipient=?")
				->limit(1)
				->execute($strRecipient);
			if (!$objRecipient->numRows) {
				$this->Session->set('AVISOTA_TRACKING', array($intNewsletter, ''));
				$this->redirect('contao/main.php?do=avisota_tracking');
			}

			# collect read state and build where statement for a specific recipient
			else
			{
				$arrIds = $objRecipient->fetchEach('pid');
				if (count($arrIds)) {
					$strWhere = ' AND id IN (' . implode(',', $arrIds) . ')';
				}
				else
				{
					$strWhere = ' AND id=0';
				}
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
		if (count($arrNewsletters) == 0) {
			$this->Template->empty = true;
			return;
		}

		# find last sended newsletter
		if (!$intNewsletter) {
			$arrIds        = array_keys($arrNewsletters);
			$intNewsletter = array_shift($arrIds);
			$this->Session->set('AVISOTA_TRACKING', array($intNewsletter, $strRecipient));
			$this->redirect('contao/main.php?do=avisota_tracking');
		}

		$objNewsletter = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
			->execute($intNewsletter);
		// Newsletter does not exists, use another one and reload
		if (!$objNewsletter->next()) {
			$arrIds        = array_keys($arrNewsletters);
			$intNewsletter = array_shift($arrIds);
			$this->Session->set('AVISOTA_TRACKING', array($intNewsletter, $strRecipient));
			$this->redirect('contao/main.php?do=avisota_tracking');
		}

		$this->Template->chart       = $objChart->generateChart($objNewsletter, $strRecipient);
		$this->Template->mode        = ($strRecipient) ? 'recipient' : 'newsletter';
		$this->Template->newsletters = $arrNewsletters;
		$this->Template->newsletter  = $objNewsletter->row();
		$this->Template->recipient   = $strRecipient;
	}
}
