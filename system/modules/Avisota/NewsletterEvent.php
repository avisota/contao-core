<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright +4wardmedia


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