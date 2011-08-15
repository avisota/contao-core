<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  InfinitySoft 2010,2011
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
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_avisota_recipient_list',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_avisota_recipient', 'checkPermission')
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
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('email'),
			'panelLayout'             => 'filter;sort,search,limit',
			'headerFields'            => array('title'),
			'child_record_callback'   => array('tl_avisota_recipient', 'addRecipient'),
			'child_record_class'      => 'no_padding'
		),
		'global_operations' => array
		(
			'tools' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['tools'],
				'class'               => 'header_recipient_tools',
				'attributes'          => 'id="header_recipient_tools" onclick="Backend.getScrollOffset();"'
			),
			'migrate' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['migrate'],
				'href'                => 'table=tl_avisota_recipient_migrate',
				'class'               => 'header_recipient_migrate recipient_tool',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'import' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['import'],
				'href'                => 'table=tl_avisota_recipient_import',
				'class'               => 'header_recipient_import recipient_tool',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'export' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['export'],
				'href'                => 'table=tl_avisota_recipient_export',
				'class'               => 'header_recipient_export recipient_tool',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'remove' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['remove'],
				'href'                => 'table=tl_avisota_recipient_remove',
				'class'               => 'header_recipient_remove recipient_tool',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_avisota_recipient', 'editRecipient')
			),
			'delete' => array
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
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_avisota_recipient', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'tracking' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['tracking'],
				'href'                => '',
				'icon'                => 'system/modules/Avisota/html/tracking.png',
				'button_callback'     => array('tl_avisota_recipient', 'tracking')
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{recipient_legend},email;{personals_legend},salutation,title,firstname,lastname,gender,confirmed',
	),

	// Fields
	'fields' => array
	(
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'email', 'mandatory'=>true, 'maxlength'=>255, 'importable'=>true, 'exportable'=>true),
			'save_callback'           => array
			(
				array('tl_avisota_recipient', 'validateBlacklist')
			)
		),
		'lists' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['lists'],
			'exclude'                 => false,
			'sorting'                 => false,
			'inputType'               => 'checkbox',
			'options'                 => array(),
			'eval'                    => array('mandatory'=>true, 'doNotShow'=>true)
		),
		'salutation' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['salutation'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'select',
			'options'                 => array_combine($GLOBALS['TL_CONFIG']['avisota_salutations'], $GLOBALS['TL_CONFIG']['avisota_salutations']),
			'eval'                    => array('maxlength'=>255, 'includeBlankOption'=>true, 'importable'=>true, 'exportable'=>true, 'feEditable'=>true, 'tl_class'=>'w50')
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'importable'=>true, 'exportable'=>true, 'feEditable'=>true, 'tl_class'=>'w50')
		),
		'firstname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['firstname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'importable'=>true, 'exportable'=>true, 'feEditable'=>true, 'tl_class'=>'w50')
		),
		'lastname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['lastname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'importable'=>true, 'exportable'=>true, 'feEditable'=>true, 'tl_class'=>'w50')
		),
		'gender' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['gender'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => array('male', 'female'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('includeBlankOption'=>true, 'importable'=>true, 'exportable'=>true, 'feEditable'=>true, 'tl_class'=>'clr')
		),
		'confirmed' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['confirmed'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('importable'=>true, 'exportable'=>true)
		),
		'token' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['token']
		),
		'addedOn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedOn'],
			'default'                 => time(),
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'eval'                    => array('importable'=>true, 'exportable'=>true, 'doNotShow'=>true, 'doNotCopy'=>true)
		),
		'addedBy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedBy'],
			'default'                 => $this->User->id,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('importable'=>true, 'exportable'=>true, 'doNotShow'=>true, 'doNotCopy'=>true)
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


	public function onsubmit_callback($dc)
	{
	}


	public function ondelete_callback($dc)
	{
		if ($this->Input->get('blacklist') !== 'false')
		{
			$this->Database->prepare("INSERT INTO tl_avisota_recipient_blacklist %s")
				->set(array('pid'=>$dc->activeRecord->pid, 'tstamp'=>time(), 'email'=>md5($dc->activeRecord->email)))
				->execute();
		}
	}


	public function validateBlacklist($strEmail)
	{
		// do not check in frontend mode
		if (TL_MODE == 'FE')
		{
			return $strEmail;
		}

		$objBlacklist = $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient_blacklist WHERE email=?")
			->execute(md5($strEmail));
		if ($objBlacklist->numRows)
		{
			$k = 'AVISOTA_BLACKLIST_WARNING_' . md5($strEmail);
			if (isset($_SESSION[$k]) && time()-$_SESSION[$k]<60)
			{
				$this->Database->prepare("DELETE FROM tl_avisota_recipient_blacklist WHERE pid=? AND email=?")
						->execute($dc->activeRecord->pid, md5($dc->activeRecord->email));
			}
			else
			{
				$_SESSION[$k] = time();
				throw new Exception($GLOBALS['TL_LANG']['tl_avisota_recipient']['blacklist']);
			}
		}
		return $strEmail;
	}


	/**
	 * Add the recipient row.
	 *
	 * @param array
	 */
	public function addRecipient($arrRow)
	{
		$icon = $arrRow['confirmed'] ? 'visible' : 'invisible';

		$label = trim($arrRow['firstname'] . ' ' . $arrRow['lastname']);
		if (strlen($label))
		{
			$label .= ' &lt;' . $arrRow['email'] . '&gt;';
		}
		else
		{
			$label = $arrRow['email'];
		}

		$label .= ' <span style="color:#b3b3b3; padding-left:3px;">(';
		$label .= sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient']['addedOn'][2], $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['addedOn']));
		if ($arrRow['addedBy'] > 0)
		{
			$objUser = $this->Database->prepare("SELECT * FROM tl_user WHERE id=?")
				->execute($arrRow['addedBy']);
			$label .= sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient']['addedBy'][2], $objUser->next() ? $objUser->name : $GLOBALS['TL_LANG']['tl_avisota_recipient']['addedBy'][3]);
		}
		$label .= ')</span>';

		return sprintf('<div class="list_icon" style="background-image:url(\'system/themes/%s/images/%s.gif\');">%s</div>', $this->getTheme(), $icon, $label);
	}


	/**
	 * Check permissions to edit table tl_avisota_recipient
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->avisota_recipient_lists) || count($this->User->avisota_recipient_lists) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->avisota_recipient_lists;
		}

		$id = strlen($this->Input->get('id')) ? $this->Input->get('id') : CURRENT_ID;


		// Check permissions to add recipients
		if (!$this->User->hasAccess('create', 'avisota_recipient_permissions'))
		{
			$GLOBALS['TL_DCA']['tl_avisota_recipient']['config']['closed'] = true;
			unset($GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['global_operations']['migrate']);
			unset($GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['global_operations']['import']);
		}

		// Check permission to delete recipients
		if (!$this->User->hasAccess('delete', 'avisota_recipient_permissions'))
		{
			unset($GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['global_operations']['remove']);

			// remove edit header class, if only delete without blacklist is allowed
			if ($this->User->hasAccess('delete_no_blacklist', 'avisota_recipient_permissions'))
			{
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
			if (strpos($arrGlobalOperation['class'], 'recipient_tool') !== false)
			{
				$intTools ++;
			}
		}
		if ($intTools <= 1)
		{
			unset($GLOBALS['TL_DCA']['tl_avisota_recipient']['list']['global_operations']['tools']);
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
				if (!strlen($this->Input->get('pid')) || !in_array($this->Input->get('pid'), $root) || !$this->User->hasAccess('create', 'avisota_recipient_permissions'))
				{
					$this->log('Not enough permissions to create newsletters recipients in list ID "'.$this->Input->get('pid').'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'edit':
			case 'show':
			case 'copy':
			case 'delete':
			case 'toggle':
				$objRecipient = $this->Database->prepare("SELECT pid FROM tl_avisota_recipient WHERE id=?")
											   ->limit(1)
											   ->execute($id);

				if ($objRecipient->numRows < 1)
				{
					$this->log('Invalid newsletter recipient ID "'.$id.'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
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
				if (!in_array($objRecipient->pid, $root) || !$blnHasAccess)
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' recipient ID "'.$id.'" of recipient list ID "'.$objRecipient->pid.'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
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
				if (!in_array($id, $root) || !$blnHasAccess)
				{
					$this->log('Not enough permissions to access recipient list ID "'.$id.'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objRecipient = $this->Database->prepare("SELECT id FROM tl_avisota_recipient WHERE pid=?")
											 ->execute($id);

				if ($objRecipient->numRows < 1)
				{
					$this->log('Invalid newsletter recipient ID "'.$id.'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objRecipient->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Invalid command "'.$this->Input->get('act').'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access newsletter recipient ID "'.$id.'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_avisota_recipient::confirmed', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['confirmed']?'':'1');

		if (!$row['confirmed'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Toggle the visibility of an element
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_avisota_recipient::confirmed', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish newsletter recipient ID "'.$intId.'"', 'tl_avisota_recipient toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_avisota_recipient', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['confirmed']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['confirmed']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_avisota_recipient SET tstamp=". time() .", confirmed='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_avisota_recipient', $intId);
	}


	/**
	 * Return the edit header button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editRecipient($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^tl_avisota_recipient::/', $this->User->alexf)) > 0) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : '';
	}


	/**
	 * Return the copy channel button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyRecipient($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'avisota_recipient_permissions')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete channel button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteRecipient($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'avisota_recipient_permissions')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete channel button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteRecipientNoBlacklist($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete_no_blacklist', 'avisota_recipient_permissions')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the tracking button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function tracking($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="contao/main.php?do=avisota_tracking&amp;recipient='.urlencode($row['email']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
}

?>