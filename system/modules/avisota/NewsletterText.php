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
 * Class Text
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
class NewsletterText extends Element
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $templateHTML = 'nle_text_html';

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $templatePlain = 'nle_text_plain';

	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$this->import('String');

		switch ($mode) {
			case NL_HTML:
				// Clean RTE output
				$this->Template->text = str_ireplace
				(
					array('<u>', '</u>', '</p>', '<br /><br />', ' target="_self"'),
					array('<span style="text-decoration:underline;">', '</span>', "</p>\n", "<br /><br />\n", ''),
					$this->String->encodeEmail($this->text)
				);
				break;

			case NL_PLAIN:
				if ($this->plain) {
					$this->Template->text = $this->plain;
				}
				else {
					$this->Template->text = $this->getPlainFromHTML($this->text);
				}
		}

		$this->Template->addImage = false;

		// Add image
		if ($this->addImage && strlen($this->singleSRC) && is_file(TL_ROOT . '/' . $this->singleSRC)) {
			$this->addImageToTemplate($this->Template, $this->currentRecordData);

			$this->Template->src = $this->extendURL($this->Template->src);
			if ($this->Template->href) {
				$this->Template->href = $this->extendURL($this->Template->href);
			}
		}
	}
}
