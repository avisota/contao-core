<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core;

use Exception;

/**
 * Class Backend
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
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
