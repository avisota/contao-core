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

namespace Avisota\Contao\Core;

/**
 * Class Backend
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
class ReplaceInsertTagsHook extends \Controller
{
    /**
     * Import the Config and Session instances
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Replace insert tags with their values
     *
     * @param string  $strBuffer The text with the tags to be replaced
     * @param boolean $blnCache  If false, non-cacheable tags will be replaced
     *
     * @return string The text with the replaced tags
     */
    public function replaceInsertTags($strBuffer, $blnCache = false)
    {
        return \Controller::replaceInsertTags($strBuffer, $blnCache);
    }
}
