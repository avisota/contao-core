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

namespace Avisota\Transport;

/**
 * Class AvisotaTransportModule
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
interface TransportInterface
{
	/**
	 * Initialise transport.
	 *
	 * @return void
	 * @throws AvisotaTransportInitialisationException
	 */
	public function initialise();

	/**
	 * Transport an email.
	 *
	 * @param string $recipientEmail
	 * @param Mail   $email
	 *
	 * @return void
	 * @throws AvisotaTransportException
	 */
	public function transport(\Swift_Message $email);

	/**
	 * Flush transport.
	 *
	 * @return void
	 * @throws AvisotaTransportFinalisationException
	 */
	public function flush();
}
