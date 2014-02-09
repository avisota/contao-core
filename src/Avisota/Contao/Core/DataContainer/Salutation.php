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

namespace Avisota\Contao\Core\DataContainer;

class Salutation
{
	/**
	 * Add the type of content element
	 *
	 * @param array
	 *
	 * @return string
	 */
	public function addElement($contentData)
	{
		return sprintf(
			'<div>%s</div>' . "\n",
			$contentData['salutation']
		);
	}
}
