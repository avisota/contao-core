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

namespace Avisota\Contao\Event;

use Avisota\Contao\Message\Renderer;
use Symfony\Component\EventDispatcher\Event;

class MailingListCreateLabelEvent extends Event
{
	const NAME = 'Avisota\Contao\Event\MailingListCreateLabel';

	/**
	 * @var \ArrayObject
	 */
	protected $row;

	/**
	 * @var \StringBuilder
	 */
	protected $label;

	function __construct(\ArrayObject $row, \StringBuilder $label)
	{
		$this->row = $row;
		$this->label = $label;
	}

	/**
	 * @return \ArrayObject
	 */
	public function getRow()
	{
		return $this->row;
	}

	/**
	 * @return \StringBuilder
	 */
	public function getLabel()
	{
		return $this->label;
	}
}