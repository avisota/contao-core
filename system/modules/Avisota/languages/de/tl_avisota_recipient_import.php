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
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['source']      = array('Import-Quelle', 'Wählen Sie hier die Datei aus, die Sie importieren möchten.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['upload']      = array('Import-Upload', 'Laden Sie eine Datei hoch, die Sie importieren möchten.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['delimiter']   = array('Feldtrenner', 'Wählen Sie hier das Zeichen aus, nach dem die einzelnen Felder getrennt sind.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['enclosure']   = array('Texttrenner', 'Wählen Sie hier das Zeichen aus, nach dem der Text getrennt ist.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['personals']   = array('Import mit persönlichen Daten', 'Wählen Sie diese Option wenn Ihre CVS nicht nur die E-Mail Adresse, sondern auch persönliche Daten beinhaltet.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['double']  = 'Doppelte Anführungszeichen "';
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['single']  = 'Einfache Anführungszeichen \'';
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['confirm'] = '%s neue Abonnenten wurden importiert.';
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['invalid'] = '%s ungültige Einträge wurden übersprungen.';
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['edit']    = 'CSV-Import';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['import_legend']    = 'Import';
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['format_legend']    = 'CSV Format';
$GLOBALS['TL_LANG']['tl_avisota_recipient_import']['personals_legend'] = 'Persönliche Daten';

?>