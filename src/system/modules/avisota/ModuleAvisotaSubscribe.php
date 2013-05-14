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


/**
 * Class ModuleAvisotaSubscribe
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class ModuleAvisotaSubscribe extends ModuleAvisotaRecipientForm
{
	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_subscribe';

	public function __construct(Database_Result $module)
	{
		parent::__construct($module);

		$this->loadLanguageFile('avisota_subscribe');
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$template           = new BackendTemplate('be_wildcard');
			$template->wildcard = '### Avisota subscribe module ###';
			return $template->parse();
		}

		$this->formTemplate = $this->avisota_template_subscribe;

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	public function compile()
	{
		$this->addForm();
	}

	protected function submit(array $recipientData, array $mailingLists, FrontendTemplate $template)
	{
		return $this->handleSubscribeSubmit($recipientData, $mailingLists, $template);
	}
}
