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

namespace Avisota\Contao\Core\Event;

use Avisota\Contao\Core\Message\Renderer;
use Symfony\Component\EventDispatcher\Event;

class ResolveStylesheetEvent extends Event
{
	const NAME = 'Avisota\Contao\Core\Event\ResolveStylesheet';

	/**
	 * @var string
	 */
	protected $stylesheet;

	function __construct($stylesheet)
	{
		$this->stylesheet = $stylesheet;
	}

	/**
	 * @param string $stylesheet
	 */
	public function setStylesheet($stylesheet)
	{
		$this->stylesheet = $stylesheet;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getStylesheet()
	{
		return $this->stylesheet;
	}
}