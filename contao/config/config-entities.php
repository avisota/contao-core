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
 * Entities
 */
$GLOBALS['DOCTRINE_ENTITY_NAMESPACE_ALIAS']['Avisota\Contao'] = 'Avisota\Contao\Entity';

$GLOBALS['DOCTRINE_ENTITY_NAMESPACE_MAP']['orm_avisota'] = 'Avisota\Contao\Entity';

$GLOBALS['DOCTRINE_ENTITY_CLASS']['Avisota\Contao\Entity\Layout']    = 'Avisota\Contao\Entity\AbstractLayout';
$GLOBALS['DOCTRINE_ENTITY_CLASS']['Avisota\Contao\Entity\Recipient'] = 'Avisota\Contao\Entity\AbstractRecipient';
$GLOBALS['DOCTRINE_ENTITY_CLASS']['Avisota\Contao\Entity\Message']   = 'Avisota\Contao\Entity\AbstractMessage';

$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_layout';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_mailing_list';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_member_subscription';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_message';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_message_category';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_message_content';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_theme';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_queue';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_blacklist';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_source';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_subscription';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_subscription_log';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_salutation';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_salutation_group';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_transport';
