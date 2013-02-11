<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
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
// statistics
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_statistic']                   = array(
	'Statistische Daten erfassen',
	'Statistische Daten erfassen.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_data_privacy_statement_page'] = array(
	'Seite zur Datenschutzvereinbarung',
	'Wählen Sie hier die Seite mit der Datenschutzvereinbarung aus.'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_statistic_personalized']      = array(
	'Statistische Daten personalisieren',
	'Die statistisch Daten werden personalisiert erfasst. In manchen Ländern ist die Einwilligung zum erfassen personenbezogener Daten nicht erforderlich, wenn Sie bzw. Ihre Zielgruppe sich in einem derartigen Land befinden können Sie hiermit die Checkbox zur Einverständniserklärung deaktivieren. Beachten Sie, wenn Sie E-Mails ins Ausland verschicken, unterstehen Sie gemäß dem Grundsatz des "Marktortprinzip" grundsätzlich ausländischem Recht. Lesen Sie hierzu die Richtlinie für zulässiges Onlinemarketing, Stichwort: Marktortprinzip!'
);
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_chart']                       = array(
	'Chart Rendering',
	'Wählen Sie eine Methode aus, wie die Graphen gerendert werden sollen.'
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
$GLOBALS['TL_LANG']['tl_avisota_settings']['statistics_legend']              = 'Statistik und Tracking';
$GLOBALS['TL_LANG']['tl_avisota_settings']['developer_legend']               = 'Entwickler';
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_salutation'] = array('Anrede');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_title']      = array('Titel');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_firstname']  = array('Vorname');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_lastname']   = array('Nachname');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_settings']['unpersonalized']    = 'nicht personalisieren';
$GLOBALS['TL_LANG']['tl_avisota_settings']['with_aggrement']    = 'Nach Einwilligung personalisieren';
$GLOBALS['TL_LANG']['tl_avisota_settings']['without_aggrement'] = 'Ohne Einwilligung personalisieren';
$GLOBALS['TL_LANG']['tl_avisota_settings']['jqplot']            = 'jqPlot (JavaScript)';
$GLOBALS['TL_LANG']['tl_avisota_settings']['highstock']         = 'Highstock Charts (JavaScript)';
$GLOBALS['TL_LANG']['tl_avisota_settings']['pchart']            = 'pChart (PHP)';
$GLOBALS['TL_LANG']['tl_avisota_settings']['missing_highstock'] = '<div>
<h3>Highstock Charts</h3>
<p>Die <a href="http://www.highcharts.com/" onclick="window.open(this.href); return false;">Highstock Charts</a> werden nicht mehr mit Avisota ausgeliefert.<br>Bitte installieren Sie die Library von Hand nach, wenn Sie diese nutzen möchten.</p>
</div>';
