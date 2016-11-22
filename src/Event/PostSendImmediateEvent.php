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

use Avisota\Contao\Entity\Message;

/**
 * The post defined immediate event.
 */
class PostSendImmediateEvent extends BaseSendImmediateEvent
{
    /**
     * The count.
     *
     * @var string
     */
    protected $count;

    /**
     * PostSendImmediateEvent constructor.
     *
     * @param Message $count   The count.
     * @param Message $message The message.
     * @param string  $turn    The turn step.
     * @param string  $loop    The unique loop id.
     */
    public function __construct($count, Message $message, $turn, $loop)
    {
        parent::__construct($message, $turn, $loop);

        $this->count = $count;
    }

    /**
     * Return the count.
     *
     * @return string
     */
    public function getCount()
    {
        return $this->count;
    }
}
