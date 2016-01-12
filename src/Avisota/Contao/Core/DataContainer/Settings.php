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

namespace Avisota\Contao\Core\DataContainer;

use Avisota\Contao\Entity\Message;
use Contao\Doctrine\ORM\EntityHelper;

class Settings extends \Backend
{
    public function onload_callback()
    {
        if (!is_dir(TL_ROOT . '/system/modules/avisota/highstock')
            || !is_file(TL_ROOT . '/system/modules/avisota/highstock/js/highstock.js')
        ) {
            $GLOBALS['TL_DCA']['tl_avisota_settings']['fields']['avisota_chart_highstock_confirm']['input_field_callback'] = array(
                'tl_avisota_settings',
                'renderMissingHighstockField'
            );
        }
    }

    /**
     * @param \DataContainer $dc
     * @param                $label
     *
     * @return mixed
     */
    public function renderMissingHighstockField($dc, $label)
    {
        return $GLOBALS['TL_LANG']['tl_avisota_settings']['missing_highstock'];
    }
}
