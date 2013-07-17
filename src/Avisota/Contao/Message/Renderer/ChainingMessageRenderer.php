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

namespace Avisota\Contao\Message\Renderer;

use Avisota\Contao\Entity\Message;
use Avisota\Recipient\RecipientInterface;

class ChainingMessageRenderer implements ChainableMessageRendererInterface
{
    protected $chain = array();

    public function addRenderer(ChainableMessageRendererInterface $renderer, $priority = 0)
    {
        $this->removeRenderer($renderer);

        $hash = spl_object_hash($renderer);

        if (!isset($this->chain[$priority])) {
            $this->chain[$priority] = array($hash => $renderer);
        }
        else {
            $this->chain[$priority][$hash] = $renderer;
        }
        krsort($this->chain);
    }

    public function removeRenderer(ChainableMessageRendererInterface $renderer)
    {
        $hash = spl_object_hash($renderer);
        foreach ($this->chain as &$list) {
            unset($list[$hash]);
        }
    }

    public function render(Message $message, RecipientInterface $recipient = null)
    {
        foreach ($this->chain as $list) {
            foreach ($list as $renderer) {
                if ($renderer->canRender($message, $recipient)) {
                    return $renderer->render($message, $recipient);
                }
            }
        }

        throw new \RuntimeException('Could not render message ' . $message->getId());
    }

    public function canRender(Message $message, RecipientInterface $recipient = null)
    {
        foreach ($this->chain as $list) {
            foreach ($list as $renderer) {
                if ($renderer->canRender($message, $recipient)) {
                    return true;
                }
            }
        }
        return false;
    }
}
