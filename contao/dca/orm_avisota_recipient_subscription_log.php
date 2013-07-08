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
 * Table orm_avisota_recipient_subscription_log
 * Entity Avisota\Contao:RecipientSubscriptionLog
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_subscription_log'] = array
(
	// Fields
	'fields' => array
	(
		'id'        => array
		(
			'field' => array(
				'id'   => true,
				'type' => 'integer',
			)
		),
		'recipient' => array
		(
			'field' => array(
				'type' => 'string',
			)
		),
		'list'      => array
		(
			'field' => array(
				'type'   => 'string',
			)
		),
		'datetime'  => array
		(
			'field' => array(
				'type'     => 'datetime',
				'nullable' => true,
			)
		),
	)
);
