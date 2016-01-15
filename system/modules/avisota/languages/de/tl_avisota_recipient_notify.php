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
