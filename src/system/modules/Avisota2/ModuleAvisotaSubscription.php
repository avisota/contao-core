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
 * Class ModuleAvisotaSubscription
 *
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
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

	public function __construct(Database_Result $objModule)
	{
		parent::__construct($objModule);

		$this->loadLanguageFile('avisota_subscribe');
		$this->loadLanguageFile('avisota_unsubscribe');
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate           = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### Avisota subscription module ###';
			return $objTemplate->parse();
		}

		$this->strFormTemplate = $this->avisota_template_subscription;

		return parent::generate();
	}

	/**
	 * Generate the content element
	 */
	public function compile()
	{
		if ($this->Input->post('FORM_SUBMIT') == $this->strFormName) {
			$this->avisota_recipient_fields = array();
		}

		$this->addForm();
	}

	protected function submit(array $arrRecipient, array $arrMailingLists, FrontendTemplate $objTemplate)
	{
		if ($this->Input->post('subscribe')) {
			return $this->handleSubscribeSubmit($arrRecipient, $arrMailingLists, $objTemplate);
		}
		if ($this->Input->post('unsubscribe')) {
			return $this->handleUnsubscribeSubmit($arrRecipient, $arrMailingLists, $objTemplate);
		}
		return null;
	}
}
