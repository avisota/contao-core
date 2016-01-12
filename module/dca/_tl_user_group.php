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

use Avisota\Contao\Core\CoreEvents;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory;

/**
 * Extend default palette
 */
$GLOBALS['TL_DCA']['tl_user_group']['palettes']['default'] = str_replace(
    'formp;',
    'formp;{avisota_legend},avisota_recipient_lists,avisota_recipient_list_permissions,avisota_recipient_permissions,avisota_newsletter_categories,avisota_newsletter_category_permissions,avisota_newsletter_permissions;',
    $GLOBALS['TL_DCA']['tl_user_group']['palettes']['default']
);


/**
 * Add fields to tl_user_group
 */
$GLOBALS['TL_DCA']['tl_user_group']['fields']['avisota_recipient_lists'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_user_group']['avisota_recipient_lists'],
    'exclude'          => true,
    'inputType'        => 'checkbox',
    'options_callback' => CreateOptionsEventCallbackFactory::createCallback(CoreEvents::CREATE_MAILING_LIST_OPTIONS),
    'eval'             => array('multiple' => true)
);

$GLOBALS['TL_DCA']['tl_user_group']['fields']['avisota_recipient_list_permissions'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_user_group']['avisota_recipient_list_permissions'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => array('create', 'delete'),
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => array('multiple' => true)
);

$GLOBALS['TL_DCA']['tl_user_group']['fields']['avisota_recipient_permissions'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_user_group']['avisota_recipient_permissions'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => array('create', 'delete', 'delete_no_blacklist'),
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => array('multiple' => true)
);

$GLOBALS['TL_DCA']['tl_user_group']['fields']['avisota_newsletter_categories'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_user_group']['avisota_newsletter_categories'],
    'exclude'          => true,
    'inputType'        => 'checkbox',
    'options_callback' => CreateOptionsEventCallbackFactory::createCallback('avisota.create-message-category-options'),
    'eval'             => array('multiple' => true)
);

$GLOBALS['TL_DCA']['tl_user_group']['fields']['avisota_newsletter_category_permissions'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_user_group']['avisota_newsletter_category_permissions'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => array('create', 'delete'),
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => array('multiple' => true)
);

$GLOBALS['TL_DCA']['tl_user_group']['fields']['avisota_newsletter_permissions'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_user_group']['avisota_newsletter_permissions'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => array('create', 'delete', 'send'),
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => array('multiple' => true)
);
