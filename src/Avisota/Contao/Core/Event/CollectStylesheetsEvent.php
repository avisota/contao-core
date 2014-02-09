<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Event;

use Avisota\Contao\Core\Message\Renderer;
use Symfony\Component\EventDispatcher\Event;

class CollectStylesheetsEvent extends Event
{
	const NAME = 'Avisota\Contao\Core\Event\CollectStylesheets';

	/**
	 * @var \ArrayObject
	 */
	protected $stylesheets;

	function __construct(\ArrayObject $stylesheets)
	{
		$this->stylesheets = $stylesheets;
	}

	/**
	 * @return \ArrayObject
	 */
	public function getStylesheets()
	{
		return $this->stylesheets;
	}
}