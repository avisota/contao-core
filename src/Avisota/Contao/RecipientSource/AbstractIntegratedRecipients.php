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

namespace Avisota\Contao\RecipientSource;

use Avisota\Contao\Entity\Recipient;
use Avisota\Recipient\MutableRecipient;

/**
 * Class AvisotaRecipientSourceIntegratedRecipients
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
abstract class AbstractIntegratedRecipients implements RecipientSourceInterface
{
	/**
	 * @var array
	 */
	protected $config;

	public function __construct($configData)
	{
		$this->config = $configData;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecipients()
	{
		$recipients = array();
		$integratedRecipients = $this->loadRecipients();

		foreach ($integratedRecipients as $integratedRecipient) {
			$recipients[] = new MutableRecipient(
				$integratedRecipient->getEmail(),
				$integratedRecipient->jsonSerialize()
			);
		}

		return $recipients;
	}

	/**
	 * @return Recipient[]
	 */
	abstract protected function loadRecipients();
}
