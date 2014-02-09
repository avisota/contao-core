<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Class NewsletterElement
 *
 * Parent class for newsletter content elements.
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 */
abstract class NewsletterElement extends AvisotaFrontend
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $templateHTML = null;

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $templatePlain = null;

	/**
	 * Current record
	 *
	 * @var array
	 */
	protected $currentRecordData = array();

	/**
	 * Style array
	 *
	 * @var array
	 */
	protected $style = array();


	/**
	 * Initialize the object
	 *
	 * @param object
	 *
	 * @return string
	 */
	public function __construct(array $elementData)
	{
		parent::__construct();

		$this->currentRecordData = $elementData;
		$this->space   = deserialize($elementData['space']);
		$this->cssID   = deserialize($elementData['cssID'], true);

		$headline    = deserialize($elementData['headline']);
		$this->headline = is_array($headline) ? $headline['value'] : $headline;
		$this->hl       = is_array($headline) ? $headline['unit'] : 'h1';
	}


	/**
	 * Set an object property
	 *
	 * @param string
	 * @param mixed
	 */
	public function __set($key, $value)
	{
		$this->currentRecordData[$key] = $value;
	}


	/**
	 * Return an object property
	 *
	 * @param string
	 *
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->currentRecordData[$key];
	}


	/**
	 * Add an image to a template
	 *
	 * @param object
	 * @param array
	 * @param integer
	 * @param string
	 */
	protected function addImageToTemplate($template, $itemData, $maxWidth = false, $lightboxId = false)
	{
		$size    = deserialize($itemData['size']);
		$imgSize = getimagesize(TL_ROOT . '/' . $itemData['singleSRC']);

		if (!$maxWidth) {
			$maxWidth = ($this->Input->get('table') == 'orm_avisota_message_content' ? 320
				: $GLOBALS['TL_CONFIG']['maxImageWidth']);
		}

		// Store original dimensions
		$template->width  = $imgSize[0];
		$template->height = $imgSize[1];

		// Adjust image size
		if ($maxWidth > 0 && ($size[0] > $maxWidth || (!$size[0] && !$size[1] && $imgSize[0] > $maxWidth))) {
			$imageMargins = deserialize($itemData['imagemargin']);

			// Subtract margins
			if (is_array($imageMargins) && $imageMargins['unit'] == 'px') {
				$maxWidth = $maxWidth - $imageMargins['left'] - $imageMargins['right'];
			}

			// See #2268 (thanks to Thyon)
			$ratio = ($size[0] && $size[1]) ? $size[1] / $size[0] : $imgSize[1] / $imgSize[0];

			$size[0] = $maxWidth;
			$size[1] = floor($maxWidth * $ratio);
		}

		$src = $this->getImage($this->urlEncode($itemData['singleSRC']), $size[0], $size[1], $size[2]);

		// Image dimensions
		if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false) {
			$template->arrSize = $imgSize;
			$template->imgSize = ' ' . $imgSize[3];
		}

		// Float image
		if (in_array($itemData['floating'], array('left', 'right'))) {
			$template->floatClass = ' float_' . $itemData['floating'];
		}

		// Image link
		if (strlen($itemData['imageUrl'])) {
			$template->href       = $this->extendURL($itemData['imageUrl']);
			$template->attributes = $itemData['fullsize'] ? LINK_NEW_WINDOW : '';
		}

		$template->src      = $this->extendURL($src);
		$template->alt      = specialchars($itemData['alt']);
		$template->margin   = $this->generateMargin(deserialize($itemData['imagemargin']), 'padding');
		$template->caption  = $itemData['caption'];
		$template->addImage = true;
	}


	/**
	 * Parse the html template
	 *
	 * @return string
	 */
	public function generateHTML()
	{
		if (!$this->templateHTML) {
			return '';
		}

		$this->style = array();

		if (strlen($this->currentRecordData['space'][0])) {
			$this->style[] = 'margin-top:' . $this->currentRecordData['space'][0] . 'px;';
		}

		if (strlen($this->currentRecordData['space'][1])) {
			$this->style[] = 'margin-bottom:' . $this->currentRecordData['space'][1] . 'px;';
		}

		$this->Template = new AvisotaNewsletterTemplate($this->templateHTML);
		$this->Template->setData($this->currentRecordData);

		$this->compile(NL_HTML);

		$this->Template->style = count($this->style) ? implode(' ', $this->style) : '';
		$this->Template->cssID = strlen($this->cssID[0]) ? ' id="' . $this->cssID[0] . '"' : '';
		$this->Template->class = trim('ce_' . $this->type . ' ' . $this->cssID[1]);

		if (!strlen($this->Template->headline)) {
			$this->Template->headline = $this->headline;
		}

		if (!strlen($this->Template->hl)) {
			$this->Template->hl = $this->hl;
		}

		return $this->Template->parse();
	}


	/**
	 * Parse the plain text template
	 *
	 * @return string
	 */
	public function generatePlain()
	{
		if (!$this->templatePlain) {
			return '';
		}

		$this->style = array();

		$this->Template = new AvisotaNewsletterTemplate($this->templatePlain);
		$this->Template->setData($this->currentRecordData);

		$this->compile(NL_PLAIN);

		if (!strlen($this->Template->headline)) {
			$this->Template->headline = $this->headline;
		}

		$headlineLevel = intval(substr(!strlen($this->Template->hl) ? $this->hl : $this->Template->hl, 1));
		$headline = '';
		for ($i = 0; $i < $headlineLevel; $i++) {
			$headline .= '#';
		}
		$this->Template->hl = $headline;

		return $this->Template->parse();
	}


	/**
	 * Compile the current element
	 */
	abstract protected function compile($mode);
}
