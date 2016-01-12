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

namespace Avisota\Contao\Core\Backend;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class NestedMenu extends \Controller
{
    /**
     * @var Backend
     */
    protected static $instance = null;

    /**
     * @static
     * @return Backend
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new NestedMenu();
        }
        return self::$instance;
    }

    protected function __construct()
    {
        parent::__construct();
    }

    public function hookNestedMenuPreContent($do)
    {
        if ($do == 'avisota_config') {
            return sprintf(
                '<div class="avisota-logo"><a href="http://avisota.org" target="_blank">%s</a></div>',
                \Image::getHtml(
                    'assets/avisota/core/images/logo.svg',
                    'Avisota newsletter and mailing system',
                    'width="300"'
                )
            );
        }
    }

    public function hookNestedMenuPostContent($do)
    {
        if ($do == 'avisota_config') {
            /** @var EventDispatcher $eventDispatcher */
            $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

            $eventDispatcher->dispatch(
                ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
                new LoadLanguageFileEvent('avisota_promotion')
            );

            $context = array(
                'opensource' => $GLOBALS['TL_LANG']['avisota_promotion']['opensource'],
                'partners'   => $GLOBALS['TL_LANG']['avisota_promotion']['partners'],
                'copyright'  => 'Avisota newsletter and mailing system &copy; 2013-2014 bit3 UG and all <a href="https://github.com/avisota/contao/graphs/contributors" target="_blank">contributors</a>',
                'disclaimer' => <<<HTML
Avisota use icons from the <a href="http://www.famfamfam.com/" target="_blank">famfamfam silk icons</a> and
<a href="http://www.picol.org/" target="_blank">Picol Vector icons</a>,
licensed under the <a href="http://creativecommons.org/licenses/by/3.0/">CC-BY-3.0.</a>
HTML
                ,
            );

            $template = new \TwigTemplate('avisota/backend/config_footer', 'html5');
            return $template->parse($context);
        }
    }
}
