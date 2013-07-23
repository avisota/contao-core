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

namespace Avisota\Contao;

use Exception;

/**
 * Class Backend
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class ReplaceInsertTagsHook extends \Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function replaceInsertTags($strBuffer, $blnCache = false)
	{
		return parent::replaceInsertTags($strBuffer, $blnCache);
	}
}
