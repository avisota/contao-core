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

namespace Avisota\Contao\Entity;

use Contao\Doctrine\ORM\Entity;

abstract class AbstractMessage extends Entity
{
	/**
	 * @var string
	 */
	protected $language;

	function __construct()
	{
		if (isset($GLOBALS['TL_LANGUAGE'])) {
			$this->language = $GLOBALS['TL_LANGUAGE'];
		}
	}

	/**
	 * @return Layout
	 */
	public function getLayout()
	{
		$category = $this->getCategory();

		switch ($category->getLayoutMode()) {
			case 'byMessage':
				$layout = $this->getLayout();
				break;

			case 'byMessageOrCategory':
				$layout = $this->getLayout();
				if ($layout) {
					break;
				}

			case 'byCategory':
				$layout = $category->getLayout();
				break;
		}

		return $layout;
	}
}
