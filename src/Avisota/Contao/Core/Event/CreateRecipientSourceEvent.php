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

namespace Avisota\Contao\Core\Event;

use Avisota\Contao\Entity\RecipientSource;
use Avisota\RecipientSource\RecipientSourceInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event is the base class for classes containing event data.
 *
 * This class contains no event data. It is used by events that do not pass
 * state information to an event handler when an event is raised.
 *
 * You can call the method stopPropagation() to abort the execution of
 * further listeners in your event listener.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CreateRecipientSourceEvent extends Event
{

	/**
	 * @var RecipientSource
	 */
	protected $configuration;

	/**
	 * @var RecipientSourceInterface
	 */
	protected $recipientSource;

	/**
	 * CreateRecipientSourceEvent constructor.
	 *
	 * @param RecipientSource          $configuration
	 * @param RecipientSourceInterface $recipientSource
     */
    function __construct(RecipientSource $configuration, RecipientSourceInterface $recipientSource)
	{
		$this->configuration   = $configuration;
		$this->recipientSource = $recipientSource;
	}

	/**
	 * @return RecipientSource
	 */
	public function getConfiguration()
	{
		return $this->configuration;
	}

	/**
	 * @return RecipientSourceInterface
	 */
	public function getRecipientSource()
	{
		return $this->recipientSource;
	}

	/**
	 * @param RecipientSourceInterface $recipientSource
	 *
	 * @return $this
	 */
	public function setRecipientSource(RecipientSourceInterface $recipientSource)
	{
		$this->recipientSource = $recipientSource;
		return $this;
	}
}
