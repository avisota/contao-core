<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Class NewsletterList
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
class NewsletterList extends Element
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $templateHTML = 'nle_list_html';

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $templatePlain = 'nle_list_plain';


	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$items = array();
		$itemContents    = deserialize($this->listItems);

		if ($mode == NL_HTML) {
			$limit = count($itemContents) - 1;

			for ($i = 0; $i < count($itemContents); $i++) {
				$items[] = array
				(
					'class'   => (($i == 0) ? 'first' : (($i == $limit) ? 'last' : '')),
					'content' => $itemContents[$i]
				);
			}

			$this->Template->items = $items;
			$this->Template->tag   = ($this->listType == 'ordered') ? 'ol' : 'ul';
		}
		else {
			$this->Template->items = $itemContents;
		}
	}
}
