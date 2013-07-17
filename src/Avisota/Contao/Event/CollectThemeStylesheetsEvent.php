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

namespace Avisota\Contao\Event;

use Avisota\Contao\Message\Renderer;
use Symfony\Component\EventDispatcher\Event;

class CollectThemeStylesheetsEvent extends CollectStylesheetsEvent
{
	/**
	 * @var array
	 */
	protected $theme;

	function __construct(array $theme, \ArrayObject $stylesheets)
	{
		$this->theme = $theme;
		parent::__construct($stylesheets);
	}

	/**
	 * @return array
	 */
	public function getTheme()
	{
		return $this->theme;
	}
}