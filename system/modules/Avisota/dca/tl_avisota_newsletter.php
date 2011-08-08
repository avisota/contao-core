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
 * Table tl_avisota_newsletter
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_avisota_newsletter_category',
		'ctable'                      => array('tl_avisota_newsletter_content'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_avisota_newsletter', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('subject'),
			'panelLayout'             => 'search,limit',
			'headerFields'            => array('title', 'viewOnlinePage', 'unsubscribePage', 'tstamp', 'useSMTP', 'senderName', 'sender'),
			'child_record_callback'   => array('tl_avisota_newsletter', 'addNewsletter'),
			'child_record_class'      => 'no_padding'
		),
		'global_operations' => array
		(
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
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['edit'],
				'href'                => 'table=tl_avisota_newsletter_content',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'attributes'          => 'class="edit-header"',
				'button_callback'     => array('tl_avisota_newsletter', 'editHeader')
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_avisota_newsletter', 'copyNewsletter')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_avisota_newsletter', 'deleteNewsletter')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'send' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['send'],
				'href'                => 'key=send',
				'icon'                => 'system/modules/Avisota/html/send.png',
				'button_callback'     => array('tl_avisota_newsletter', 'sendNewsletter')
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addFile'),
		'default'                     => '{newsletter_legend},subject,alias;{recipient_legend},recipients;{attachment_legend},addFile;{template_legend:hide},template_html,template_plain',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addFile'                     => 'files'
	),

	// Fields
	'fields' => array
	(
		'subject' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['subject'],
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_avisota_newsletter', 'generateAlias')
			)
		),
		'recipients' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipients'],
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_avisota_newsletter', 'getRecipients'),
			'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'tl_class'=>'clr')
		),
		'addFile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['addFile'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'files' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['files'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true)
		),
		'template_html' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_html'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('mail_html_'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'template_plain' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_plain'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('mail_plain_'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'sendOn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['sendOn'],
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'eval'                    => array('rgxp'=>'datim', 'doNotCopy'=>true, 'doNotShow'=>true)
		)
	)
);


class tl_avisota_newsletter extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit table tl_newsletter_channel
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->avisota_newsletter_categories) || count($this->User->avisota_newsletter_categories) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->avisota_newsletter_categories;
		}

		// Check permissions to add channels
		if (!$this->User->hasAccess('create', 'avisota_newsletter_permissions'))
		{
			$GLOBALS['TL_DCA']['tl_avisota_newsletter']['config']['closed'] = true;
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
			case 'copy':
			case 'delete':
			case 'show':
				$intPid = -1;
				if ($this->Input->get('id'))
				{
					$objNewsletter = $this->Database
						->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
						->execute($this->Input->get('id'));
					if ($objNewsletter->next())
					{
						$intPid = $objNewsletter->pid;
					}
				}
				if (!in_array($intPid, $root) || ($this->Input->get('act') == 'delete' && !$this->User->hasAccess('delete', 'avisota_newsletter_permissions')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' avisota newsletter ID "'.$this->Input->get('id').'"', 'tl_avisota_newsletter checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'avisota_newsletter_permissions'))
				{
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' avisota newsletter', 'tl_avisota_newsletter checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
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
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^tl_avisota_newsletter::/', $this->User->alexf)) > 0) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : '';
	}


	/**
	 * Return the copy button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyNewsletter($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'avisota_newsletter_permissions')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteNewsletter($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'avisota_newsletter_permissions')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the send button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function sendNewsletter($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->isAdmin && !$this->User->hasAccess('send', 'avisota_newsletter_permissions'))
		{
			$label = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['view_only'][0];
			$title = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['view_only'][1];
		}
		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	public function getRecipients()
	{
		$arrRecipients = array(
			$GLOBALS['TL_LANG']['tl_avisota_newsletter']['list'] => array(),
			$GLOBALS['TL_LANG']['tl_avisota_newsletter']['mgroup'] => array()
		);

		$objList = $this->Database->execute("
				SELECT
					*
				FROM
					`tl_avisota_recipient_list`
				ORDER BY
					`title`");
		while ($objList->next())
		{
			$arrRecipients[$GLOBALS['TL_LANG']['tl_avisota_newsletter']['list']]['list-' . $objList->id] = $objList->title;
		}

		$objMember = $this->Database->execute("
				SELECT
					*
				FROM
					`tl_member_group`
				ORDER BY
					`name`");
		while ($objMember->next())
		{
			$arrRecipients[$GLOBALS['TL_LANG']['tl_avisota_newsletter']['mgroup']]['mgroup-' . $objMember->id] = $objMember->name;
		}

		return $arrRecipients;
	}


	/**
	 * Add the recipient row.
	 *
	 * @param array
	 */
	public function addNewsletter($arrRow)
	{
		$icon = $arrRow['sendOn'] ? 'visible' : 'invisible';

		$label = $arrRow['subject'];

		if ($row['sendOn'])
		{
			$label .= ' <span style="color:#b3b3b3; padding-left:3px;">(' . sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient']['sended'], $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $row['sendOn'])) . ')</span>';
		}

		return sprintf('<div class="list_icon" style="background-image:url(\'system/themes/%s/images/%s.gif\');">%s</div>', $this->getTheme(), $icon, $label);
	}


	/**
	 * Autogenerate a news alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue))
		{
			$autoAlias = true;
			$varValue = standardize($dc->activeRecord->subject);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_avisota_newsletter WHERE alias=?")
								   ->execute($varValue);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}
}
?>