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
 * Class ModuleAvisotaSubscribe
 *
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class ModuleAvisotaSubscribe extends ModuleAvisotaRecipientForm
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_subscribe';

	public function __construct(Database_Result $objModule)
	{
		parent::__construct($objModule);

		$this->loadLanguageFile('avisota_subscribe');
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate           = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### Avisota subscribe module ###';
			return $objTemplate->parse();
		}

		$this->strFormTemplate = $this->avisota_template_subscribe;

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
		try {
			// load existing recipient
			$objRecipeint = AvisotaIntegratedRecipient::byEmail($arrRecipient['email']);
		} catch (AvisotaRecipientException $e) {
			// create a new recipient
			$objRecipeint = new AvisotaIntegratedRecipient($arrRecipient);
			$objRecipeint->store();
		}

		// subscribe to mailing lists
		$arrSubscribedMailingLists = $objRecipeint->subscribe($arrMailingLists, true);

		// if subscription success...
		if (is_array($arrSubscribedMailingLists) && count($arrSubscribedMailingLists)) {
			// ...send confirmation mail...
			$objRecipeint->sendSubscriptionConfirmation($arrSubscribedMailingLists);

			// ...and redirect if jump to page is configured
			if ($this->jumpTo) {
				$objJumpTo = $this->getPageDetails($this->jumpTo);
				$this->redirect($this->generateFrontendUrl($objJumpTo->row()));
			}

			return array('subscribed', $GLOBALS['TL_LANG']['avisota_subscribe']['subscribed'], true);
		}

		// ...or try to send reminder...
		if ($GLOBALS['TL_CONFIG']['avisota_send_notification']) {
			// resend subscriptions
			$arrConfirmationSend = $objRecipeint->sendSubscriptionConfirmation($arrMailingLists, true);
			$arrReminderSend     = array();
		}
		else {
			// first send subscriptions if not allready done
			$arrConfirmationSend = $objRecipeint->sendSubscriptionConfirmation($arrMailingLists);
			// now send reminders
			$arrReminderSend = $objRecipeint->sendRemind(array_diff($arrMailingLists, $arrConfirmationSend), true);
		}

		if (count($arrConfirmationSend) || count($arrReminderSend)) {
			// ...and redirect if jump to page is configured
			if ($this->jumpTo) {
				$objJumpTo = $this->getPageDetails($this->jumpTo);
				$this->redirect($this->generateFrontendUrl($objJumpTo->row()));
			}

			return array('reminder_sent', $GLOBALS['TL_LANG']['avisota_subscribe']['subscribed'], true);
		}

		// ...otherwise recipient allready subscribed
		return array('allready_subscribed', $GLOBALS['TL_LANG']['avisota_subscribe']['allreadySubscribed'], false);
	}

	protected function prepareForm(FrontendTemplate $objTemplate)
	{
		$arrLists = $this->handleSubscribeTokens();

		if ($arrLists && count($arrLists)) {
			$objTemplate->messageClass = 'confirm_subscription';
			$objTemplate->message      = $GLOBALS['TL_LANG']['avisota_subscribe']['confirmSubscription'];
			$objTemplate->hideForm     = true;
		}
	}
}
