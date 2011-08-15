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
 * Page types
 */
$GLOBALS['TL_LANG']['PTY']['avisota']   = array('Newsletter', 'Displays any newsletter from the Newsletter System.');


/**
 * Newsletter content elements
 */
$GLOBALS['TL_LANG']['NLE']['texts']     = 'Tekst element';
$GLOBALS['TL_LANG']['NLE']['headline']  = array('Overskrift', 'Opret overskrifter (h1 - h6).');
$GLOBALS['TL_LANG']['NLE']['text']      = array('Tekst', 'Opret et Rich-Text-Element.');
$GLOBALS['TL_LANG']['NLE']['list']      = array('Liste', 'Opret en ordnet eller uordnet liste.');
$GLOBALS['TL_LANG']['NLE']['table']     = array('Tabel', 'Opret en valgfri sorterbar tabel.');
$GLOBALS['TL_LANG']['NLE']['links']     = 'Link element';
$GLOBALS['TL_LANG']['NLE']['hyperlink'] = array('Hyperlink', 'Opret et link til en anden hjemmeside.');
$GLOBALS['TL_LANG']['NLE']['images']    = 'Billede elementer';
$GLOBALS['TL_LANG']['NLE']['image']     = array('Billede', 'Opret et enkelt billed.');
$GLOBALS['TL_LANG']['NLE']['gallery']   = array('Galleri', 'Opret et galleri.');
$GLOBALS['TL_LANG']['NLE']['files']     = 'Vedhæftet filer';
$GLOBALS['TL_LANG']['NLE']['download']  = array('Download', 'Opret et link til download af fil.');
$GLOBALS['TL_LANG']['NLE']['downloads'] = array('Downloads', 'Opret links for flere filer der kan downloades.');
$GLOBALS['TL_LANG']['NLE']['includes']  = 'Inkluder element';
$GLOBALS['TL_LANG']['NLE']['news']      = array('Nyheder', 'Indsæt en nyhedsteaer.');
$GLOBALS['TL_LANG']['NLE']['events']     = array('Events', 'Indsæt en nyhedsteaer.');
$GLOBALS['TL_LANG']['NLE']['article']   = array('Artiklen', 'Indsæt en teaser til artiklen.');

?>