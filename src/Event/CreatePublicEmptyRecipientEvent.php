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

namespace Avisota\Contao\Core\Event;

use Avisota\Recipient\RecipientInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CreatePublicEmptyRecipientEvent
 *
 * @package Avisota\Contao\Core\Event
 */
class CreatePublicEmptyRecipientEvent extends BaseCreateRecipientEvent
{
}
