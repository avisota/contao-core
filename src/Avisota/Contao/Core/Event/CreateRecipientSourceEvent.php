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

namespace Avisota\Contao\Core\Event;

use Avisota\Contao\Entity\RecipientSource;
use Avisota\RecipientSource\RecipientSourceInterface;
use Symfony\Component\EventDispatcher\Event;

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
     */
    public function setRecipientSource(RecipientSourceInterface $recipientSource)
    {
        $this->recipientSource = $recipientSource;
        return $this;
    }
}
