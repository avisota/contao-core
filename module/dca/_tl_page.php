<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
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
