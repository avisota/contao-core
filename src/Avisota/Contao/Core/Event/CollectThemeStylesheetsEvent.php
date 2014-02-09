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

class CollectThemeStylesheetsEvent extends CollectStylesheetsEvent
{
	const NAME = 'Avisota\Contao\Core\Event\CollectThemeStylesheets';

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