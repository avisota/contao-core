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

namespace Avisota\Contao\Salutation;

use Avisota\Contao\Entity\Salutation;
use Avisota\Recipient\RecipientInterface;

/**
 *
 */
interface DeciderInterface
{
	public function accept(RecipientInterface $recipient, Salutation $salutation);
}
