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
 * Class AvisotaSubscriptionException
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 */
class AvisotaSubscriptionException extends Exception
{
	protected $recipient;

	public function __construct(AvisotaRecipient $recipient, $message = '', $code = 0, $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->recipient = $recipient;
	}

	public function getRecipient()
	{
		return $this->recipient;
	}
}
