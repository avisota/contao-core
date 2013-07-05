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

class orm_avisota_mailing_create_from_draft extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 *
	 *
	 * @param DataContainer $dc
	 */
	public function onload_callback(DataContainer $dc)
	{
		$dc->setData('category', $this->Input->get('id'));
	}


	/**
	 *
	 *
	 * @param DataContainer $dc
	 */
	public function onsubmit_callback(DataContainer $dc)
	{
		$categoryId = $dc->getData('category');
		$subject  = $dc->getData('subject');
		$draftId    = $dc->getData('draft');

		$newsletterDraft = $this->Database
			->prepare("SELECT * FROM orm_avisota_mailing_draft WHERE id=?")
			->execute($draftId);
		if ($newsletterDraft->next()) {
			$newsletterDraftData = $newsletterDraft->row();
			// remove unwanted fields
			unset($newsletterDraftData['id'], $newsletterDraftData['tstamp'], $newsletterDraftData['title'], $newsletterDraftData['description'], $newsletterDraftData['alias']);
			// set pid
			$newsletterDraftData['pid'] = $categoryId;
			// set subject
			$newsletterDraftData['subject'] = $subject;

			// call hook
			// TODO AvisotaHelper::callHook('prepareNewsletterCreateFromDraft', array(&$arrRow));

			$value = '';
			for ($i = 0; $i < count($newsletterDraftData); $i++) {
				if ($i > 0) {
					$value .= ',';
				}
				$value .= '?';
			}

			$newsletter = $this->Database
				->prepare(
				"INSERT INTO orm_avisota_mailing (" . implode(",", array_keys($newsletterDraftData)) . ") VALUES ($value)"
			)
				->execute($newsletterDraftData);
			$newsletterId         = $newsletter->insertId;

			$content = $this->Database
				->prepare("SELECT * FROM orm_avisota_mailing_draft_content WHERE pid=?")
				->execute($draftId);

			while ($content->next()) {
				$newsletterDraftData = $content->row();
				// remove unwanted fields
				unset($newsletterDraftData['id'], $newsletterDraftData['tstamp']);
				// set pid
				$newsletterDraftData['pid'] = $newsletterId;

				// call hook
				// TODO AvisotaHelper::callHook('prepareNewsletterContentCreateFromDraft', array(&$arrRow));

				// prevent pid changing
				$newsletterDraftData['pid'] = $newsletterId;

				$value = '';
				for ($i = 0; $i < count($newsletterDraftData); $i++) {
					if ($i > 0) {
						$value .= ',';
					}
					$value .= '?';
				}

				$newsletter = $this->Database
					->prepare(
					"INSERT INTO orm_avisota_mailing_content (" . implode(
						",",
						array_keys($newsletterDraftData)
					) . ") VALUES ($value)"
				)
					->execute($newsletterDraftData);
			}

			$_SESSION['TL_INFO'][] = $GLOBALS['TL_LANG']['orm_avisota_mailing_create_from_draft']['created'];
			$this->redirect('contao/main.php?do=avisota_newsletter&table=orm_avisota_mailing_content&id=' . $newsletterId);
		}
	}
}
