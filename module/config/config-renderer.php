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
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Transport renderer
 */
$GLOBALS['AVISOTA_TRANSPORT_RENDERER']['native'] = 'Avisota\Renderer\NativeMessageRenderer';


/**
 * Backend content renderer
 */
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\HeadlineElementPreRenderer';
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\TextElementPreRenderer';
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\ListElementPreRenderer';
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\TableElementPreRenderer';
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\HyperlinkElementPreRenderer';
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\ImageElementPreRenderer';
