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

namespace Avisota\Contao\Core\RecipientSource;

use Avisota\Contao\Entity\RecipientSource;

/**
 * Interface RecipientSourceFactoryInterface
 *
 * @package Avisota\Contao\Core\RecipientSource
 */
interface RecipientSourceFactoryInterface
{
    /**
     * @param RecipientSource $recipientSource
     *
     * @return mixed
     */
    public function createRecipientSource(RecipientSource $recipientSource);
}
