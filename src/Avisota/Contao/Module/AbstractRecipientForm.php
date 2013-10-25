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

namespace Avisota\Contao\Module;

use Avisota\Contao\Entity\MailingList;
use Avisota\Contao\Entity\Recipient;
use Avisota\Contao\Message\PreRenderedMessageTemplateInterface;
use Avisota\Contao\Message\Renderer\MessagePreRendererInterface;
use Avisota\Contao\Subscription\SubscriptionManagerInterface;
use Avisota\Recipient\MutableRecipient;
use Avisota\Transport\TransportInterface;
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
	public function __construct($module)
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
		$this->avisota_lists            = array_filter(deserialize($this->avisota_lists, true));

		return parent::generate();
	}

	protected function handleSubscribeSubmit(
		array $recipientData,
		array $mailingLists,
		$mailBoilerplateId,
		$transportId
	) {
		$subscriptionManager = $GLOBALS['container']['avisota.subscription'];
		$recipient           = $subscriptionManager->resolveRecipient(
			'Avisota\Contao:Recipient',
			$recipientData,
			true
		);
		$subscriptions       = $subscriptionManager->subscribe(
			$recipient,
			$mailingLists,
			SubscriptionManagerInterface::OPT_IGNORE_BLACKLIST
		);

		if (count($subscriptions)) {
			$this->loadLanguageFile('avisota_subscription');

			$tokens = array();
			foreach ($subscriptions as $subscription) {
				$tokens[] = $subscription->getToken();
			}

			$parameters = array(
				'email' => $recipientData['email'],
				'token' => implode(',', $tokens),
			);

			$url = \Environment::getInstance()->base . \Environment::getInstance()->request;
			$url .= (strpos($url, '?') === false ? '?' : '&');
			$url .= http_build_query($parameters);

			$subscription = $subscriptions[0];
			$recipient    = $subscription->getRecipient();

			// TODO
			$newsletterData         = array();
			$newsletterData['link'] = array(
				'url'  => $url,
				'text' => $GLOBALS['TL_LANG']['avisota_subscription']['confirmSubscription'],
			);

			$this->sendMessage($recipient, $mailBoilerplateId, $transportId, $newsletterData);

			return array('confirm', $GLOBALS['TL_LANG']['avisota_subscription']['subscribed'], true);
		}

		return array('duplicate', $GLOBALS['TL_LANG']['avisota_subscription']['allreadySubscribed'], false);
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

			$subscriptionManager = $GLOBALS['container']['avisota.subscription'];
			return $subscriptionManager->confirm(
				$recipient,
				$tokens
			);
		}

		return false;
	}

	protected function handleUnsubscribeSubmit(
		array $recipientData,
		array $mailingLists,
		$mailBoilerplateId,
		$transportId
	) {
		$recipientRepository = EntityHelper::getRepository('Avisota\Contao:Recipient');

		/** @var Recipient $recipient */
		$recipient = $recipientRepository->findOneBy(array('email' => strtolower($recipientData['email'])));

		if (!$recipient) {
			return false;
		}

		$subscriptionManager = $GLOBALS['container']['avisota.subscription'];
		$subscriptions       = $subscriptionManager->unsubscribe(
			$recipient,
			$mailingLists
		);

		if (count($subscriptions)) {
			// TODO
			$newsletterData = array();
			$this->sendMessage($recipient, $mailBoilerplateId, $transportId, $newsletterData);

			 //redirect to confirmation page if set 
			if ($this->avisota_unsubscribe_confirmation_page) { 
				$this->redirectToFrontendPage($this->avisota_unsubscribe_confirmation_page);
			}
			return array('confirm', $GLOBALS['TL_LANG']['avisota_subscription']['unsubscribed'], true);
		}

		return array('notfound', $GLOBALS['TL_LANG']['avisota_subscription']['notSubscribed'], false);
	}

	protected function sendMessage($recipient, $mailBoilerplateId, $transportId, $newsletterData)
	{
		$messageRepository = EntityHelper::getRepository('Avisota\Contao:Message');
		$messageEntity     = $messageRepository->find($mailBoilerplateId);

		if (!$messageEntity) {
			throw new \RuntimeException('Could not find message id ' . $mailBoilerplateId);
		}

		/** @var MessagePreRendererInterface $renderer */
		$renderer           = $GLOBALS['container']['avisota.renderer'];
		$preRenderedMessage = $renderer->renderMessage($messageEntity);
		$message            = $preRenderedMessage->render($recipient, $newsletterData);

		/** @var TransportInterface $transport */
		$transport = $GLOBALS['container']['avisota.transport.' . $transportId];

		$transport->send($message);
	}

	/**
	 * Generate the content element
	 */
	protected function addForm()
	{
		// create the new form
		$template = new \TwigFrontendTemplate($this->formTemplate);
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
		$recipientData = array();
		$mailingLists  = array();

		// The form fields
		$fields    = array();
		$hasUpload = false;
		$i         = 0;

		// fetch mailing lists
		if (count($this->avisota_lists)) {
			$queryBuilder = EntityHelper::getEntityManager()
				->createQueryBuilder();
			$mailingLists = $queryBuilder
				->select('m')
				->from('Avisota\Contao:MailingList', 'm')
				->where(
					$queryBuilder
						->expr()
						->in('m.id', '?1')
				)
				->setParameter(1, $this->avisota_lists)
				->getQuery()
				->getResult();
		}

		// add the lists options
		if ($this->avisota_show_lists) {
			/** @var MailingList $mailingList */
			foreach ($mailingLists as $mailingList) {
				$GLOBALS['TL_DCA']['orm_avisota_recipient']['fields']['lists']['options'][$mailingList->getId(
				)] = $mailingList->getTitle();
			}
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

			$fields[$field] = $widget;

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
				//redirect to confirmation page if set
				if ($this->avisota_subscribe_confirmation_page) {
					$this->redirectToFrontendPage($this->avisota_subscribe_confirmation_page);
				}
				$this->loadLanguageFile('avisota_subscription');
				$template->messageClass = 'confirm_subscription';
				$template->message      = $GLOBALS['TL_LANG']['avisota_subscription']['subscribeConfirmation'];
			}
		}

		// generate form action page
		if ($this->avisota_form_target) {
			$formAction = $this->generateFrontendUrl(
				$this
					->getPageDetails($this->avisota_form_target)
					->row()
			);
		}
		else {
			$formAction = $this->getIndexFreeRequest();
		}

		// Complete formular data
		$template->fields     = $fields;
		$template->formId     = $this->formName;
		$template->formAction = $formAction;

		$this->Template->form = $template->parse();
	}

	protected abstract function submit(array $recipientData, array $mailingLists, \TwigFrontendTemplate $template);
}
