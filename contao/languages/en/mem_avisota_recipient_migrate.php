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
 * Fields
 */
$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['channels']             = array(
	'Channels',
	'Please choose the channels and the target mailing lists.'
);
$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['channels_channel']     = array('Channel');
$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['channels_mailingList'] = array('Mailing list');
$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['overwrite']            = array(
	'Overwrite existing',
	'Overwrite existing recipients.'
);
$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['importFromMembers']    = array(
	'Import personals from Members',
	'Import the personal data from the members.'
);

/**
 * Legends
 */
$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['migrate_legend'] = 'Migrate recipients';

/**
 * Messages
 */
$GLOBALS['TL_LANG']['mem_avisota_recipient_migrate']['migrated'] = '%d recipients migrated, %d skipped.';
