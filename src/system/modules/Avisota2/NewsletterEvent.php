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
 * @copyright  4ward.media 2010
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


class NewsletterEvent extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_events_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_events_plain';

	/**
	 * Caching var for jumpTo-pages
	 * @var mixed tl_page-rows
	 */
	protected $arrObjJumpToPages = array();



	/**
	 * Generate content element
	 * @param str $mode Compile either html or plaintext part
	 */
	protected function compile($mode)
	{
		$this->import('DomainLink');

		$events = unserialize($this->events);
		if(!is_array($events))
		{
			$this->Template->events = array();
			return;
		}

		// split ID and startTime
		$eventIds = array();
		$eventStartTimes = array();
		foreach($events as $event)
		{
			$tmp = explode('_',$event);
			$eventIds[] = $tmp[0];
			$eventStartTimes[] = $tmp[1];
		}

		$objEvents = $this->Database->prepare('SELECT e.*,c.jumpTo,c.title AS section
												FROM tl_calendar_events as e
												LEFT JOIN tl_calendar as c ON (e.pid = c.id)
												WHERE e.id IN ('.implode(',',$eventIds).')
												ORDER BY e.startDate')->execute();

		$arrEvents = array();
		while($objEvents->next())
		{
			$arrEvents[$objEvents->id] = $objEvents->row();
		}

		$arrReturn = array();
		foreach($eventIds as $k=>$id)
		{
			// adjust startTime for recurring events
			if($arrEvents[$id]['recurring'])
			{
				$event = $arrEvents[$id];
				$count = 0;
				$arrRepeat = deserialize($event['repeatEach']);

				while ($event['recurrences'] <= $count || ($event['recurrences']!=0 && $event['startTime'] < $event['repeatEnd']))
				{
					if($event['startTime'] == $eventStartTimes[$k])
					{
						$arrReturn[] = $event;
						break;
					}

					$arg = $arrRepeat['value'];
					$unit = $arrRepeat['unit'];

					if ($arg < 1)
					{
						break;
					}

					$strtotime = '+ ' . $arg . ' ' . $unit;

					$event['startTime'] = strtotime($strtotime, $event['startTime']);
					$event['endTime'] = strtotime($strtotime, $event['endTime']);

				}
			}
			else
			{
				$arrReturn[] = $arrEvents[$id];
			}
		}

		$this->Template->events = $arrReturn;
	}
}
