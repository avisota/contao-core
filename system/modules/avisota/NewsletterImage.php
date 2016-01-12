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
 * Class NewsletterImage
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
class Image extends NewsletterElement
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
