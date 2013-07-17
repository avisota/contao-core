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

use Avisota\Contao\Event\ResolveStylesheetEvent;
use Contao\Doctrine\ORM\Entity;
use Symfony\Component\EventDispatcher\EventDispatcher;

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

		if ($category->getBoilerplates() ||
			$category->getLayoutMode() == 'byMessage'
		) {
			if ($this instanceof Proxy) {
				$this->__load();
			}

			$layout = $this->layout;
		}
		else if ($category->getLayoutMode() == 'byMessageOrCategory') {
			$layout = $this->getLayout();
			if (!$layout) {
				$layout = $category->getLayout();
			}
		}
		else if ($category->getLayoutMode() == 'byCategory') {
			$layout = $category->getLayout();
		}
		else {
			throw new \RuntimeException('Could not find layout for message ' . $this->getId());
		}

		return $layout;
	}
}
