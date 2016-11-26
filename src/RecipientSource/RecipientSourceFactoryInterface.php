<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\RecipientSource;

use Avisota\Contao\Entity\RecipientSource;

/**
 * The recipient source factory interface.
 */
interface RecipientSourceFactoryInterface
{
    /**
     * Create the recipient source.
     *
     * @param RecipientSource $recipientSource
     *
     * @return mixed
     */
    public function createRecipientSource(RecipientSource $recipientSource);
}
