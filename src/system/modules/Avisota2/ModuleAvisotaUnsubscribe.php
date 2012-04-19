<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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

		if (strlen($this->avisota_template_unsubscribe))
		{
			$this->strFormTemplate = $this->avisota_template_unsubscribe;
		}

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

	protected function submit(array $arrRecipient, array $arrMailingLists)
	{
		try {
			// search for the recipient
			$objRecipient = AvisotaIntegratedRecipient::byEmail($arrRecipient['email']);

			// unsubscribe from lists
			$arrUnsubscribedLists = $objRecipient->unsubscribe($arrMailingLists);

			if ($arrUnsubscribedLists === false || !count($arrUnsubscribedLists)) {
				return array('not_subscribed', $GLOBALS['TL_LANG']['avisota_unsubscribe']['notSubscribed']);
			}

			if ($this->jumpTo) {
				$objJumpTo = $this->getPageDetails($this->jumpTo);
				$this->redirect($this->generateFrontendUrl($objJumpTo->row()));
			}

			return array('unsubscribed', $GLOBALS['TL_LANG']['avisota_unsubscribe']['unsubscribed']);
		} catch (AvisotaRecipientException $e) {
			return array('not_subscribed', $GLOBALS['TL_LANG']['avisota_unsubscribe']['notSubscribed']);
		}
	}
}
