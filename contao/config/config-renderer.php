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
 * Message renderer
 */
$GLOBALS['AVISOTA_MESSAGE_RENDERER']['backend']   = array('Avisota\Contao\Message\Renderer\Backend\MessageRenderer', 100);
$GLOBALS['AVISOTA_MESSAGE_RENDERER']['mailChimp'] = 'Avisota\Contao\Message\Renderer\MailChimp\MessageRenderer';


/**
 * Backend content renderer
 */
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\HeadlineElementRenderer';
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\TextElementRenderer';
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\ListElementRenderer';
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\TableElementRenderer';
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\HyperlinkElementRenderer';
$GLOBALS['AVISOTA_CONTENT_RENDERER']['backend'][] = 'Avisota\Contao\Message\Renderer\Backend\ImageElementRenderer';


/**
 * MailChimp content renderer
 */
$GLOBALS['AVISOTA_CONTENT_RENDERER']['mailChimp'][] = 'Avisota\Contao\Message\Renderer\MailChimp\Content\TextRenderer';
