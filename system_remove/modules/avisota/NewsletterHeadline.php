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
 * Class NewsletterHeadline
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
class NewsletterHeadline extends Element
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
