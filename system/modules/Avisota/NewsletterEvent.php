<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  4ward.media 2010
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
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
		
		$eventIds = unserialize($this->events);
		if(!is_array($eventIds))
		{
			$this->Template->events = array();
			return;
		}
		
		$objEvents = $this->Database->prepare('SELECT e.*,c.jumpTo,c.title AS section
												FROM tl_calendar_events as e
												LEFT JOIN tl_calendar as c ON (e.pid = c.id)
												WHERE e.id IN ('.implode(',',$eventIds).')
												ORDER BY e.startDate')->execute();
		
		$events = $objEvents->fetchAllAssoc();
		foreach($events as $k => $v)
		{
			$events[$k]['href'] = $this->getHref($v['jumpTo'],$v['alias']);
		}
		
		$this->Template->events = $events;
	}
	
	
	
	/**
	 * Generate the event-link
	 * @param int $id jumpTo-page-id
	 * @param str $alias alias
	 */
	protected function getHref($id,$alias)
	{
		if(!isset($this->arrObjJumpToPages[$id]))
		{
			$tmp = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start='' OR start<UNIX_TIMESTAMP()) AND (stop='' OR stop>UNIX_TIMESTAMP()) AND published=1")
											->limit(1)
											->execute($id);
											
			if($tmp->numRows < 1)
			{
				$this->arrObjJumpToPages[$id] = false;
			}
			else
			{
				$this->arrObjJumpToPages[$id] = $tmp;
			}
		}
		
		
		if($this->arrObjJumpToPages[$id])
			return $this->DomainLink->absolutizeUrl($this->generateFrontendUrl($this->arrObjJumpToPages[$id]->row(),'/events/'.$alias),$this->arrObjJumpToPages[$id]);
		else 
			return '';
		
	}
}

?>