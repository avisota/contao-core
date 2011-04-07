<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class NewsletterList
 *
 * 
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterList extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_list_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_list_plain';
	

	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$arrItems = array();
		$items = deserialize($this->listitems);

		if ($mode == NL_HTML)
		{
			$limit = count($items) - 1;
		
			for ($i=0; $i<count($items); $i++)
			{
				$arrItems[] = array
				(
					'class' => (($i == 0) ? 'first' : (($i == $limit) ? 'last' : '')),
					'content' => $items[$i]
				);
			}
	
			$this->Template->items = $arrItems;
			$this->Template->tag = ($this->listtype == 'ordered') ? 'ol' : 'ul';
		}
		else
		{
			$this->Template->items = $items;
		}
	}
}

?>