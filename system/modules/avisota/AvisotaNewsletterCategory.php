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
 * Class AvisotaNewsletterCategory
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 */
class AvisotaNewsletterCategory
{
	public static function load($id)
	{
		if (!$id) {
			return null;
		}

		$resultSet = Database::getInstance()
			->prepare(
			'SELECT *
					   FROM orm_avisota_message_category
					   WHERE id=? OR alias=?'
		)
			->execute($id, $id);

		if (!$resultSet->next()) {
			return null;
		}

		return new AvisotaNewsletterCategory($resultSet);
	}

	/**
	 * Title of this category.
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Meta description of this newsletter.
	 *
	 * @var array
	 */
	protected $recipients;

	/**
	 * Meta keywords of this newsletter.
	 *
	 * @var AvisotaNewsletterTheme
	 */
	protected $theme;

	/**
	 * The theme of thins newsletter.
	 *
	 * @var AvisotaTransport
	 */
	protected $transport;

	/**
	 * @var array
	 */
	protected $data;

	function __construct(Database_Result $resultSet = null)
	{
		foreach ($resultSet->row() as $name => $value) {
			$this->__set($name, $value);
		}
	}

	function __set($name, $value)
	{
		switch ($name) {
			case 'title':
				$this->setTitle($value);
				break;

			case 'recipients':
				$this->setRecipients(
					is_array($value)
						? $value
						: deserialize($value, true)
				);
				break;

			case 'theme':
				$this->setTheme($value);
				break;

			case 'transport':
				$this->setTransport($value);
				break;

			default:
				$this->data[$name] = $value;
		}
	}

	function __get($name)
	{
		switch ($name) {
			case 'title':
				return $this->getTitle();

			case 'recipients':
				return $this->getRecipients();

			case 'theme':
				return $this->getTheme();

			case 'transport':
				return $this->getTransport();

			default:
				return $this->data[$name];
		}
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param array $recipients
	 */
	public function setRecipients($recipients)
	{
		$this->recipients = $recipients;
	}

	/**
	 * @return array
	 */
	public function getRecipients()
	{
		return $this->recipients;
	}

	/**
	 * @param \AvisotaNewsletterTheme $theme
	 */
	public function setTheme($theme)
	{
		$this->theme = $theme;
	}

	/**
	 * @return \AvisotaNewsletterTheme
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * @param \AvisotaTransport $transport
	 */
	public function setTransport($transport)
	{
		$this->transport = $transport;
	}

	/**
	 * @return \AvisotaTransport
	 */
	public function getTransport()
	{
		return $this->transport;
	}
}
