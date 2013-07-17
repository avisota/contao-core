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
$GLOBALS['TL_NLE'] = array_merge_recursive(
	array
	(
	'texts'  => array
	(
		'headline' => 'Avisota\Contao\Message\Element\Headline',
		'text'     => 'Avisota\Contao\Message\Element\Text',
		'list'     => 'Avisota\Contao\Message\Element\List',
		'table'    => 'Avisota\Contao\Message\Element\Table'
	),
	'links'  => array
	(
		'hyperlink' => 'Avisota\Contao\Message\Element\Hyperlink'
	),
	'images' => array
	(
		'image'   => 'Avisota\Contao\Message\Element\Image',
		'gallery' => 'Avisota\Contao\Message\Element\Gallery'
	),
	/*
	'includes' => array
	(
		'news'    => 'Avisota\Contao\Message\Element\News',
		'events'  => 'Avisota\Contao\Message\Element\Event',
		'article' => 'Avisota\Contao\Message\Element\ArticleTeaser'
	)
	*/
	),
	is_array($GLOBALS['TL_NLE']) ? $GLOBALS['TL_NLE'] : array()
);
