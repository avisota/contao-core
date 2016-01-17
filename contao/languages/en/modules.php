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

/**
 * Module
 */
$GLOBALS['TL_LANG']['MOD']['avisota-core'] = array(
    'Avisota - Core',
    'Basic integration of Avisota for Contao.'
);

/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['avisota']                   = array('Avisota newsletter');
$GLOBALS['TL_LANG']['MOD']['avisota_outbox']            = array('Outbox');
$GLOBALS['TL_LANG']['MOD']['avisota_newsletter']        = array('Messages');
$GLOBALS['TL_LANG']['MOD']['avisota_recipients']        = array('Recipients');
$GLOBALS['TL_LANG']['MOD']['avisota_config']            = array('Settings');
$GLOBALS['TL_LANG']['MOD']['avisota_settings']          = array(
    'Avisota system settings',
    'Manage basic avisota system settings'
);
$GLOBALS['TL_LANG']['MOD']['avisota_salutation']        = array(
    'Salutation',
    'Manage salutations.'
);
$GLOBALS['TL_LANG']['MOD']['avisota_config:recipient']  = array('Recipient config');
$GLOBALS['TL_LANG']['MOD']['avisota_mailing_list']      = array(
    'Mailing lists',
    'Manage the mailing lists that can be subscribed.'
);
$GLOBALS['TL_LANG']['MOD']['avisota_recipient_source']  = array('Recipient sources', 'Manager recipient sources.');
$GLOBALS['TL_LANG']['MOD']['avisota_config:newsletter'] = array('Message config');
$GLOBALS['TL_LANG']['MOD']['avisota_newsletter_draft']  = array(
    'Message drafts',
    'Manager newsletter drafts to create new newsletters from.'
);
$GLOBALS['TL_LANG']['MOD']['avisota_theme']             = array(
    'Theme',
    'Manage themes, including templates, stylesheets and layout settings for newsletters.'
);
$GLOBALS['TL_LANG']['MOD']['avisota_config:transport']  = array('Transport config');
$GLOBALS['TL_LANG']['MOD']['avisota_transport']         = array('Transports', 'Manager transport modules.');
$GLOBALS['TL_LANG']['MOD']['avisota_queue']             = array('Queues', 'Manager transport queues.');
$GLOBALS['TL_LANG']['MOD']['avisota_support']           = array('Support');

/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['avisota'] = 'Avisota';
