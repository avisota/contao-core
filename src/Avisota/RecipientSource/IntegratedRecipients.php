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

namespace Avisota\RecipientSource;

/**
 * Class AvisotaRecipientSourceIntegratedRecipients
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class IntegratedRecipients implements RecipientSourceInterface
{
	/**
	 * @var array
	 */
	private $config;

	public function __construct($configData)
	{
		$this->config = $configData;
	}

	/**
	 * Get all selectable recipient options for this source.
	 * Every option can be an individuell ID.
	 *
	 * @return array
	 */
	public function getRecipientOptions()
	{
		$database = \Database::getInstance();

		switch ($this->config['integratedBy']) {
			case 'integratedByMailingLists':
				$ids = array_filter(array_map('intval', deserialize($this->config['integratedMailingLists'], true)));
				if (!count($ids)) {
					$ids[] = 0;
				}
				// fetch all selected mailing lists
				$mailingList = $database
					->execute(
					'SELECT * FROM tl_avisota_mailing_list
							   WHERE id IN (' . implode(',', $ids) . ')
							   ORDER BY title'
				);
			// continue with same logic as for all mailing lists
			case 'integratedByAllMailingLists':
				// fetch mailing lists, if there are no result
				if (!isset($mailingList)) {
					$mailingList = $database
						->execute('SELECT * FROM tl_avisota_mailing_list ORDER BY title');
				}

				$options = array();
				while ($mailingList->next()) {
					$options[$mailingList->id] = $mailingList->title;
				}

				// if single selection is allowed, build an option for every mailing list
				if ($this->config['integratedAllowSingleListSelection']) {
					return $options;
				}

				// build a wildcard option for non-single select
				else if (count($options)) {
					return array(
						'*' => implode(', ', $mailingList->fetchEach('title'))
					);
				}
				return array();

			case 'integratedByRecipients':
				$ids = array_filter(array_map('intval', deserialize($this->config['integratedMailingLists'], true)));
				if (!count($ids)) {
					$ids[] = 0;
				}
				$recipient = $database
					->prepare(
					'SELECT r.*, (SELECT t2.confirmed FROM tl_avisota_recipient_to_mailing_list t2 WHERE t2.recipient=r.id AND confirmed=? LIMIT 1) as confirmed
							   FROM tl_avisota_recipient r
							   INNER JOIN tl_avisota_recipient_to_mailing_list t ON t.recipient=r.id
							   WHERE t.list IN (' . implode(',', $ids) . ')
							   ORDER BY email'
				)
					->execute(1);
			case 'integratedByAllRecipients':
				if (!isset($recipient)) {
					$recipient = $database
						->execute(
						"SELECT *, 1 AS confirmed
						 FROM tl_avisota_recipient
						 ORDER BY email"
					);
				}

				$options = array();
				while ($recipient->next()) {
					$name = trim($recipient->firstname . ' ' . $recipient->lastname);
					$name = $name ? sprintf('%s &lt;%s&gt;', $name, $recipient->email)
						: $recipient->email;
					if (!$recipient->confirmed) {
						$name = '<span style="color:#A6A6A6">' . $name . '</span>';
					}
					$options[$recipient->id] = $name;
				}

				if ($this->config['integratedAllowSingleSelection']) {
					return $options;
				}

				else if (count($options)) {
					return array(
						'*' => implode(', ', array_slice($options, 3)) . (count($options) > 3 ? '&hellip' : '')
					);
				}
				return array();

			default:
				$GLOBALS['container']['avisota.logger']->error(
					'The integrated recipient source ID ' . $this->config['id'] . ' is not fully configured!'
				);
				return array();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecipients($options)
	{
		throw new \Exception('Not implemented yet');
		switch ($this->config['integratedBy']) {
			case 'integratedByMailingLists':

				break;
			case 'integratedByAllMailingLists':

				break;
			case 'integratedByAllRecipients':
				break;
			default:
				$GLOBALS['container']['avisota.logger']->error(
					'The recipient source ID ' . $this->config['id'] . ' is not fully configured!'
				);
				return array();
		}
	}
}
