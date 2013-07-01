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

use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\SubscriptionManager;
use Contao\Doctrine\ORM\EntityHelper;

/**
 * Class ModuleAvisotaRecipientForm
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
abstract class AbstractRecipientForm extends \TwigModule
{
	const ACTION_SUBSCRIBED = 1;

	const ACTION_NOTIFIED = 2;

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

		$this->formName = standardize(get_class($this)) . '_' . $module->id;
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		$this->loadLanguageFile('avisota');
		$this->loadLanguageFile('orm_avisota_recipient');
		$this->loadDataContainer('orm_avisota_recipient');

		// Call onload_callback (e.g. to check permissions)
		if (is_array($GLOBALS['TL_DCA']['orm_avisota_recipient']['config']['onload_callback'])) {
			foreach ($GLOBALS['TL_DCA']['orm_avisota_recipient']['config']['onload_callback'] as $callback) {
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1](null);
			}
		}

		// Deserialize module configuration
		$this->avisota_recipient_fields = deserialize($this->avisota_recipient_fields, true);
		$this->avisota_lists            = array_filter(array_map('intval', deserialize($this->avisota_lists, true)));

		return parent::generate();
	}

	protected function handleSubscribeSubmit(array $recipientData, array $mailingLists)
	{
		$entityManager = EntityHelper::getEntityManager();
		$recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');

		/** @var Recipient $recipient */
		$recipient = $recipientRepository->findOneBy(array('email' => strtolower($recipientData['email'])));

		if (!$recipient) {
			$recipient = new Recipient();
		}

		$recipient->fromArray($recipientData);
		$entityManager->persist($recipient);
		$entityManager->flush();

		$subscriptionManager = new SubscriptionManager();
		$subscriptions = $subscriptionManager->subscribe(
			$recipient,
			$mailingLists,
			SubscriptionManager::OPT_IGNORE_BLACKLIST
		);

		return $subscriptions;
	}

	protected function handleSubscribeTokens()
	{
		$email = $this->Input->get('email');
		$token = $this->Input->get('token');
		$this->Input->setGet('token', '');

		$recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');

		/** @var Recipient $recipient */
		$recipient = $recipientRepository->findOneBy(array('email' => strtolower($email)));

		if (!$recipient) {
			return false;
		}

		if ($token) {
			$tokens = explode(',', $token);

			$subscriptionManager = new SubscriptionManager();
			return $subscriptionManager->confirm(
				$recipient,
				$tokens
			);
		}

		return false;
	}

	protected function handleUnsubscribeSubmit(
		array $recipientData,
		array $mailingLists
	) {
		$recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');

		/** @var Recipient $recipient */
		$recipient = $recipientRepository->findOneBy(array('email' => strtolower($recipientData['email'])));

		if (!$recipient) {
			return false;
		}

		$subscriptionManager = new SubscriptionManager();
		return $subscriptionManager->unsubscribe(
			$recipient,
			$mailingLists
		);
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
						orm_avisota_mailing_list" . (count($this->avisota_lists) ? "
					WHERE
						id IN (" . implode(',', $this->avisota_lists) . ")" : '') . "
					ORDER BY
						title"
			);
			while ($list->next()) {
				$GLOBALS['TL_DCA']['orm_avisota_recipient']['fields']['lists']['options'][$list->id] = $list->title;
			}
		}

		// or set selected lists, if they are not displayed
		else if (count($this->avisota_lists)) {
			$mailingLists = $this->avisota_lists;
		}

		// or use all, if there are no lists selected
		else {
			$mailingLists = $this->Database
				->query("SELECT id FROM orm_avisota_mailing_list")
				->fetchEach('id');
		}

		// Build form
		foreach ($editables as $field) {
			$fieldConfig = $GLOBALS['TL_DCA']['orm_avisota_recipient']['fields'][$field];

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
						->prepare("SELECT * FROM orm_avisota_recipient WHERE " . $field . "=?")
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
