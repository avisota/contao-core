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
 * @copyright  4ward.media 2010
 * @copyright  InfinitySoft 2011
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    WidgetEventchooser
 * @license    LGPL
 * @filesource
 */

class WidgetEventchooser extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$this->import('Database');
		$strClass = 'eventchooser';
		if(!is_array($this->value)) $this->value = array();

		$arrEvents = $this->getAllEvents();

		if(!count($arrEvents)) {
			return  '<p class="tl_noopt">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
		}

		$strBuffer = '';
		$header = $date = "";
		foreach($arrEvents as $event)
		{
			if($event['calendar'] != $header)
			{
				$header = $event['calendar'];
				$strBuffer .= '<br/><h1 class="main_headline">'.$header.'</h1>';
			}

			$curDate = $GLOBALS['TL_LANG']['MONTHS'][date('m',$event['startTime'])-1].' '.date('Y',$event['startTime']);
			if($curDate != $date)
			{
				$date = $curDate;
				$strBuffer .= '<div class="tl_content_header">'.$curDate.'</div>';
			}

			$strBuffer .= '<div class="tl_content">';
			$strBuffer .= '<input type="checkbox" id="event'.$event['id'].'_'.$event['startTime'].'" class="tl_checkbox" name="events[]" value="'.$event['id'].'_'.$event['startTime'].'"';
			if(in_array($event['id'].'_'.$event['startTime'], $this->value)) $strBuffer .= ' CHECKED';
			$strBuffer .= '/>';
			$strBuffer .= '<label for="event'.$event['id'].'_'.$event['startTime'].'"> ';
			$strBuffer .= $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'],$event['startTime']).' - ';
			$strBuffer .= '<strong>'.$event['title'].'</strong></label>';
			$strBuffer .= '</div>';
		}

		return $strBuffer;
	}

	/**
	 * get all events
	 * @return array
	 */
	protected function getAllEvents()
	{
		$intStart = time();
		$intEnd = $intStart + 360*3600*24;

		// Get events of the current period
		$objEvents = $this->Database->prepare("SELECT e.startTime, e.endTime, e.id,e.title, e.recurring, e.recurrences, e.repeatEach, c.title AS calendar
											   FROM tl_calendar_events AS e
											   LEFT JOIN tl_calendar AS c ON (e.pid = c.id)
											   WHERE
											   		published='1' ". // only published events
												   "AND (
														startTime >= $intStart AND endTime <= $intEnd ". // all events in the period
													   "OR recurring='1' AND (". // all recurring events which are not ending bevore intStart
															"recurrences=0 OR repeatEnd>=$intStart
														)
													)")
									->execute();

		if ($objEvents->numRows < 1)
		{
			return array();
		}

		$arrEvents = array();
		while ($objEvents->next())
		{

			// Recurring events
			if ($objEvents->recurring)
			{
				$count = 0;
				$arrRepeat = deserialize($objEvents->repeatEach);

				while ($objEvents->endTime < $intEnd)
				{
					if ($objEvents->recurrences > 0 && $count++ > $objEvents->recurrences)
					{
						break;
					}

					$arg = $arrRepeat['value'];
					$unit = $arrRepeat['unit'];

					if ($arg < 1)
					{
						break;
					}

					// Skip events outside the scope
					if ($objEvents->startTime < $intStart)
					{
						continue;
					}

					$arrEvents[] = $objEvents->row();

					// calculate next time after adding, otherwise the first event date is skipped!
					$strtotime = '+ ' . $arg . ' ' . $unit;

					$objEvents->startTime = strtotime($strtotime, $objEvents->startTime);
					$objEvents->endTime = strtotime($strtotime, $objEvents->endTime);
				}
			}
			else
			// not recurring
			{
				$arrEvents[] = $objEvents->row();
			}
		}

		// sort the stuff by Calendar and StartTime
		$arrCalendars = array();
		$arrDates = array();
		foreach($arrEvents as $k => $event)
		{
			$arrCalendars[$k] = $event['calendar'];
			$arrDates[$k] = $event['startTime'];
		}

		array_multisort($arrCalendars,$arrDates,$arrEvents);

		return $arrEvents;


	}
}

?>
