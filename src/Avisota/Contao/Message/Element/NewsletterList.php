<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class NewsletterList
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
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
		$itemContents    = deserialize($this->listitems);

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
			$this->Template->tag   = ($this->listtype == 'ordered') ? 'ol' : 'ul';
		}
		else {
			$this->Template->items = $itemContents;
		}
	}
}
