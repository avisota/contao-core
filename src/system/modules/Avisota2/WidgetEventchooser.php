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
 *
 * @copyright  4ward.media 2010
 * @copyright  InfinitySoft 2011,2012
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    WidgetEventchooser
 * @license    LGPL
 * @filesource
 */


/**
 * Class WidgetEventchooser
 *
 * @copyright  4ward.media 2010
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class WidgetEventchooser extends Widget
{

	/**
	 * Submit user input
	 *
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 *
	 * @param string
	 * @param mixed
	 */
	public function __set($key, $value)
	{
		switch ($key) {
			default:
				parent::__set($key, $value);
				break;
		}
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generate()
	{
		$this->import('Database');
		if (!is_array($this->value)) {
			$this->value = array();
		}

		$events = $this->getAllEvents();

		if (!count($events)) {
			return '<p class="tl_noopt">' . $GLOBALS['TL_LANG']['MSC']['noResult'] . '</p>';
		}

		$buffer = '';
		$header    = $date = "";
		foreach ($events as $event) {
			if ($event['calendar'] != $header) {
				$header = $event['calendar'];
				$buffer .= '<br/><h1 class="main_headline">' . $header . '</h1>';
			}

			$curDate = $GLOBALS['TL_LANG']['MONTHS'][date('m', $event['startTime']) - 1] . ' ' . date(
				'Y',
				$event['startTime']
			);
			if ($curDate != $date) {
				$date = $curDate;
				$buffer .= '<div class="tl_content_header">' . $curDate . '</div>';
			}

			$buffer .= '<div class="tl_content">';
			$buffer .= '<input type="checkbox" id="event' . $event['id'] . '_' . $event['startTime'] . '" class="tl_checkbox" name="events[]" value="' . $event['id'] . '_' . $event['startTime'] . '"';
			if (in_array($event['id'] . '_' . $event['startTime'], $this->value)) {
				$buffer .= ' CHECKED';
			}
			$buffer .= '/>';
			$buffer .= '<label for="event' . $event['id'] . '_' . $event['startTime'] . '"> ';
			$buffer .= $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $event['startTime']) . ' - ';
			$buffer .= '<strong>' . $event['title'] . '</strong></label>';
			$buffer .= '</div>';
		}

		return $buffer;
	}

	/**
	 * get all events
	 *
	 * @return array
	 */
	protected function getAllEvents()
	{
		$startTime = time();
		$endTime   = $startTime + 360 * 3600 * 24;

		// Get events of the current period
		$event = $this->Database
			->prepare(
			"SELECT e.startTime, e.endTime, e.id,e.title, e.recurring, e.recurrences, e.repeatEach, c.title AS calendar
											   FROM tl_calendar_events AS e
											   LEFT JOIN tl_calendar AS c ON (e.pid = c.id)
											   WHERE
											   		published='1' " . // only published events
				"AND (
														startTime >= $startTime AND endTime <= $endTime " . // all events in the period
				"OR recurring='1' AND (" . // all recurring events which are not ending bevore intStart
				"recurrences=0 OR repeatEnd>=$startTime
														)
													)"
		)
			->execute();

		if ($event->numRows < 1) {
			return array();
		}

		$events = array();
		while ($event->next()) {

			// Recurring events
			if ($event->recurring) {
				$count     = 0;
				$repeat = deserialize($event->repeatEach);

				while ($event->endTime < $endTime) {
					if ($event->recurrences > 0 && $count++ > $event->recurrences) {
						break;
					}

					$arg  = $repeat['value'];
					$unit = $repeat['unit'];

					if ($arg < 1) {
						break;
					}

					// Skip events outside the scope
					if ($event->startTime < $startTime) {
						continue;
					}

					$events[] = $event->row();

					// calculate next time after adding, otherwise the first event date is skipped!
					$strtotime = '+ ' . $arg . ' ' . $unit;

					$event->startTime = strtotime($strtotime, $event->startTime);
					$event->endTime   = strtotime($strtotime, $event->endTime);
				}
			}
			else // not recurring
			{
				$events[] = $event->row();
			}
		}

		// sort the stuff by Calendar and StartTime
		$calendars = array();
		$dates     = array();
		foreach ($events as $k => $eventData) {
			$calendars[$k] = $eventData['calendar'];
			$dates[$k]     = $eventData['startTime'];
		}

		array_multisort($calendars, $dates, $events);

		return $events;


	}
}
