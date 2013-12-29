<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['avisota']['avisota_subscribe']    = 'Avisota\Contao\Module\Subscribe';
$GLOBALS['FE_MOD']['avisota']['avisota_unsubscribe']  = 'Avisota\Contao\Module\Unsubscribe';
$GLOBALS['FE_MOD']['avisota']['avisota_subscription'] = 'Avisota\Contao\Module\Subscription';
$GLOBALS['FE_MOD']['avisota']['avisota_list']         = 'Avisota\Contao\Module\List';
$GLOBALS['FE_MOD']['avisota']['avisota_reader']       = 'Avisota\Contao\Module\Reader';
