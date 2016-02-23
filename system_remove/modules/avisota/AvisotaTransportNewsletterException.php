<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Class AvisotaTransportNewsletterException
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
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
