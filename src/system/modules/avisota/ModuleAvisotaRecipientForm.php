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
 * Class ModuleAvisotaRecipientForm
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
abstract class ModuleAvisotaRecipientForm extends Module
{
	/**
	 * @var string
	 */
	protected $formTemplate;

	/**
	 * @var string
	 */
	protected $formName;

	/**
	 * Construct the content element
	 */
	public function __construct(Database_Result $module)
	{
		parent::__construct($module);
		$this->import('DomainLink');
		$this->import('FrontendUser', 'User');
		$this->loadLanguageFile('avisota');

		$this->formName = get_class($this) . '_' . $module->id;
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');

		// Call onload_callback (e.g. to check permissions)
		if (is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['config']['onload_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['config']['onload_callback'] as $callback) {
				if (is_array($callback)) {
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1](null);
				}
			}
		}

		// Change the label of the permit personal tracing checkbox
		if (isset($GLOBALS['TL_CONFIG']['avisota_data_privacy_statement_page'])) {
			$dataPrivacyStatementPage = $this->getPageDetails(
				$GLOBALS['TL_CONFIG']['avisota_data_privacy_statement_page']
			);
		}
		else {
			$dataPrivacyStatementPage = $GLOBALS['objPage'];
		}
		$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['permitPersonalTracing']['label']    = $GLOBALS['TL_LANG']['tl_avisota_recipient']['permitPersonalTracingFE'];
		$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['permitPersonalTracing']['label'][1] = sprintf(
			$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['permitPersonalTracing']['label'][1],
			$this->generateFrontendUrl($dataPrivacyStatementPage->row())
		);

		// Deserialize module configuration
		$this->avisota_recipient_fields = deserialize($this->avisota_recipient_fields, true);
		$this->avisota_lists            = array_filter(array_map('intval', deserialize($this->avisota_lists, true)));

		return parent::generate();
	}

	protected function handleSubscribeSubmit(array $recipientData, array $mailingLists, FrontendTemplate $template)
	{
		try {
			// load existing recipient
			$recipeint = AvisotaIntegratedRecipient::byEmail($recipientData['email']);
		}
		catch (AvisotaRecipientException $e) {
			// create a new recipient
			$recipeint = new AvisotaIntegratedRecipient($recipientData);
			$recipeint->store();
		}

		// subscribe to mailing lists
		$subscribedMailingLists = $recipeint->subscribe($mailingLists, true);

		// if subscription success...
		if (is_array($subscribedMailingLists) && count($subscribedMailingLists)) {
			// ...send confirmation mail...
			$recipeint->sendSubscriptionConfirmation($subscribedMailingLists);

			// ...and redirect if jump to page is configured
			if ($this->avisota_subscribe_confirmation_page) {
				$jumpToPage = $this->getPageDetails($this->avisota_subscribe_confirmation_page);
				$this->redirect($this->generateFrontendUrl($jumpToPage->row()));
			}

			return array('subscribed', $GLOBALS['TL_LANG']['avisota_subscribe']['subscribed'], true);
		}

		// ...or try to send reminder...
		if ($GLOBALS['TL_CONFIG']['avisota_send_notification']) {
			// resend subscriptions
			$sendConfirmations = $recipeint->sendSubscriptionConfirmation($mailingLists, true);
			$sendReminders     = array();
		}
		else {
			// first send subscriptions if not allready done
			$sendConfirmations = $recipeint->sendSubscriptionConfirmation($mailingLists);
			// now send reminders
			$sendReminders = $recipeint->sendRemind(array_diff($mailingLists, $sendConfirmations), true);
		}

		if (is_array($sendConfirmations) && count($sendConfirmations) ||
			is_array($sendReminders) && count($sendReminders)
		) {
			// ...and redirect if jump to page is configured
			if ($this->avisota_subscribe_confirmation_page) {
				$jumpToPage = $this->getPageDetails($this->avisota_subscribe_confirmation_page);
				$this->redirect($this->generateFrontendUrl($jumpToPage->row()));
			}

			return array('reminder_sent', $GLOBALS['TL_LANG']['avisota_subscribe']['subscribed'], true);
		}

		// ...otherwise recipient allready subscribed
		return array('allready_subscribed', $GLOBALS['TL_LANG']['avisota_subscribe']['allreadySubscribed'], false);
	}

	protected function handleSubscribeTokens()
	{
		try {
			$token = $this->Input->get('subscribetoken');
			$this->Input->setGet('subscribetoken', '');
			if ($token) {
				$tokens = explode(',', $token);

				$recipient = AvisotaIntegratedRecipient::bySubscribeTokens($tokens);
				if ($recipient !== null) {
					return $recipient->confirmSubscription($tokens);
				}
			}
		}
		catch (AvisotaRecipientException $e) {
			$this->log($e->getMessage(), 'ModuleAvisotaRecipientForm::handleSubscribeTokens', 'TL_ERROR');
		}
		return false;
	}

	protected function handleUnsubscribeSubmit(
		array $recipientData,
		array $mailingLists,
		FrontendTemplate $template
	) {
		try {
			// search for the recipient
			$recipient = AvisotaIntegratedRecipient::byEmail($recipientData['email']);

			// unsubscribe from lists
			$unsubscribedLists = $recipient->unsubscribe($mailingLists);

			if ($unsubscribedLists === false || !count($unsubscribedLists)) {
				return array('not_subscribed', $GLOBALS['TL_LANG']['avisota_unsubscribe']['notSubscribed']);
			}

			if ($this->avisota_unsubscribe_confirmation_page) {
				$jumpToPage = $this->getPageDetails($this->avisota_unsubscribe_confirmation_page);
				$this->redirect($this->generateFrontendUrl($jumpToPage->row()));
			}

			$template->hideForm = true;

			return array('unsubscribed', $GLOBALS['TL_LANG']['avisota_unsubscribe']['unsubscribed']);
		}
		catch (AvisotaRecipientException $e) {
			return array('not_subscribed', $GLOBALS['TL_LANG']['avisota_unsubscribe']['notSubscribed']);
		}
	}

	/**
	 * Generate the content element
	 */
	protected function addForm()
	{
		// create the new form
		$template = new FrontendTemplate($this->formTemplate);
		$template->setData($this->arrData);

		// set defaults
		$template->tableless = $this->tableless;
		$template->fields    = '';

		// flag that store the submit state
		$doNotSubmit = false;

		// Email is mandatory field
		$editables = array('email');

		// Show lists if visible
		if ($this->avisota_show_lists) {
			$editables[] = 'lists';
		}

		// Add more detail fields
		if (count($this->avisota_recipient_fields)) {
			$editables = array_merge($editables, $this->avisota_recipient_fields);
		}

		// The recipient data
		$recipientData    = array();
		$mailingLists = array();

		// The form fields
		$fields = array();
		$hasUpload = false;
		$i         = 0;

		// add the lists options
		if ($this->avisota_show_lists) {
			$list = $this->Database
				->execute(
				"SELECT
						*
					FROM
						tl_avisota_mailing_list" . (count($this->avisota_lists) ? "
					WHERE
						id IN (" . implode(',', $this->avisota_lists) . ")" : '') . "
					ORDER BY
						title"
			);
			while ($list->next()) {
				$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['lists']['options'][$list->id] = $list->title;
			}
		}

		// or set selected lists, if they are not displayed
		else if (count($this->avisota_lists)) {
			$mailingLists = $this->avisota_lists;
		}

		// or use all, if there are no lists selected
		else {
			$mailingLists = $this->Database
				->query("SELECT id FROM tl_avisota_mailing_list")
				->fetchEach('id');
		}

		// Build form
		foreach ($editables as $field) {
			$fieldConfig = $GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$field];

			// Map checkboxWizard to regular checkbox widget
			if ($fieldConfig['inputType'] == 'checkboxWizard') {
				$fieldConfig['inputType']        = 'checkbox';
				$fieldConfig['eval']['multiple'] = true;
			}

			$class = $GLOBALS['TL_FFL'][$fieldConfig['inputType']];

			// Continue if the class is not defined
			if (!$this->classFileExists($class)) {
				continue;
			}

			$fieldConfig['eval']['tableless'] = $this->tableless;
			$fieldConfig['eval']['required']  = $fieldConfig['eval']['mandatory'];

			$widget = new $class($this->prepareForWidget($fieldConfig, $field, $fieldConfig['default']));

			$widget->storeValues = true;
			$widget->rowClass    = 'row_' . $i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even'
				: ' odd');

			// Increase the row count if its a password field
			if ($widget instanceof FormPassword) {
				$widget->rowClassConfirm = 'row_' . ++$i . ((($i % 2) == 0) ? ' even' : ' odd');
			}

			// Validate input
			if ($this->Input->post('FORM_SUBMIT') == $this->formName) {
				$widget->validate();
				$value = $widget->value;

				$rgxp = $fieldConfig['eval']['rgxp'];

				// Convert date formats into timestamps (check the eval setting first -> #3063)
				if (($rgxp == 'date' || $rgxp == 'time' || $rgxp == 'datim') && $value != '') {
					$date  = new Date($value, $GLOBALS['TL_CONFIG'][$rgxp . 'Format']);
					$value = $date->tstamp;
				}

				// Make sure that unique fields are unique (check the eval setting first -> #3063)
				if ($fieldConfig['eval']['unique'] && $value != '') {
					$unique = $this->Database
						->prepare("SELECT * FROM tl_avisota_recipient WHERE " . $field . "=?")
						->limit(1)
						->execute($value);

					if ($unique->numRows) {
						$widget->addError(
							sprintf(
								$GLOBALS['TL_LANG']['ERR']['unique'],
								(strlen($fieldConfig['label'][0]) ? $fieldConfig['label'][0] : $field)
							)
						);
					}
				}

				// Save callback
				if (is_array($fieldConfig['save_callback'])) {
					foreach ($fieldConfig['save_callback'] as $callback) {
						$this->import($callback[0]);

						try {
							$value = $this->$callback[0]->$callback[1]($value, $this->User);
						}
						catch (Exception $e) {
							$widget->class = 'error';
							$widget->addError($e->getMessage());
						}
					}
				}

				if ($widget->hasErrors()) {
					$doNotSubmit = true;
				}

				// Store current value
				$recipientData[$field] = $value;
			}

			if ($widget instanceof uploadable) {
				$hasUpload = true;
			}

			$temp = $widget->parse();

			$template->fields .= $temp;
			$fields[$field] = $temp;

			++$i;
		}

		// lists have to be an array
		if (!is_array($mailingLists)) {
			$mailingLists = array($mailingLists);
		}

		$template->enctype  = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$template->hasError = $doNotSubmit;

		$messageKey = 'MESSAGE_' . strtoupper(get_class($this)) . '_' . $this->id;
		if ($this->Input->post('FORM_SUBMIT') == $this->formName && !$doNotSubmit) {
			$_SESSION[$messageKey] = $this->submit($recipientData, $mailingLists, $template);
			$this->reload();
		}
		else if (!empty($_SESSION[$messageKey])) {
			list($messageClass, $message, $hideForm) = $_SESSION[$messageKey];
			$template->messageClass = $messageClass;
			$template->message      = $message;
			$template->hideForm     = $hideForm;
			unset($_SESSION[$messageKey]);
		}
		else {
			$lists = $this->handleSubscribeTokens();

			if ($lists && count($lists)) {
				$this->loadLanguageFile('avisota_subscribe');
				$template->messageClass = 'confirm_subscription';
				$template->message      = $GLOBALS['TL_LANG']['avisota_subscribe']['confirmSubscription'];
			}
		}

		// Add fields
		foreach ($fields as $k => $v) {
			$template->$k = $v;
		}

		$template->formId     = $this->formName;
		$template->formAction = $this->avisota_form_target ? $this->generateFrontendUrl(
			$this
				->getPageDetails($this->avisota_form_target)
				->row()
		) : $this->getIndexFreeRequest();

		$this->Template->form = $template->parse();
	}

	protected abstract function submit(array $recipientData, array $mailingLists, FrontendTemplate $template);
}
