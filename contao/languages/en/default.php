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
 * @license    LGPL
 * @filesource
 */


/**
 * Newsletter content elements
 */
$GLOBALS['TL_LANG']['NLE']['texts']     = 'Text elements';
$GLOBALS['TL_LANG']['NLE']['headline']  = array('Headline', 'Generates a headline (h1 - h6).');
$GLOBALS['TL_LANG']['NLE']['text']      = array('Text', 'Generates a rich text element.');
$GLOBALS['TL_LANG']['NLE']['list']      = array('List', 'Generates an ordered or unordered list.');
$GLOBALS['TL_LANG']['NLE']['table']     = array('Table', 'Generates an optionally sortable table.');
$GLOBALS['TL_LANG']['NLE']['links']     = 'Link elements';
$GLOBALS['TL_LANG']['NLE']['hyperlink'] = array('Hyperlink', 'Generates a link to another website.');
$GLOBALS['TL_LANG']['NLE']['images']    = 'Image elements';
$GLOBALS['TL_LANG']['NLE']['image']     = array('Image', 'Generates a stand-alone image.');
$GLOBALS['TL_LANG']['NLE']['gallery']   = array('Gallery', 'Generates a lightbox image gallery.');
$GLOBALS['TL_LANG']['NLE']['files']     = 'File elements';
$GLOBALS['TL_LANG']['NLE']['download']  = array('Download', 'Generates a link to download a file.');
$GLOBALS['TL_LANG']['NLE']['downloads'] = array('Downloads', 'Generates multiple links to download files.');
$GLOBALS['TL_LANG']['NLE']['includes']  = 'Include elements';
$GLOBALS['TL_LANG']['NLE']['article']   = array('Article', 'Includes another article.');
$GLOBALS['TL_LANG']['NLE']['alias']     = array('Content element', 'Includes another content element.');
$GLOBALS['TL_LANG']['NLE']['module']    = array('Module', 'Includes a front end module.');
$GLOBALS['TL_LANG']['NLE']['teaser']    = array('Article teaser', 'Displays the teaser text of an article.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['delete_no_blacklist'] = 'Löschen ohne Blacklist-Eintrag';
$GLOBALS['TL_LANG']['MSC']['schedule']            = 'Versand planen';
$GLOBALS['TL_LANG']['MSC']['send']                = 'Versenden';
