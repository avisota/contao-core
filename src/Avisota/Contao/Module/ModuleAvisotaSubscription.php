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


/**
 * Class ModuleAvisotaSubscription
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class ModuleAvisotaSubscription extends ModuleAvisotaRecipientForm
{
	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_subscription';

	public function __construct($module)
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
			$template           = new BackendTemplate('be_wildcard');
			$template->wildcard = '### Avisota subscription module ###';
			return $template->parse();
		}

		$this->formTemplate = $this->avisota_template_subscription;

		return parent::generate();
	}

	/**
	 * Generate the content element
	 */
	public function compile()
	{
		if ($this->Input->post('FORM_SUBMIT') == $this->formName) {
			$this->avisota_recipient_fields = array();
		}

		$this->addForm();
	}

	protected function submit(array $recipientData, array $mailingLists, FrontendTemplate $template)
	{
		if ($this->Input->post('subscribe')) {
			return $this->handleSubscribeSubmit($recipientData, $mailingLists, $template);
		}
		if ($this->Input->post('unsubscribe')) {
			return $this->handleUnsubscribeSubmit($recipientData, $mailingLists, $template);
		}
		return null;
	}
}
