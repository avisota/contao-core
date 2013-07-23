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
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Message;

use Avisota\Contao\Entity\Message;
use Avisota\Contao\Entity\MessageContent;
use Avisota\Message\NativeMessage;
use Avisota\Recipient\RecipientInterface;

class MutablePreRenderedMessageTemplate extends AbstractPostRenderingMessageTemplate
{
	/**
	 * @var string
	 */
	protected $contentType;

	/**
	 * @var string
	 */
	protected $contentEncoding;

	/**
	 * @var string
	 */
	protected $contentName;

	/**
	 * @var string
	 */
	protected $content;

	function __construct(Message $message, $content = '', $contentName = 'message.html', $contentType = 'text/html', $contentEncoding = 'utf-8')
	{
		parent::__construct($message);
		$this->content         = (string) $content;
		$this->contentName     = (string) $contentName;
		$this->contentType     = (string) $contentType;
		$this->contentEncoding = (string) $contentEncoding;
	}

	/**
	 * @param string $contentType
	 */
	public function setContentType($contentType)
	{
		$this->contentType = $contentType;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * @param string $contentEncoding
	 */
	public function setContentEncoding($contentEncoding)
	{
		$this->contentEncoding = $contentEncoding;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContentEncoding()
	{
		return $this->contentEncoding;
	}

	/**
	 * @param string $contentName
	 */
	public function setContentName($contentName)
	{
		$this->contentName = $contentName;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContentName()
	{
		return $this->contentName;
	}

	/**
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContent()
	{
		return $this->content;
	}
}
