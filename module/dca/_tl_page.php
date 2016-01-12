<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Table tl_page
 */
$GLOBALS['TL_DCA']['tl_page']['metapalettes']['avisota'] = array
(
    'title'     => array('title', 'alias', 'type'),
    'redirect'  => array('jumpBack'),
    'protected' => array(':hide', 'protected'),
    'cache'     => array(':hide', 'includeCache'),
    'chmod'     => array(':hide', 'includeChmod'),
    'expert'    => array(':hide', 'guests'),
    'publish'   => array('published', 'start', 'stop')
);

$GLOBALS['TL_DCA']['tl_page']['fields']['jumpBack'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['jumpBack'],
    'exclude'   => true,
    'inputType' => 'pageTree',
    'eval'      => array('fieldType' => 'radio')
);
