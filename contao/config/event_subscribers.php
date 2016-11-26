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

use Avisota\Contao\Core\Backend\Transport;
use Avisota\Contao\Core\Controller\CopyController;
use Avisota\Contao\Core\DataContainer\Queue;
use Avisota\Contao\Core\DataContainer\RecipientSource;
use Avisota\Contao\Core\EventSubscriber;

return array(
    new Queue(),
    new RecipientSource(),
    new EventSubscriber(),
    function () {
        return $GLOBALS['container']['avisota.core.options-builder'];
    },
    new CopyController(),
    new Transport(),
);
