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
 * Class ModuleAvisotaRecipientForm
 *
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
abstract class ModuleAvisotaRecipientForm extends Module
{
	/**
	 * @var string
	 */
	protected $strFormTemplate;

	/**
	 * @var string
	 */
	protected $strFormName;

	/**
	 * Construct the content element
	 */
	public function __construct(Database_Result $objModule)
	{
		parent::__construct($objModule);
		$this->import('DomainLink');
		$this->import('FrontendUser', 'User');
		$this->loadLanguageFile('avisota');

		$this->strFormName = get_class($this) . '_' . $objModule->id;
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
			foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['config']['onload_callback'] as $callback)
			{
				if (is_array($callback)) {
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1](null);
				}
			}
		}

		// Change the label of the permit personal tracing checkbox
		if (isset($GLOBALS['TL_CONFIG']['avisota_data_privacy_statement_page'])) {
			$objDataPrivacyStatementPage = $this->getPageDetails($GLOBALS['TL_CONFIG']['avisota_data_privacy_statement_page']);
		}
		else {
			$objDataPrivacyStatementPage = $GLOBALS['objPage'];
		}
		$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['permitPersonalTracing']['label'] = $GLOBALS['TL_LANG']['tl_avisota_recipient']['permitPersonalTracingFE'];
		$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['permitPersonalTracing']['label'][1] = sprintf(
			$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['permitPersonalTracing']['label'][1],
			$this->generateFrontendUrl($objDataPrivacyStatementPage->row())
		);

		// Deserialize module configuration
		$this->avisota_recipient_fields = deserialize($this->avisota_recipient_fields, true);
		$this->avisota_lists            = array_filter(array_map('intval', deserialize($this->avisota_lists, true)));

		return parent::generate();
	}

	/**
	 * Generate the content element
	 */
	protected function addForm()
	{
		global $objPage;

		// create the new form
		$objTemplate = new FrontendTemplate($this->strFormTemplate);
		$objTemplate->setData($this->arrData);

		// set defaults
		$objTemplate->tableless = $this->tableless;
		$objTemplate->fields    = '';

		// flag that store the submit state
		$doNotSubmit = false;

		// Email is mandatory field
		$arrEditable = array('email');

		// Show lists if visible
		if ($this->avisota_show_lists) {
			$arrEditable[] = 'lists';
		}

		// Add more detail fields
		if (count($this->avisota_recipient_fields)) {
			$arrEditable = array_merge($arrEditable, $this->avisota_recipient_fields);
		}

		// The recipient data
		$arrRecipient    = array();
		$arrMailingLists = array();

		// The form fields
		$arrFields = array();
		$hasUpload = false;
		$i         = 0;

		// add the lists options
		if ($this->avisota_show_lists) {
			$objList = $this->Database
				->execute("SELECT
						*
					FROM
						tl_avisota_mailing_list" . (count($this->avisota_lists) ? "
					WHERE
						id IN (" . implode(',', $this->avisota_lists) . ")" : '') . "
					ORDER BY
						title");
			while ($objList->next())
			{
				$GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['lists']['options'][$objList->id] = $objList->title;
			}
		}

		// or set selected lists, if they are not displayed
		else if (count($this->avisota_lists)) {
			$arrMailingLists = $this->avisota_lists;
		}

		// or use all, if there are no lists selected
		else
		{
			$arrMailingLists = $this->Database
				->query("SELECT id FROM tl_avisota_mailing_list")
				->fetchEach('id');
		}

		// Build form
		foreach ($arrEditable as $field)
		{
			$arrData = $GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$field];

			// Map checkboxWizard to regular checkbox widget
			if ($arrData['inputType'] == 'checkboxWizard') {
				$arrData['inputType']        = 'checkbox';
				$arrData['eval']['multiple'] = true;
			}

			$strClass = $GLOBALS['TL_FFL'][$arrData['inputType']];

			// Continue if the class is not defined
			if (!$this->classFileExists($strClass)) {
				continue;
			}

			$arrData['eval']['tableless'] = $this->tableless;
			$arrData['eval']['required']  = $arrData['eval']['mandatory'];

			$objWidget = new $strClass($this->prepareForWidget($arrData, $field, $arrData['default']));

			$objWidget->storeValues = true;
			$objWidget->rowClass    = 'row_' . $i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');

			// Increase the row count if its a password field
			if ($objWidget instanceof FormPassword) {
				$objWidget->rowClassConfirm = 'row_' . ++$i . ((($i % 2) == 0) ? ' even' : ' odd');
			}

			// Validate input
			if ($this->Input->post('FORM_SUBMIT') == $this->strFormName) {
				$objWidget->validate();
				$varValue = $objWidget->value;

				$rgxp = $arrData['eval']['rgxp'];

				// Convert date formats into timestamps (check the eval setting first -> #3063)
				if (($rgxp == 'date' || $rgxp == 'time' || $rgxp == 'datim') && $varValue != '') {
					$objDate  = new Date($varValue, $GLOBALS['TL_CONFIG'][$rgxp . 'Format']);
					$varValue = $objDate->tstamp;
				}

				// Make sure that unique fields are unique (check the eval setting first -> #3063)
				if ($arrData['eval']['unique'] && $varValue != '') {
					$objUnique = $this->Database->prepare("SELECT * FROM tl_avisota_recipient WHERE " . $field . "=?")
						->limit(1)
						->execute($varValue);

					if ($objUnique->numRows) {
						$objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['unique'], (strlen($arrData['label'][0]) ? $arrData['label'][0] : $field)));
					}
				}

				// Save callback
				if (is_array($arrData['save_callback'])) {
					foreach ($arrData['save_callback'] as $callback)
					{
						$this->import($callback[0]);

						try
						{
							$varValue = $this->$callback[0]->$callback[1]($varValue, $this->User);
						}
						catch (Exception $e)
						{
							$objWidget->class = 'error';
							$objWidget->addError($e->getMessage());
						}
					}
				}

				if ($objWidget->hasErrors()) {
					$doNotSubmit = true;
				}

				// Store current value
				$arrRecipient[$field] = $varValue;
			}

			if ($objWidget instanceof uploadable) {
				$hasUpload = true;
			}

			$temp = $objWidget->parse();

			$objTemplate->fields .= $temp;
			$arrFields[$field] = $temp;

			++$i;
		}

		// lists have to be an array
		if (!is_array($arrMailingLists)) {
			$arrMailingLists = array($arrMailingLists);
		}

		$objTemplate->enctype  = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$objTemplate->hasError = $doNotSubmit;

		$strMessageKey = 'MESSAGE_' . strtoupper(get_class($this)) . '_' . $this->id;
		if ($this->Input->post('FORM_SUBMIT') == $this->strFormName && !$doNotSubmit) {
			$_SESSION[$strMessageKey] = $this->submit($arrRecipient, $arrMailingLists);
			$this->reload();
		}
		else if (!empty($_SESSION[$strMessageKey])) {
			$objTemplate->message = $_SESSION[$strMessageKey];
			unset($_SESSION[$strMessageKey]);
		}

		// Add fields
		foreach ($arrFields as $k=> $v)
		{
			$objTemplate->$k = $v;
		}

		$objTemplate->formId     = $this->strFormName;
		$objTemplate->formAction = $this->avisota_form_target ? $this->generateFrontendUrl($this->getPageDetails($this->avisota_form_target)->row()) : $this->getIndexFreeRequest();

		$this->Template->form = $objTemplate->parse();
	}

	protected abstract function submit(array $arrRecipient, array $arrMailingLists);
}
