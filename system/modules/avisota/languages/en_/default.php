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
 * Page types
 */
$GLOBALS['TL_LANG']['PTY']['avisota'] = array('Newsletter', 'Displays any newsletter from the Newsletter System.');


/**
 * Newsletter content elements
 */
$GLOBALS['TL_LANG']['MCE']['texts']     = 'Text-Element';
$GLOBALS['TL_LANG']['MCE']['headline']  = array('Headline', 'Creates a Heading (h1 - h6).');
$GLOBALS['TL_LANG']['MCE']['text']      = array('Text', 'Creates a Rich-Text-Element.');
$GLOBALS['TL_LANG']['MCE']['list']      = array('List', 'Creates an ordered or unordered list.');
$GLOBALS['TL_LANG']['MCE']['table']     = array('Table', 'Creates an optional sortable table.');
$GLOBALS['TL_LANG']['MCE']['links']     = 'Link-Element';
$GLOBALS['TL_LANG']['MCE']['hyperlink'] = array('Hyperlink', 'Creates a link to another web page.');
$GLOBALS['TL_LANG']['MCE']['images']    = 'Image Elements';
$GLOBALS['TL_LANG']['MCE']['image']     = array('Image', 'Creates a single image.');
$GLOBALS['TL_LANG']['MCE']['gallery']   = array('Gallery', 'Creates a gallery of images.');
$GLOBALS['TL_LANG']['MCE']['files']     = 'Attached files';
$GLOBALS['TL_LANG']['MCE']['download']  = array('Download', 'Creates a link to a download file.');
$GLOBALS['TL_LANG']['MCE']['downloads'] = array('Downloads', 'Creates links for multiple download files.');
$GLOBALS['TL_LANG']['MCE']['includes']  = 'Include-Elemente';
$GLOBALS['TL_LANG']['MCE']['news']      = array('News', 'Inserts a news teaser.');
$GLOBALS['TL_LANG']['MCE']['events']    = array('Events', 'Inserts an event teaser.');
$GLOBALS['TL_LANG']['MCE']['article']   = array('Article', 'Inserts an article teaser.');
