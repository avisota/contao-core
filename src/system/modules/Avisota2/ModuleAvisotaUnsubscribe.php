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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleAvisotaUnsubscribe
 *
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class ModuleAvisotaUnsubscribe extends ModuleAvisotaRecipientForm
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_unsubscribe';

	public function __construct(Database_Result $objModule)
	{
		parent::__construct($objModule);

		$this->loadLanguageFile('avisota_unsubscribe');
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### Avisota unsubscribe module ###';
			return $objTemplate->parse();
		}

		$this->strFormTemplate = $this->avisota_template_unsubscribe;

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

	protected function submit(array $arrRecipient, array $arrMailingLists, FrontendTemplate $objTemplate)
	{
		return $this->handleUnsubscribeSubmit($arrRecipient, $arrMailingLists, $objTemplate);
	}
}
