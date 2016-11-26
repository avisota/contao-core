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
use ContaoCommunityAlliance\DcGeneral\DC_General;

/**
 * The avisota core nested menu.
 */
class NestedMenu extends \Controller
{
    /**
     * The nested menu instance.
     *
     * @var NestedMenu
     */
    protected static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return NestedMenu
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new NestedMenu();
        }
        return self::$instance;
    }

    /**
     * Import the Config and Session instances
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * The hook for nested menu pre defined content.
     *
     * @param string $do The do parameter.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
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

    /**
     * The hook for nested menu post defined content.
     *
     * @param string $do The do parameter.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function hookNestedMenuPostContent($do)
    {
        if ($do == 'avisota_config') {
            $general         = new DC_General('orm_avisota_transport');
            $environment     = $general->getEnvironment();
            $eventDispatcher = $environment->getEventDispatcher();
            $translator      = $environment->getTranslator();

            $eventDispatcher->dispatch(
                ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
                new LoadLanguageFileEvent('avisota_promotion')
            );

            $context = array(
                'opensource' => $translator->translate('opensource', 'avisota_promotion'),
                'partners'   => $translator->translate('partners', 'avisota_promotion'),
                'copyright'  => 'Avisota newsletter and mailing system &copy; 2013-2016 way.vision and all '
                                . '<a href="https://github.com/avisota/contao/graphs/contributors" '
                                . 'target="_blank">contributors</a>',
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
