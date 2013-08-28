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
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Class NewsletterImage
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class NewsletterImage extends Element
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $templateHTML = 'nle_image_html';

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $templatePlain = 'nle_image_plain';


	/**
	 * Parse the html template
	 *
	 * @return string
	 */
	public function generateHTML()
	{
		if (!strlen($this->singleSRC) || !is_file(TL_ROOT . '/' . $this->singleSRC)) {
			return '';
		}

		return parent::generateHTML();
	}


	/**
	 * Parse the plain text template
	 *
	 * @return string
	 */
	public function generatePlain()
	{
		if (!strlen($this->singleSRC) || !is_file(TL_ROOT . '/' . $this->singleSRC)) {
			return '';
		}

		return parent::generatePlain();
	}


	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$this->addImageToTemplate($this->Template, $this->currentRecordData);
	}
}
