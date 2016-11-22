<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\Event;

use Avisota\Contao\Entity\RecipientSource;
use Avisota\RecipientSource\RecipientSourceInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Create recipient source event.
 */
class CreateRecipientSourceEvent extends Event
{

    /**
     * The recipient source configuration.
     *
     * @var RecipientSource
     */
    protected $configuration;

    /**
     * The recipient source.
     *
     * @var RecipientSourceInterface
     */
    protected $recipientSource;

    /**
     * CreateRecipientSourceEvent constructor.
     *
     * @param RecipientSource          $configuration   The recipient source configuration.
     * @param RecipientSourceInterface $recipientSource The recipient source.
     */
    public function __construct(RecipientSource $configuration, RecipientSourceInterface $recipientSource)
    {
        $this->configuration   = $configuration;
        $this->recipientSource = $recipientSource;
    }

    /**
     * Return the recipient source configuration.
     *
     * @return RecipientSource
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Return the recipient source.
     *
     * @return RecipientSourceInterface
     */
    public function getRecipientSource()
    {
        return $this->recipientSource;
    }

    /**
     * Set the recipient source.
     *
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
