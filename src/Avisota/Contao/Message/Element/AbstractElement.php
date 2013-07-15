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


namespace Avisota\Contao\Message\Element;

use Avisota\Contao\Entity\MessageContent;


/**
 * Class NewsletterElement
 *
 * Parent class for newsletter content elements.
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
abstract class AbstractElement extends AvisotaFrontend
{
	/**
	 * Current content record
	 *
	 * @var MessageContent
	 */
	protected $messageContent = array();

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
	public function __construct(MessageContent $messageContent)
	{
		parent::__construct();

		$this->messageContent = $messageContent;
		$this->space   = deserialize($messageContent->getSpace());
		$this->cssID   = deserialize($messageContent->getCssID(), true);

		$headline    = deserialize($messageContent->getHeadline());
		$this->headline = is_array($headline) ? $headline['value'] : $headline;
		$this->hl       = is_array($headline) ? $headline['unit'] : 'h1';
	}

	/**
	 * Parse the plain text template
	 *
	 * @return string
	 */
	public function generate()
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
