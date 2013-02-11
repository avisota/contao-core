<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_avisota_newsletter_create_from_draft
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter_create_from_draft'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onload_callback'   => array
		(
			array('tl_avisota_newsletter_create_from_draft', 'onload_callback'),
		),
		'onsubmit_callback' => array
		(
			array('tl_avisota_newsletter_create_from_draft', 'onsubmit_callback'),
		)
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array(
			'create' => array('category', 'subject', 'draft')
		)
	),
	// Fields
	'fields'       => array
	(
		'category' => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_create_from_draft']['category'],
			'inputType'  => 'select',
			'foreignKey' => 'tl_avisota_newsletter_category.title',
			'eval'       => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
			)
		),
		'subject'  => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_create_from_draft']['subject'],
			'inputType' => 'text',
			'eval'      => array(
				'mandatory' => true,
				'tl_class'  => 'w50'
			)
		),
		'draft'    => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_create_from_draft']['draft'],
			'inputType'  => 'radio',
			'foreignKey' => 'tl_avisota_newsletter_draft.title',
			'eval'       => array(
				'mandatory' => true,
				'tl_class'  => 'clr'
			)
		)
	)
);

class tl_avisota_newsletter_create_from_draft extends Backend
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
			->prepare("SELECT * FROM tl_avisota_newsletter_draft WHERE id=?")
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
				"INSERT INTO tl_avisota_newsletter (" . implode(",", array_keys($newsletterDraftData)) . ") VALUES ($value)"
			)
				->execute($newsletterDraftData);
			$newsletterId         = $newsletter->insertId;

			$content = $this->Database
				->prepare("SELECT * FROM tl_avisota_newsletter_draft_content WHERE pid=?")
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
					"INSERT INTO tl_avisota_newsletter_content (" . implode(
						",",
						array_keys($newsletterDraftData)
					) . ") VALUES ($value)"
				)
					->execute($newsletterDraftData);
			}

			$_SESSION['TL_INFO'][] = $GLOBALS['TL_LANG']['tl_avisota_newsletter_create_from_draft']['created'];
			$this->redirect('contao/main.php?do=avisota_newsletter&table=tl_avisota_newsletter_content&id=' . $newsletterId);
		}
	}
}
