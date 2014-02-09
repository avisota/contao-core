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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['recipient']     = array(
	'Abonnent',
	'Wählen Sie hier den Abonnenten aus.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['confirmations'] = array(
	'Bestätigungen versenden',
	'Noch nicht versendete Bestätiungsmails versenden.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['notifications'] = array(
	'Erinnerungen versenden',
	'Erinnerungen für noch nicht bestätigte Abonnements versenden.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['overdue']       = array(
	'Überfällig',
	'Überfällige Abonnements, für die eine Bestätigung- und alle Erinnerungen versendet wurden.'
);


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['edit']             = 'Abonnent benachrichtigen';
$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['confirmationSent'] = 'Bestätigungsmail gesendet am %s';
$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['reminderSent']     = '%d. Erinnerung gesendet am %s';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient_notify']['notify_legend'] = 'Benachrichtung';
