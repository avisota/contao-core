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

namespace Avisota\Contao\Core\Backend;

use BackendTemplate;
use Contao\Doctrine\ORM\EntityHelper;

class CustomMenu
{
    static public function hookGetUserNavigation(array $navigation, $showAll)
    {
        if (TL_MODE == 'BE' && is_array($navigation['avisota']['modules'])) {
            try {
                $GLOBALS['TL_CSS']['avisota-be-global'] = 'assets/avisota/core/css/be_global.css';

                if (Outbox::isEmpty()) {
                    $navigation['avisota']['modules']['avisota_outbox']['class'] .= ' avisota_outbox_empty';
                }
            } catch (\Exception $exception) {
                // silently ignore
            }
        }
        return $navigation;
    }
}
