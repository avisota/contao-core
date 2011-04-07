<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright <4wardmedia

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

		// get events
		$objEvents = $this->Database->prepare('SELECT e.id, e.title, e.startDate, c.title AS section 
												FROM tl_calendar_events AS e
												LEFT JOIN tl_calendar AS c ON (e.pid = c.id)
												WHERE published="1" AND startDate >= UNIX_TIMESTAMP() 
												ORDER BY c.title, e.startDate')
									->execute();
		
		if($objEvents->numRows < 1) {
			return  '<p class="tl_noopt">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
		}
		
		$strBuffer = '';
		$header = $date = "";
		while($objEvents->next())
		{
			if($objEvents->section != $header)
			{
				$header = $objEvents->section;
				$strBuffer .= '<br/><h1 class="main_headline">'.$header.'</h1>';
			}
			
			$curDate = $GLOBALS['TL_LANG']['MONTHS'][date('m',$objEvents->startDate)-1].' '.date('Y',$objEvents->startDate);
			if($curDate != $date)
			{
				$date = $curDate;
				$strBuffer .= '<div class="tl_content_header">'.$curDate.'</div>';
			}
			
			$strBuffer .= '<div class="tl_content">';
			$strBuffer .= '<input type="checkbox" id="event'.$objEvents->id.'" class="tl_checkbox" name="events[]" value="'.$objEvents->id.'"';
			if(in_array($objEvents->id, $this->value)) $strBuffer .= ' CHECKED';
			$strBuffer .= '/>';
			$strBuffer .= '<label for="event'.$objEvents->id.'"> ';
			$strBuffer .= date('d.m.Y',$objEvents->startDate).' - ';
			$strBuffer .= '<strong>'.$objEvents->title.'</strong></label>';
			$strBuffer .= '</div>';
		}
		
		return $strBuffer;
	}
}

?>
