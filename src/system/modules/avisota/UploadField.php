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
 * Class UploadField
 *
 * Provide methods to handle upload fields.
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class UploadField extends Widget implements uploadable
{
	/**
	 * Submit user input
	 *
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'be_widget';

	/**
	 * Contents
	 *
	 * @var array
	 */
	protected $contents = array();


	/**
	 * Add specific attributes
	 *
	 * @param string
	 * @param mixed
	 */
	public function __set($key, $value)
	{
		switch ($key) {
			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $value ? true : false;
				break;

			default:
				parent::__set($key, $value);
				break;
		}
	}


	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		if (isset($_FILES[$this->strName]) && is_uploaded_file($_FILES[$this->strName]['tmp_name'])) {
			$value = $_FILES[$this->strName];
		}
		else {
			$value = false;
		}

		if ($this->mandatory && !$value) {
			if ($this->strLabel == '') {
				$this->addError($GLOBALS['TL_LANG']['ERR']['mdtryNoLabel']);
			}
			else {
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory'], $this->strLabel));
			}
		}

		if ($this->hasErrors()) {
			$this->class = 'error';
		}

		$this->varValue = $value;
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generate()
	{
		return sprintf(
			'<input type="file" name="%s" id="ctrl_%s" class="tl_upload%s" %s onfocus="Backend.getScrollOffset();" />',
			$this->strName,
			$this->strId,
			(strlen($this->strClass) ? ' ' . $this->strClass : ''),
			$this->getAttributes()
		);
	}
}
