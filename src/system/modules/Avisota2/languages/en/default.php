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
 * @copyright  InfinitySoft 2010,2011,2012
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
$GLOBALS['TL_LANG']['NLE']['texts']     = 'Text-Element';
$GLOBALS['TL_LANG']['NLE']['headline']  = array('Headline', 'Creates a Heading (h1 - h6).');
$GLOBALS['TL_LANG']['NLE']['text']      = array('Text', 'Creates a Rich-Text-Element.');
$GLOBALS['TL_LANG']['NLE']['list']      = array('List', 'Creates an ordered or unordered list.');
$GLOBALS['TL_LANG']['NLE']['table']     = array('Table', 'Creates an optional sortable table.');
$GLOBALS['TL_LANG']['NLE']['links']     = 'Link-Element';
$GLOBALS['TL_LANG']['NLE']['hyperlink'] = array('Hyperlink', 'Creates a link to another web page.');
$GLOBALS['TL_LANG']['NLE']['images']    = 'Image Elements';
$GLOBALS['TL_LANG']['NLE']['image']     = array('Image', 'Creates a single image.');
$GLOBALS['TL_LANG']['NLE']['gallery']   = array('Gallery', 'Creates a gallery of images.');
$GLOBALS['TL_LANG']['NLE']['files']     = 'Attached files';
$GLOBALS['TL_LANG']['NLE']['download']  = array('Download', 'Creates a link to a download file.');
$GLOBALS['TL_LANG']['NLE']['downloads'] = array('Downloads', 'Creates links for multiple download files.');
$GLOBALS['TL_LANG']['NLE']['includes']  = 'Include-Elemente';
$GLOBALS['TL_LANG']['NLE']['news']      = array('News', 'Inserts a news teaser.');
$GLOBALS['TL_LANG']['NLE']['events']     = array('Events', 'Inserts an event teaser.');
$GLOBALS['TL_LANG']['NLE']['article']   = array('Article', 'Inserts an article teaser.');
