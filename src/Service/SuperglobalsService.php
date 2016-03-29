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

namespace Avisota\Contao\Core\Service;

/**
 * Class SuperglobalsService
 *
 * @package Avisota\Contao\Core\Service
 */
class SuperglobalsService
{
    protected $global = 'GLOBALS';

    protected $language = 'TL_LANG';

    protected $dataContainer = 'TL_LANG';

    /**
     * SuperglobalsService constructor.
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function __construct()
    {
        global $TL_LANG,
               $TL_DCA;

        $this->language      = &$TL_LANG;
        $this->dataContainer = &$TL_DCA;
    }

    /**
     * @param $path
     *
     * @return null|string
     */
    public function getLanguage($path)
    {
        $chunks = explode('/', $path);

        $languageStorage = $this->language;
        foreach ($chunks as $chunk) {
            if (!array_key_exists($chunk, $languageStorage)
                && array_reverse($chunks)[0] === $chunk
            ) {
                return $chunk;
            }

            if (!array_key_exists($chunk, $languageStorage)) {
                return null;
            }

            $languageStorage = $languageStorage[$chunk];
        }

        return $languageStorage;
    }

    /**
     * @param $path
     *
     * @return null|string
     */
    public function getFromDataContainer($path)
    {
        $chunks = explode('/', $path);

        $languageStorage = $this->dataContainer;
        foreach ($chunks as $chunk) {
            if (!array_key_exists($chunk, $languageStorage)
                && array_reverse($chunks)[0] === $chunk
            ) {
                return $chunk;
            }

            if (!array_key_exists($chunk, $languageStorage)) {
                return null;
            }

            $languageStorage = $languageStorage[$chunk];
        }

        return $languageStorage;
    }
}
