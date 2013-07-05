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

namespace Avisota\Contao\Module;

/**
 * Class ModuleAvisotaUnsubscribe
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class Unsubscribe extends AbstractRecipientForm
{
	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_unsubscribe';

	public function __construct(\Database_Result $module)
	{
		parent::__construct($module);

		$this->loadLanguageFile('avisota_subscription');
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$template           = new \BackendTemplate('be_wildcard');
			$template->wildcard = '### Avisota unsubscribe module ###';
			return $template->parse();
		}

		$this->formTemplate = $this->avisota_template_unsubscribe;

		$this->avisota_recipient_fields = '';

		return parent::generate();
	}

	/**
	 * Generate the content element
	 */
	public function compile()
	{
		$this->addForm();
	}

	protected function submit(array $recipientData, array $mailingLists, \TwigFrontendTemplate $template)
	{
		return $this->handleUnsubscribeSubmit($recipientData, $mailingLists, $template);
	}
}
