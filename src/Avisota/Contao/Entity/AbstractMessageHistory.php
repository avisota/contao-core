<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  MEN AT WORK 2013
 * @package    avisota
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Entity;

use Contao\Doctrine\ORM\Entity;

abstract class AbstractMessageHistory extends Entity
{
	
	/**
	 * 
	 * @param type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}
}
