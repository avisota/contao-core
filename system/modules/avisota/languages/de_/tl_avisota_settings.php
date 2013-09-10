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
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations'] = array(
	'Anredeformen',
	'Tragen Sie hier die möglichen Anredeformen ein. Wählen Sie dazu aus, aus welchen Teilen sich der Name zusammen setzt, dem Titel (z.B. Dr.), dem Vornamen und dem Nachnamen.'
);
// subscription
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_subscribe_mail_plain']   = array(
	'Anmelden Plain Text E-Mail-Template',
	''
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_subscribe_mail_html']    = array(
	'Anmelden HTML E-Mail-Template',
	''
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_unsubscribe_mail_plain'] = array(
	'Abmelden Plain Text E-Mail-Template',
	''
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_unsubscribe_mail_html']  = array(
	'Abmelden HTML E-Mail-Template',
	''
);
// notification
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_send_notification']                = array(
	'Erinnerung senden',
	'Sendet eine Erinnerung, wenn das Abonnement nach einigen Tagen noch nicht aktiviert wurde.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_time']                = array(
	'Tage bis zur Erinnerung',
	'Anzahl Tage nach denen die Erinnerung verschickt werden soll.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_notification_count']               = array(
	'Anzahl Erinnerungen',
	'Anzahl Erinnerungen die maximal verschickt werden soll. Die Zeit wird jeweils um 50% verlängert.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_notification_mail_plain'] = array(
	'Erinnerung Plain Text E-Mail-Template',
	''
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_template_notification_mail_html']  = array(
	'Erinnerung HTML E-Mail-Template',
	''
);
// cleanup
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_do_cleanup']   = array(
	'Unbestätige Abonnements löschen',
	'Löscht unbestätigte Abonnements nach einigen Tagen.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_cleanup_time'] = array(
	'Tage bis zur Löschung',
	'Anzahl Tage nach denen das Abonnement wieder gelöscht wird.'
);
// transport
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_default_transport']                 = array(
	'Standard Transportmodul',
	'Wählen Sie hier das Standard Transportmodul aus.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_time']                     = array(
	'Versanddauer',
	'Anzahl Sekunden die pro Zyklus vergehen dürfen, bevor ein neuer Zyklus getriggert wird.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_count']                    = array(
	'Versandanzahl',
	'Anzahl E-Mails die pro Zyklus versendet werden, bevor ein neuer Zyklus getriggert wird.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_timeout']                  = array(
	'Zyklenpause',
	'Anzahl Sekunden die zwischen zwei Zyklen gewartet wird.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_dont_disable_recipient_on_failure'] = array(
	'Abonnent bei Fehlversand nicht deaktivieren',
	'Deaktiviert die Deaktivierung von Abonnenten, wenn der Versand an sie fehlgeschlagen ist.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_dont_disable_member_on_failure']    = array(
	'Mitglied bei Fehlversand nicht deaktivieren',
	'Deaktiviert die Deaktivierung von Mitgliedern, wenn der Versand an sie fehlgeschlagen ist.'
);
// developer
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_developer_mode']  = array(
	'Entwicklermodus',
	'Aktiviert den Entwicklermodus.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_developer_email'] = array(
	'Entwickler E-Mail',
	'Im Entwicklermodus werden alle E-Mails an diese Adresse umgeleitet.'
);


/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_avisota_settings']['edit']                           = 'Newslettersystem konfigurieren';
$GLOBALS['TL_LANG']['tl_avisota_settings']['recipients_legend']              = 'Abonnenten';
$GLOBALS['TL_LANG']['tl_avisota_settings']['subscription_legend']            = 'Abonnement';
$GLOBALS['TL_LANG']['tl_avisota_settings']['notification_legend']            = 'Erinnerung';
$GLOBALS['TL_LANG']['tl_avisota_settings']['cleanup_legend']                 = 'Aufräumen';
$GLOBALS['TL_LANG']['tl_avisota_settings']['transport_legend']               = 'Versand';
$GLOBALS['TL_LANG']['tl_avisota_settings']['developer_legend']               = 'Entwickler';
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_salutation'] = array('Anrede');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_title']      = array('Titel');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_forename']   = array('Vorname');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_surname']    = array('Nachname');
