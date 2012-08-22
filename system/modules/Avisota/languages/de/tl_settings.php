<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['avisota_backend_send']                      = array('Backend-Versand', 'Wählen Sie hier, ob der Versand über das Backend möglich sein soll.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_max_send_time']                     = array('Versanddauer', 'Anzahl Sekunden die pro Zyklus vergehen dürfen, bevor ein neuer Zyklus getriggert wird.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_max_send_count']                    = array('Versandanzahl', 'Anzahl E-Mails die pro Zyklus versendet werden, bevor ein neuer Zyklus getriggert wird.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_max_send_timeout']                  = array('Zyklenpause', 'Anzahl Sekunden die zwischen zwei Zyklen gewartet wird.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_hold_on_errors']                    = array('Bei Fehlern Versand unterbrechen', 'Wenn zu viele Fehler auftreten, wird der Benutzer gefragt, ob der Versand fortgesetzt werden soll oder nicht.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_max_send_error_count']              = array('Maximale Fehleranzahl / Zyklus', 'Geben Sie hier die maximale Anzahl Fehler an, die in einem Zyklus auftreten dürfen.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_max_send_error_rate']               = array('Maximale Fehlerrate in % / Zyklus', 'Geben Sie hier das maximale Fehlerverhältnis von fehlerhaften zu versendeten Newsletter an, die in einem Zyklus auftreten dürfen. Die Skala geht von 1 (100%) bis 0 (0%). Die Fehlerrate wird nur dann ermittelt, wenn eine gewisse Anzahl Newsletter versendet wurde, die Anzahl bestimmt sich aus der "Maximale Fehleranzahl / Zyklus" (MaxFail). Um die Fehlerrate (MaxFailRate) zu ermitteln müssen mindestens <code>X = 2 * MaxFail</code> Newsletter versendet werden. Beispiel: MaxFail:=5; MaxFailRate:=.1; X = 2 * 5 = 10 &rArr; Ab 11 versendeten Newslettern, darf maximal 1.1 (= 11 * .1) Fehler auftreten!');
$GLOBALS['TL_LANG']['tl_settings']['avisota_dont_disable_recipient_on_failure'] = array('Abonnent bei Fehlversand nicht deaktivieren', 'Deaktiviert die Deaktivierung von Abonnenten, wenn der Versand an sie fehlgeschlagen ist.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_dont_disable_member_on_failure']    = array('Mitglied bei Fehlversand nicht deaktivieren', 'Deaktiviert die Deaktivierung von Mitgliedern, wenn der Versand an sie fehlgeschlagen ist.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_merge_member_details']              = array('Mitgliederdaten für personalisierung mitverwenden', 'Mischt die Mitgliederdaten zu den persönlichen Daten aus dem Newslettersystem.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_chart_highstock']                   = array('Highstock Charts verwenden (frei für privaten und nicht-kommerziellen Gebrauch, Lizenz beachten!)', 'Beachten Sie die <a href="http://www.highcharts.com/license" onclick="window.open(this.href); return false;"><u>Lizenz</u></a> und <a href="http://www.highcharts.com/component/content/article/uncategorised/32-highstock-license-and-pricing" onclick="window.open(this.href); return false;"><u>Preisauszeichnung</u></a>. Verwendet die <a href="http://www.highcharts.com/" onclick="window.open(this.href); return false;"><u>Highstock Charts</u></a> anstelle von jqplot.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_chart_highstock_confirmed']         = array('Ich/Wir bestätigen, dass dieses Newsletter-Projekt den Lizenzbestimmungen entspricht.', 'Beachten Sie die <a href="http://www.highcharts.com/license" onclick="window.open(this.href); return false;"><u>Lizenz</u></a> und <a href="http://www.highcharts.com/component/content/article/uncategorised/32-highstock-license-and-pricing" onclick="window.open(this.href); return false;"><u>Preisauszeichnung</u></a>. Hiermit bestätigen Sie dass dieses Newsletter-Projekt privat oder für nicht-kommerzielle Zwecke benutzt wird oder eine entsprechende Lizenz erworben wurde');
$GLOBALS['TL_LANG']['tl_settings']['avisota_developer_mode']                    = array('Entwicklermodus', 'Aktiviert den Entwicklermodus.');
$GLOBALS['TL_LANG']['tl_settings']['avisota_developer_email']                   = array('Entwickler E-Mail', 'Im Entwicklermodus werden alle E-Mails an diese Adresse umgeleitet.');


/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_settings']['avisota_legend'] = 'Avisota Newsletter System';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_settings']['avisota_backend_send_modes']['enabled']  = 'aktiviert';
$GLOBALS['TL_LANG']['tl_settings']['avisota_backend_send_modes']['admin']    = 'nur Admins';
$GLOBALS['TL_LANG']['tl_settings']['avisota_backend_send_modes']['disabled'] = 'deaktiviert';
