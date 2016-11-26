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

namespace Avisota\Contao\Core\Backend;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A BackendModule implementation that use Twig as template engine.
 */
class Support extends \TwigBackendModule
{
    /**
     * The template.
     *
     * @var string
     */
    protected $strTemplate = 'avisota/backend/support';

    /**
     * Compile the current element.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function compile()
    {
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('avisota_support')
        );
    }
}
