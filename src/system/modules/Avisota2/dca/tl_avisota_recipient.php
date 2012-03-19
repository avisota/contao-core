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
 * Table tl_avisota_recipient
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient'] = array
(

	// Config
	'config'       => array
	(
		'dataContainer'               => 'Table',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback'             => array
		(
			array('tl_avisota_recipient', 'checkPermission'),
			array('AvisotaDCA', 'filterByMailingLists'),
			array('tl_avisota_recipient', 'onload_callback')
		),
		'onsubmit_callback'           => array
		(
			array('tl_avisota_recipient', 'onsubmit_callback')
		),
		'ondelete_callback'           => array
		(
			array('tl_avisota_recipient', 'ondelete_callback')
		)
	),

	// List
	'list'         => array
	(
		'sorting'           => array
		(
			'mode'                    => 2,
			'fields'                  => array('email'),
			'panelLayout'             => 'filter;sort,search,limit',
		),
		'label'             => array
		(
			'fields'                  => array('firstname', 'lastname', 'email'),
			'format'                  => '%s %s &lt;%s&gt;',
			'label_callback'          => array('tl_avisota_recipient', 'getLabel')
		),
		'global_operations' => array
		(
			'tools'   => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['tools'],
				'class'               => 'header_recipient_tools',
				'attributes'          => 'id="header_recipient_tools" onclick="Backend.getScrollOffset();"'
			),
			'migrate' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['migrate'],
				'href'                => 'table=tl_avisota_recipient_migrate&amp;act=edit',
				'class'               => 'header_recipient_migrate recipient_tool',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'import'  => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['import'],
				'href'                => 'table=tl_avisota_recipient_import&amp;act=edit',
				'class'               => 'header_recipient_import recipient_tool',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'export'  => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['export'],
				'href'                => 'table=tl_avisota_recipient_export&amp;act=edit',
				'class'               => 'header_recipient_export recipient_tool',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'remove'  => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['remove'],
				'href'                => 'table=tl_avisota_recipient_remove&amp;act=edit',
				'class'               => 'header_recipient_remove recipient_tool',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'all'     => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations'        => array
		(
			'edit'                => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_avisota_recipient', 'editRecipient')
			),
			'delete'              => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'class="contextmenu" onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_avisota_recipient', 'deleteRecipient')
			),
			'delete_no_blacklist' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['delete_no_blacklist'],
				'href'                => 'act=delete&amp;blacklist=false',
				'icon'                => 'delete.gif',
				'attributes'          => 'class="edit-header" onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_avisota_recipient', 'deleteRecipientNoBlacklist')
			),
			'show'                => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'notify'            => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['notify'],
				'href'                => '',
				'icon'                => 'system/modules/Avisota2/html/notify.png',
				'button_callback'     => array('tl_avisota_recipient', 'notify')
			),
			'tracking'            => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['tracking'],
				'href'                => '',
				'icon'                => 'system/modules/Avisota2/html/tracking.png',
				'button_callback'     => array('tl_avisota_recipient', 'tracking')
			)
		),
	),

	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'recipient'    => array('email'),
			'subscription' => array('lists', 'subscriptionAction'),
			'personals'    => array('salutation', 'title', 'firstname', 'lastname', 'gender')
		)
	),

	// Fields
	'fields'       => array
	(
		'email'              => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'  => 'w50',
			                                   'rgxp'      => 'email',
			                                   'mandatory' => true,
			                                   'maxlength' => 255,
			                                   'importable'=> true,
			                                   'exportable'=> true),
			'save_callback'           => array
			(
				array('tl_avisota_recipient', 'saveEmail')
			)
		),
		'lists'              => array
		(
			'label'                      => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['lists'],
			'inputType'                  => 'checkbox',
			'foreignKey'                 => 'tl_avisota_mailing_list.title',
			'eval'                       => array('multiple'               => true,
			                                      'doNotSaveEmpty'         => true,
			                                      'doNotCopy'              => true,
			                                      'doNotShow'              => true,
			                                      'tl_class'               => 'clr'),
			'load_callback'              => array(array('tl_avisota_recipient', 'loadMailingLists')),
			'save_callback'              => array
			(
				array('tl_avisota_recipient', 'validateBlacklist'),
				array('tl_avisota_recipient', 'saveMailingLists')
			)
		),
		'subscriptionAction' => array
		(
			'label'                          => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['subscriptionAction'],
			'inputType'                      => 'select',
			'options'                        => array('sendConfirmation', 'activateSubscription', 'doNothink'),
			'reference'                      => &$GLOBALS['TL_LANG']['tl_avisota_recipient'],
			'eval'                           => array('doNotSaveEmpty'         => true,
			                                          'doNotCopy'              => true,
			                                          'doNotShow'              => true),
			'save_callback'                  => array(array('tl_avisota_recipient', 'saveSubscriptionAction'))
		),
		'salutation'         => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['salutation'],
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'select',
			'options'                 => array(), //array_combine($GLOBALS['TL_CONFIG']['avisota_salutations'], $GLOBALS['TL_CONFIG']['avisota_salutations']),
			'eval'                    => array('maxlength'         => 255,
			                                   'includeBlankOption'=> true,
			                                   'importable'        => true,
			                                   'exportable'        => true,
			                                   'feEditable'        => true,
			                                   'tl_class'          => 'w50')
		),
		'title'              => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength' => 255,
			                                   'importable'=> true,
			                                   'exportable'=> true,
			                                   'feEditable'=> true,
			                                   'tl_class'  => 'w50')
		),
		'firstname'          => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['firstname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength' => 255,
			                                   'importable'=> true,
			                                   'exportable'=> true,
			                                   'feEditable'=> true,
			                                   'tl_class'  => 'w50')
		),
		'lastname'           => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['lastname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength' => 255,
			                                   'importable'=> true,
			                                   'exportable'=> true,
			                                   'feEditable'=> true,
			                                   'tl_class'  => 'w50')
		),
		'gender'             => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['gender'],
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'inputType'               => 'select',
			'options'                 => array('male', 'female'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('includeBlankOption'=> true,
			                                   'importable'        => true,
			                                   'exportable'        => true,
			                                   'feEditable'        => true,
			                                   'tl_class'          => 'clr')
		),
		'token'              => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['token']
		),
		'addedOn'            => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedOn'],
			'default'                 => time(),
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'eval'                    => array('importable'=> true,
			                                   'exportable'=> true,
			                                   'doNotShow' => true,
			                                   'doNotCopy' => true)
		),
		'addedBy'            => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedBy'],
			'default'                 => $this->User->id,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('importable'=> true,
			                                   'exportable'=> true,
			                                   'doNotShow' => true,
			                                   'doNotCopy' => true)
		)
	)
);

class tl_avisota_recipient extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function getLabel($arrRow, $strLabel, DataContainer $dc)
	{
		$strLabel = trim($arrRow['firstname'] . ' ' . $arrRow['lastname']);
		if (strlen($strLabel)) {
			$strLabel .= ' &lt;' . $arrRow['email'] . '&gt;';
		}
		else
		{
			$strLabel = $arrRow['email'];
		}

		$strLabel .= ' <span style="color:#b3b3b3; padding-left:3px;">(';
		$strLabel .= sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient']['addedOn'][2], $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['addedOn']));
		if ($arrRow['addedBy'] > 0) {
			$objUser = $this->Database->prepare("SELECT * FROM tl_user WHERE id=?")
				->execute($arrRow['addedBy']);
			$strLabel .= sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient']['addedBy'][2], $objUser->next() ? $objUser->name : $GLOBALS['TL_LANG']['tl_avisota_recipient']['addedBy'][3]);
		}
		$strLabel .= ')</span>';

		$strLabel .= '<ul style="margin-top: 3px;">';

		$objList = $this->Database
			->prepare("SELECT ml.*, rtml.confirmed, rtml.confirmationSent, rtml.reminderSent, rtml.reminderCount FROM tl_avisota_mailing_list ml INNER JOIN tl_avisota_recipient_to_mailing_list rtml ON ml.id=rtml.list WHERE rtml.recipient=? ORDER BY ml.title")
			->execute($arrRow['id']);
		while ($objList->next()) {
			$strLabel .= '<li>';
			$strLabel .= '<a href="javascript:void(0);" onclick="if ($(this).getProperty(\'data-confirmed\') || confirm(' . specialchars(json_encode($GLOBALS['TL_LANG']['tl_avisota_recipient']['confirmManualActivation'])) . ')) Avisota.toggleConfirmation(this);" data-recipient="' . $arrRow['id'] . '" data-list="' . $objList->id . '" data-confirmed="' . ($objList->confirmed ? '1' : '') . '">';
			$strLabel .= $this->generateImage(sprintf('system/themes/%s/images/%s.gif', $this->getTheme(), $objList->confirmed ? 'visible' : 'invisible'), '');
			$strLabel .= '</a> ';
			$strLabel .= $objList->title;
			if ($objList->confirmationSent || $objList->reminderSent) {
				$strLabel .= ' <span style="color:#b3b3b3; padding-left:3px;">(';
				if ($objList->reminderCount > 1) {
					$strLabel .= sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient']['remindersSent'],
						$objList->reminderCount,
						$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat']));
				}
				else if ($objList->reminderSent > 0) {
					$strLabel .= sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient']['reminderSent'],
						$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat']));
				}
				else if ($objList->confirmationSent > 0) {
					$strLabel .= sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient']['confirmationSent'],
						$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat']));
				}
				$strLabel .= ')</span>';
			}
			$strLabel .= '</li>';
		}

		$strLabel .= '</ul>';

		return $strLabel;
	}

	public function onload_callback($dc)
	{
		if ($this->Input->get('act') == 'toggleConfirmation') {
			$intRecipient = $this->Input->get('recipient');
			$intList = $this->Input->get('list');

			$this->Database
				->prepare("UPDATE tl_avisota_recipient_to_mailing_list SET confirmed=? WHERE recipient=? AND list=?")
				->execute($this->Input->get('confirmed') ? '1' : '', $intRecipient, $intList);

			header('Content-Type: application/javascript');
			echo json_encode(array(
				'confirmed' => $this->Input->get('confirmed') ? true : false
			));
			exit;
		}
	}

	public function onsubmit_callback($dc)
	{
		$objRecipient = AvisotaIntegratedRecipient::byEmail($dc->activeRecord->email);
		$objRecipient->subscribe($_SESSION['avisotaMailingLists'], true);

		switch ($_SESSION['avisotaSubscriptionAction']) {
			case 'sendConfirmation':
				$objRecipient->sendSubscriptionConfirmation($_SESSION['avisotaMailingLists']);
				break;
			case 'activateSubscription':
				$objRecipient->confirmSubscription($_SESSION['avisotaMailingLists']);
				break;
		}

		unset ($_SESSION['avisotaMailingLists'], $_SESSION['avisotaSubscriptionAction']);
	}


	public function ondelete_callback($dc)
	{
		if ($this->Input->get('blacklist') !== 'false')
		{
			$time = time();

			$arrLists = $this->loadMailingLists('', $dc, true);

			// build insert values
			$arrValues = array();
			$arrArgs = array();
			foreach ($arrLists as $intList) {
				$arrValues[] = '(?, ?, ?)';
				$arrArgs[] = $time;
				$arrArgs[] = $intList;
				$arrArgs[] = md5(strtolower($dc->activeRecord->email));
			}

			// on duplicate key update tstamp
			$arrArgs[] = $time;

			// execute query
			$this->Database
				->prepare("INSERT INTO tl_avisota_recipient_blacklist (tstamp, pid, email)
						   VALUES " . implode(',', $arrValues) . "
						   ON DUPLICATE KEY UPDATE tstamp=?")
				->execute($arrArgs);
		}
	}


	/**
	 * Make email lowercase.
	 *
	 * @param $strEmail
	 *
	 * @return string
	 */
	public function saveEmail($strEmail)
	{
		return strtolower($strEmail);
	}


	public function validateBlacklist($arrLists, DataContainer $dc)
	{
		// do not check in frontend mode
		if (TL_MODE == 'FE') {
			return $arrLists;
		}

		$strEmail = $this->Input->post('email');
		$arrLists = deserialize($arrLists, true);
		$arrLists = array_map('intval', $arrLists);
		$arrLists = array_filter($arrLists);
		if (!count($arrLists)) {
			return $arrLists;
		}

		$arrBlacklisted = AvisotaIntegratedRecipient::checkBlacklisted($strEmail, $arrLists);

		if ($arrBlacklisted) {
			$objBlacklist = $this->Database
				->execute("SELECT * FROM tl_avisota_mailing_list
				           WHERE id IN (" . implode(',', $arrBlacklisted) . ")
				           ORDER BY title");
			if ($objBlacklist->numRows)
			{
				$k = 'AVISOTA_BLACKLIST_WARNING_' . md5(implode(',', $objBlacklist->fetchEach('id')));
				if (!(isset($_SESSION[$k]) && time()-$_SESSION[$k]<60))
				{
					$_SESSION[$k] = time();
					throw new Exception(
						sprintf(
							$GLOBALS['TL_LANG']['tl_avisota_recipient'][$objBlacklist->numRows > 1 ? 'blacklists' : 'blacklist'],
							implode(', ', $objBlacklist->fetchEach('title'))
						)
					);
				}
			}
		}
		return $arrLists;
	}

	public function loadMailingLists($varValue, DataContainer $dc, $blnConfirmed = null)
	{
		return $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient_to_mailing_list WHERE recipient=?"
					  . ($blnConfirmed !== null ? ' AND confirmed=?' : ''))
			->execute($dc->id, $blnConfirmed ? '1' : '')
			->fetchEach('list');
	}

	public function saveMailingLists($varValue)
	{
		$_SESSION['avisotaMailingLists'] = $varValue;
		return null;
	}

	public function saveSubscriptionAction($varValue)
	{
		$_SESSION['avisotaSubscriptionAction'] = $varValue;
		return null;
	}

	/**
	 * Check permissions to edit table tl_avisota_recipient
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// Set root IDs
		if (!is_array($this->User->avisota_recipient_lists) || count($this->User->avisota_recipient_lists) < 1) {
			$root = array(0);
		}
		else
		{
			$root = $this->User->avisota_recipient_lists;
		}

		$id = strlen($this->Input->get('id')) ? $this->Input->get('id') : CURRENT_ID;


		// Check permissions to add recipients
		if (!$this->User->hasAccess('create', 'avisota_recipient_permissions')) {
			$GLOBALS['TL_DCA']['tl_avisota_recipient']['config']['closed'] = true;
			unset($GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['global_operations']['migrate']);
			unset($GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['global_operations']['import']);
		}

		// Check permission to delete recipients
		if (!$this->User->hasAccess('delete', 'avisota_recipient_permissions')) {
			unset($GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['global_operations']['remove']);

			// remove edit header class, if only delete without blacklist is allowed
			if ($this->User->hasAccess('delete_no_blacklist', 'avisota_recipient_permissions')) {
				$GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['operations']['delete_no_blacklist']['attributes'] = str_replace(
					'class="edit-header"',
					'',
					$GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['operations']['delete_no_blacklist']['attributes']);
			}
			else
			{
				unset($GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['operations']['delete_no_blacklist']);
			}
		}

		// remove tools if there are no tools
		$intTools = 0;
		foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['global_operations'] as $arrGlobalOperation)
		{
			if (strpos($arrGlobalOperation['class'], 'recipient_tool') !== false) {
				$intTools++;
			}
		}
		if ($intTools <= 1) {
			unset($GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['global_operations']['tools']);
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
				if (!strlen($this->Input->get('pid')) || !in_array($this->Input->get('pid'), $root) || !$this->User->hasAccess('create', 'avisota_recipient_permissions')) {
					$this->log('Not enough permissions to create newsletters recipients in list ID "' . $this->Input->get('pid') . '"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'edit':
			case 'show':
			case 'copy':
			case 'paste':
			case 'delete':
			case 'toggle':
				$objRecipient = $this->Database->prepare("SELECT pid FROM tl_avisota_recipient WHERE id=?")
					->limit(1)
					->execute($id);

				if ($objRecipient->numRows < 1) {
					$this->log('Invalid newsletter recipient ID "' . $id . '"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				switch ($this->Input->get('act'))
				{
					case 'edit':
					case 'toggle':
						$blnHasAccess = (count(preg_grep('/^tl_avisota_recipient::/', $this->User->alexf)) > 0);
						break;

					case 'show':
						$blnHasAccess = true;
						break;

					case 'copy':
						$blnHasAccess = ($this->User->hasAccess('create', 'avisota_recipient_permissions'));
						break;

					case 'delete':
						$blnHasAccess = ($this->User->hasAccess($this->Input->get('blacklist') == 'false' ? 'delete_no_blacklist' : 'delete', 'avisota_recipient_permissions'));
						break;
				}
				if (!in_array($objRecipient->pid, $root) || !$blnHasAccess) {
					$this->log('Not enough permissions to ' . $this->Input->get('act') . ' recipient ID "' . $id . '" of recipient list ID "' . $objRecipient->pid . '"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'select':
			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				switch ($this->Input->get('act'))
				{
					case 'select':
						$blnHasAccess = true;
						break;

					case 'editAll':
					case 'overrideAll':
						$blnHasAccess = (count(preg_grep('/^tl_avisota_recipient::/', $this->User->alexf)) > 0);
						break;

					case 'deleteAll':
						$blnHasAccess = ($this->User->hasAccess($this->Input->get('blacklist') == 'false' ? 'delete_no_blacklist' : 'delete', 'avisota_recipient_permissions'));
						break;
				}
				if (!in_array($id, $root) || !$blnHasAccess) {
					$this->log('Not enough permissions to access recipient list ID "' . $id . '"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objRecipient = $this->Database->prepare("SELECT id FROM tl_avisota_recipient WHERE pid=?")
					->execute($id);

				if ($objRecipient->numRows < 1) {
					$this->log('Invalid newsletter recipient ID "' . $id . '"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session                   = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objRecipient->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act'))) {
					$this->log('Invalid command "' . $this->Input->get('act') . '"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access newsletter recipient ID "' . $id . '"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Return the edit header button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function editRecipient($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^tl_avisota_recipient::/', $this->User->alexf)) > 0) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : '';
	}


	/**
	 * Return the copy channel button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function copyRecipient($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'avisota_recipient_permissions')) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)) . ' ';
	}


	/**
	 * Return the delete channel button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function deleteRecipient($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'avisota_recipient_permissions')) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)) . ' ';
	}


	/**
	 * Return the delete channel button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function deleteRecipientNoBlacklist($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete_no_blacklist', 'avisota_recipient_permissions')) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)) . ' ';
	}


	/**
	 * Return the notify button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function notify($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="contao/main.php?do=avisota_recipients&amp;table=tl_avisota_recipient_notify&amp;act=edit&amp;id=' . $row['id'] . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}


	/**
	 * Return the tracking button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function tracking($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="contao/main.php?do=avisota_tracking&amp;recipient=' . urlencode($row['email']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}
}
