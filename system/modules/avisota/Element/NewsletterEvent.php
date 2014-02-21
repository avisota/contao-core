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


class NewsletterEvent extends Element
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $templateHTML = 'nle_events_html';

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $templatePlain = 'nle_events_plain';

	/**
	 * Caching var for jumpTo-pages
	 *
	 * @var mixed tl_page-rows
	 */
	protected $jumpToPages = array();


	/**
	 * Generate content element
	 *
	 * @param str $mode Compile either html or plaintext part
	 */
	protected function compile($mode)
	{
		$this->import('DomainLink');

		$eventIdentifiers = unserialize($this->events);
		if (!is_array($eventIdentifiers)) {
			$this->Template->events = array();
			return;
		}

		// split ID and startTime
		$eventIds        = array();
		$eventStartTimes = array();
		foreach ($eventIdentifiers as $eventIdentifier) {
			$tmp               = explode('_', $eventIdentifier);
			$eventIds[]        = $tmp[0];
			$eventStartTimes[] = $tmp[1];
		}

		$event = \Database::getInstance()
			->prepare(
			'SELECT e.*,c.jumpTo,c.title AS section
												FROM tl_calendar_events as e
												LEFT JOIN tl_calendar as c ON (e.pid = c.id)
												WHERE e.id IN (' . implode(',', $eventIds) . ')
												ORDER BY e.startDate'
		)
			->execute();

		$events = array();
		while ($event->next()) {
			$events[$event->id] = $event->row();
		}

		$return = array();
		foreach ($eventIds as $k => $id) {
			// adjust startTime for recurring events
			if ($events[$id]['recurring']) {
				$eventData     = $events[$id];
				$count     = 0;
				$repeatData = deserialize($eventData['repeatEach']);

				while ($eventData['recurrences'] <= $count || ($eventData['recurrences'] != 0 && $eventData['startTime'] < $eventData['repeatEnd'])) {
					if ($eventData['startTime'] == $eventStartTimes[$k]) {
						$return[] = $eventData;
						break;
					}

					$arg  = $repeatData['value'];
					$unit = $repeatData['unit'];

					if ($arg < 1) {
						break;
					}

					$strtotime = '+ ' . $arg . ' ' . $unit;

					$eventData['startTime'] = strtotime($strtotime, $eventData['startTime']);
					$eventData['endTime']   = strtotime($strtotime, $eventData['endTime']);

				}
			}
			else {
				$return[] = $events[$id];
			}
		}

		$this->Template->events = $return;
	}
}
