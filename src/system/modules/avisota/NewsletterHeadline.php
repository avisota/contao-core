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
 * Class NewsletterHeadline
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class NewsletterHeadline extends NewsletterElement
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $templateHTML = 'nle_headline_html';

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $templatePlain = 'nle_headline_plain';


	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
	}
}
