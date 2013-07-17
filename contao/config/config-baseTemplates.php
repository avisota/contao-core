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

$GLOBALS['AVISOTA_MESSAGE_BASE_TEMPLATE']['templates']['3col-basic3column'] = array(
	'mode'      => 'html',
	'template'  => 'system/modules/avisota/blueprints/templates/3col-basic3column.html',
	'cells'     => array(
		'title'      => array(
			'xpath'   => '/html/head/meta[@property="og:title"]/@content|/html/head/title',
			'content' => '##message.subject##',
		),
		'teaser'     => array(
			'xpath'         => '//div[@mc:edit="std_preheader_content"]',
			'content'       => '##message.description##',
			'ifEmptyRemove' => '//div[@mc:edit="std_preheader_content"]/..',
		),
		'viewonline' => array(
			'xpath'   => '//div[@mc:edit="std_preheader_links"]',
			'content' => '##view_online_link##',
		),
		'header'     => array(
			'xpath'   => '//table[@id="templateHeader"]//img[@mc:edit="header_image"]/..',
			'content' => '{{image::{{token::theme.headerImage}}}}',
		),
		'col1'       => array(
			'xpath'       => '//table[@id="templateBody"]//td[@class="leftColumnContent"]',
			'wrapContent' => '<table border="0" cellpadding="20" cellspacing="0" width="100%"></table>',
			'wrapRow'     => '<tr><td valign="top"></td></tr>',
		),
		'col2'       => array(
			'xpath'       => '//table[@id="templateBody"]//td[@class="centerColumnContent"]',
			'wrapContent' => '<table border="0" cellpadding="20" cellspacing="0" width="100%"></table>',
			'wrapRow'     => '<tr><td valign="top"></td></tr>',
		),
		'col3'       => array(
			'xpath'       => '//table[@id="templateBody"]//td[@class="rightColumnContent"]',
			'wrapContent' => '<table border="0" cellpadding="20" cellspacing="0" width="100%"></table>',
			'wrapRow'     => '<tr><td valign="top"></td></tr>',
		),
		'footer'     => array(
			'xpath'   => '//table[@id="templateFooter"]//td[@class="footerContent"]',
			'content' => '##message.footer##'
		),
	),
	'formation' => array(
		array(
			'cells' => array(
				'teaser' => array(),
			),
		),
		array(
			'cells' => array(
				'col1' => array(),
				'col2' => array(),
				'col3' => array(),
			),
		),
		array(
			'cells' => array(
				'footer' => array(),
			),
		),
	)
);

$GLOBALS['AVISOTA_MESSAGE_BASE_TEMPLATE']['templates']['transactional-basic'] = array(
	'mode'      => 'html',
	'template'  => 'system/modules/avisota/blueprints/templates/transactional_basic.html',
	'cells'     => array(
		'title'    => array(
			'xpath'   => '/html/head/meta[@property="og:title"]/@content|/html/head/title',
			'content' => '##message.subject##',
		),
		'header'   => array(
			'xpath'   => '//table[@id="templateHeader"]//img[@mc:edit="header_image"]/..',
			'content' => '{{image::{{token::theme.headerImage}}}}',
		),
		'col1'     => array(
			'xpath' => '//table[@id="templateBody"]//td[@class="bodyContent"]',
		),
		'linkUrl'  => array(
			'xpath'   => '//table[@id="templateBody"]//td[@class="templateButtonContent"]//a/@href',
			'content' => '##link.url##',
		),
		'linkText' => array(
			'xpath'   => '//table[@id="templateBody"]//td[@class="templateButtonContent"]//a',
			'content' => '##link.text##',
		),
		'footer'   => array(
			'xpath'   => '//table[@id="templateFooter"]//td[@class="footerContent"]',
			'content' => '##message.footer##'
		),
	),
	'formation' => array(
		array(
			'cells' => array(
				'teaser' => array(),
			),
		),
		array(
			'cells' => array(
				'col1' => array(),
				'col2' => array(),
				'col3' => array(),
			),
		),
		array(
			'cells' => array(
				'footer' => array(),
			),
		),
	)
);
