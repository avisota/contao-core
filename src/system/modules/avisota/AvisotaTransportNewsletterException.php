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
 * Class AvisotaTransportNewsletterException
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class AvisotaTransportNewsletterException extends Exception
{
	protected $recipient;

	protected $newsletter;

	public function __construct(
		AvisotaRecipient $recipient,
		AvisotaNewsletter $newsletter,
		$message = '',
		$code = 0,
		$previous = null
	) {
		parent::__construct($message, $code, $previous);
		$this->recipient = $recipient;
		$this->newsletter = $newsletter;
	}

	public function getRecipient()
	{
		return $this->recipient;
	}

	public function getNewsletter()
	{
		return $this->newsletter;
	}
}
