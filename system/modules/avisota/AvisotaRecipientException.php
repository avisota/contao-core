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
 * Class AvisotaRecipientException
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 */
class AvisotaRecipientException extends Exception
{
	protected $recipient;

	public function __construct(array $recipientData = null, $message = '', $code = 0, $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->recipient = $recipientData;
	}

	public function getRecipient()
	{
		return $this->recipient;
	}
}
