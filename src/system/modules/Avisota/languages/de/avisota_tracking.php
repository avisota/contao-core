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
 * Avisota tracking
 */
$GLOBALS['TL_LANG']['avisota_tracking']['headline']         = 'Analytics und Tracking';
$GLOBALS['TL_LANG']['avisota_tracking']['newsletter_label'] = 'Newsletter';
$GLOBALS['TL_LANG']['avisota_tracking']['recipient_label']  = 'Abonnent';

$GLOBALS['TL_LANG']['avisota_tracking']['empty_stats']      = 'Zur Zeit liegen keine Daten vor!';

$GLOBALS['TL_LANG']['avisota_tracking']['export'] = array('CSV-Export', 'Export der Statistik in eine CSV-Datei.');

$GLOBALS['TL_LANG']['avisota_tracking']['col_sum']      = 'Summe';
$GLOBALS['TL_LANG']['avisota_tracking']['col_percent']  = '% / Versendet';
$GLOBALS['TL_LANG']['avisota_tracking']['col_percent2'] = '% / Gelesen';

$GLOBALS['TL_LANG']['avisota_tracking']['newsletter']['stats_legend'] = 'Lese- und Reaktionsverhalten';
$GLOBALS['TL_LANG']['avisota_tracking']['newsletter']['sends']  = 'Newsletter';
$GLOBALS['TL_LANG']['avisota_tracking']['newsletter']['reads']  = 'Gelesen';
$GLOBALS['TL_LANG']['avisota_tracking']['newsletter']['reacts'] = 'Reaktion';

$GLOBALS['TL_LANG']['avisota_tracking']['newsletter']['links_legend'] = 'Links';
$GLOBALS['TL_LANG']['avisota_tracking']['newsletter']['url'] = 'URL';
$GLOBALS['TL_LANG']['avisota_tracking']['newsletter']['hits'] = 'Klicks';

$GLOBALS['TL_LANG']['avisota_tracking']['recipient']['stats_legend'] = 'Lese- und Reaktionsverhalten';
$GLOBALS['TL_LANG']['avisota_tracking']['recipient']['sends']  = 'Newsletter';
$GLOBALS['TL_LANG']['avisota_tracking']['recipient']['reads']  = 'Gelesen';
$GLOBALS['TL_LANG']['avisota_tracking']['recipient']['reacts'] = 'Reaktion';

$GLOBALS['TL_LANG']['avisota_tracking']['recipient']['newsletters_legend'] = 'Newsletters';
$GLOBALS['TL_LANG']['avisota_tracking']['recipient']['readed'] = 'Gelesen';
$GLOBALS['TL_LANG']['avisota_tracking']['recipient']['newsletter'] = 'Newsletter';

$GLOBALS['TL_LANG']['avisota_tracking']['recipient']['links_legend'] = 'Links';
$GLOBALS['TL_LANG']['avisota_tracking']['recipient']['url'] = 'URL';
$GLOBALS['TL_LANG']['avisota_tracking']['recipient']['hits'] = 'Klicks';

$GLOBALS['TL_LANG']['avisota_tracking']['chart']['headline'] = '%s - %s vom %s bis zum %s';
$GLOBALS['TL_LANG']['avisota_tracking']['chart']['download'] = 'Herunterladen';
