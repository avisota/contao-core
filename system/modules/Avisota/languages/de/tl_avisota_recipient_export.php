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
$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['delimiter']                = array('Feldtrenner', 'Wählen Sie hier das Zeichen aus, nach dem die einzelnen Felder getrennt sind.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['enclosure']                = array('Texttrenner', 'Wählen Sie hier das Zeichen aus, nach dem der Text getrennt ist.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['datim']                    = array('Datum/Zeit Format', 'Wählen Sie hier das Format, in dem Datum und Zeit exportiert werden sollen. (Zum Zurücksetzen auf den Standardwert lassen Sie das Feld einfach leer!)');
$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['fields']                   = array('Felder', 'Wählen Sie hier, wie die Felder die exportiert werden sollen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['statistic:links']          = array('Statistik - Links (nicht importierbar)', 'Angeklickte Links');
$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['statistic:links:category'] = array('Statistik - Links aus %s (nicht importierbar)', 'Angeklickte Links aus %s');

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['double']      = 'Doppelte Anführungszeichen "';
$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['single']      = 'Einfache Anführungszeichen \'';
$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['edit']        = 'CSV-Export';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_export']['format_legend']    = 'CSV Format';

?>