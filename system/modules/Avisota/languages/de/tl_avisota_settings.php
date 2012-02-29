<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations']                       = array('Anredeformen', 'Tragen Sie hier die möglichen Anredeformen ein. Wählen Sie dazu aus, aus welchen Teilen sich der Name zusammen setzt, dem Titel (z.B. Dr.), dem Vornamen und dem Nachnamen.');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_time']                     = array('Versanddauer', 'Anzahl Sekunden die pro Zyklus vergehen dürfen, bevor ein neuer Zyklus getriggert wird.');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_count']                    = array('Versandanzahl', 'Anzahl E-Mails die pro Zyklus versendet werden, bevor ein neuer Zyklus getriggert wird.');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_max_send_timeout']                  = array('Zyklenpause', 'Anzahl Sekunden die zwischen zwei Zyklen gewartet wird.');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_dont_disable_recipient_on_failure'] = array('Abonnent bei Fehlversand nicht deaktivieren', 'Deaktiviert die Deaktivierung von Abonnenten, wenn der Versand an sie fehlgeschlagen ist.');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_dont_disable_member_on_failure']    = array('Mitglied bei Fehlversand nicht deaktivieren', 'Deaktiviert die Deaktivierung von Mitgliedern, wenn der Versand an sie fehlgeschlagen ist.');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_chart_highstock']                   = array('Highstock Charts verwenden (frei für privaten und nicht-kommerziellen Gebrauch, Lizenz beachten!)', 'Beachten Sie die <a href="http://www.highcharts.com/license" onclick="window.open(this.href); return false;"><u>Lizenz</u></a> und <a href="http://www.highcharts.com/component/content/article/uncategorised/32-highstock-license-and-pricing" onclick="window.open(this.href); return false;"><u>Preisauszeichnung</u></a>. Verwendet die <a href="http://www.highcharts.com/" onclick="window.open(this.href); return false;"><u>Highstock Charts</u></a> anstelle von jqplot.');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_chart_highstock_confirmed']         = array('Ich/Wir bestätigen, dass dieses Newsletter-Projekt den Lizenzbestimmungen entspricht.', 'Beachten Sie die <a href="http://www.highcharts.com/license" onclick="window.open(this.href); return false;"><u>Lizenz</u></a> und <a href="http://www.highcharts.com/component/content/article/uncategorised/32-highstock-license-and-pricing" onclick="window.open(this.href); return false;"><u>Preisauszeichnung</u></a>. Hiermit bestätigen Sie dass dieses Newsletter-Projekt privat oder für nicht-kommerzielle Zwecke benutzt wird oder eine entsprechende Lizenz erworben wurde');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_developer_mode']                    = array('Entwicklermodus', 'Aktiviert den Entwicklermodus.');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_developer_email']                   = array('Entwickler E-Mail', 'Im Entwicklermodus werden alle E-Mails an diese Adresse umgeleitet.');


/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_avisota_settings']['recipients_legend']              = 'Abonnenten';
$GLOBALS['TL_LANG']['tl_avisota_settings']['transport_legend']               = 'Versand';
$GLOBALS['TL_LANG']['tl_avisota_settings']['backend_legend']                 = 'Backend';
$GLOBALS['TL_LANG']['tl_avisota_settings']['developer_legend']               = 'Entwickler';
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_salutation'] = array('Anrede');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_title']      = array('Titel');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_firstname']  = array('Vorname');
$GLOBALS['TL_LANG']['tl_avisota_settings']['avisota_salutations_lastname']   = array('Nachname');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_settings']['missing_hightstock'] = '<div>
<h3>Highstock Charts</h3>
<p>Die <a href="http://www.highcharts.com/" onclick="window.open(this.href); return false;">Highstock Charts</a> werden nicht mehr mit Avisota ausgeliefert. Bitte installieren Sie die Library von Hand nach, wenn Sie diese nutzen möchten.</p>
</div>';
