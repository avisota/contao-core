<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Class WidgetEventchooser
 *
 * @copyright  4ward.media 2010
 * @copyright  bit3 UG 2013
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
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
