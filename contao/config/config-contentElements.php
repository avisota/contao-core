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
 * Message elements
 */
$GLOBALS['TL_MCE'] = array_merge_recursive(
	array
	(
	'texts'  => array
	(
		'headline',
		'text',
		'list',
		'table'
	),
	'links'  => array
	(
		'hyperlink'
	),
	'images' => array
	(
		'image',
		'gallery'
	),
	/*
	'includes' => array
	(
		'news',
		'events',
		'article'
	)
	*/
	),
	is_array($GLOBALS['TL_MCE']) ? $GLOBALS['TL_MCE'] : array()
);
