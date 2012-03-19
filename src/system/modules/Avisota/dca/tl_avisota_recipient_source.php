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
 * Table tl_avisota_recipient_source
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_source'] = array
(

	// Config
	'config'                => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onload_callback'             => array(array('tl_avisota_recipient_source', 'onload_callback')),
		'onsubmit_callback'           => array(array('tl_avisota_recipient_source', 'onsubmit_callback'))
	),

	// List
	'list'                  => array
	(
		'sorting'           => array
		(
			'mode'                    => 1,
			'flag'                    => 11,
			'fields'                  => array('sorting'),
			'disableGrouping'         => true,
			'root'                    => 0
		),
		'label'             => array
		(
			'fields'                  => array('title', 'type'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">(%s)</span>'
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
		'operations'        => array
		(
			'edit'   => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_avisota_recipient_source', 'toggleIcon')
			),
			'show'   => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'move'   => array
			(
				'button_callback'     => array('tl_avisota_recipient_source', 'move_button_callback')
			)
		),
	),

	// Palettes
	'palettes'              => array(
		'__selector__' => array('type')
	),

	// Meta Palettes
	'metapalettes'          => array
	(
		'default'                      => array(
			'source' => array('type')
		),
		'integrated'                   => array(
			'source'     => array('title', 'type'),
			'integrated' => array('integratedBy', 'integratedDetails'),
			'filter'     => array(':hide', 'filter'),
			'expert'     => array('disable')
		),
		'member'                       => array(
			'source'     => array('title', 'type'),
			'member'     => array('memberBy'),
			'filter'     => array(':hide', 'filter'),
			'expert'     => array('disable')
		),
		'csv_file'                     => array(
			'source'   => array('title', 'type'),
			'csvFile'  => array('csvFileSrc', 'csvColumnAssignment'),
			'expert'   => array('disable')
		)
	),

	'metasubselectpalettes' => array
	(
		'integratedBy' => array
		(
			'integratedByMailingLists'    => array('integratedAllowSingleListSelection', 'integratedMailingLists'),
			'integratedByAllMailingLists' => array('integratedAllowSingleListSelection'),
			'integratedByRecipients'      => array('integratedAllowSingleSelection', 'integratedMailingLists'),
			'integratedByAllRecipients'   => array('integratedAllowSingleSelection')
		),
		'memberBy'     => array
		(
			'memberByMailingLists'            => array('memberAllowSingleMailingListSelection', 'memberMailingLists'),
			'memberByAllMailingLists'         => array('memberAllowSingleMailingListSelection'),
			'memberByGroups'                  => array('memberAllowSingleGroupSelection', 'memberGroups'),
			'memberByAllGroups'               => array('memberAllowSingleGroupSelection'),
			'memberByMailingListMembers'      => array('memberAllowSingleSelection', 'memberMailingLists'),
			'memberByGroupMembers'            => array('memberAllowSingleSelection', 'memberGroups'),
			'memberByAllMembers'              => array('memberAllowSingleSelection')
		)
	),

	// Fields
	'fields'                => array
	(
		'type'                                                                          => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['type'],
			'inputType'               => 'select',
			'options'                 => array_keys($GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE']),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source'],
			'eval'                    => array('mandatory'         => true,
			                                   'submitOnChange'    => true,
			                                   'includeBlankOption'=> true,
			                                   'tl_class'          => 'w50')
		),
		'title'                                                                         => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['title'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=> true,
			                                   'unique'   => true,
			                                   'maxlength'=> 255,
			                                   'tl_class' => 'w50')
		),

		// integrated source
		'integratedBy'                                                                  => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedBy'],
			'inputType'               => 'select',
			'options'                 => array('integratedByMailingLists', 'integratedByAllMailingLists', 'integratedByRecipients', 'integratedByAllRecipients'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source'],
			'eval'                    => array('mandatory'          => true,
			                                   'includeBlankOption' => true,
			                                   'submitOnChange'     => true,
			                                   'tl_class'           => 'w50')
		),
		'integratedMailingLists'                                                        => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedMailingLists'],
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_avisota_mailing_list.title',
			'eval'                    => array('mandatory'=> true,
			                                   'multiple' => true,
			                                   'tl_class' => 'clr')
		),
		'integratedAllowSingleListSelection'                                            => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedAllowSingleListSelection'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'm12 w50')
		),
		'integratedAllowSingleSelection'                                                => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedAllowSingleSelection'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'm12 w50')
		),
		'integratedDetails'                                                             => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedDetails'],
			'default'                 => 'integrated_details',
			'inputType'               => 'select',
			'options'                 => array('integrated_details', 'member_details', 'integrated_member_details'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source'],
			'eval'                    => array('mandatory'=> true,
			                                   'tl_class' => 'w50')
		),
		'integratedFilterByColumns'                                                     => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumns'],
			'inputType'               => 'multiColumnWizard',
			'eval'                    => array(
				'columnFields' => array(
					'field'         => array(
						'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsField'],
						'inputType'               => 'select',
						'options_callback'        => array('tl_avisota_recipient_source', 'getRecipientFilterColumns'),
						'eval'                    => array('style'=> 'width:200px')
					),
					'comparator'    => array(
						'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsComparator'],
						'inputType'               => 'select',
						'options'                 => array(
							'='        => '=',
							'!='       => '!=',
							'<'        => '<',
							'<='       => '<=',
							'>'        => '>',
							'>='       => '>=',
							'LIKE'     => 'LIKE',
							'NOT LIKE' => 'NOT LIKE',
							'REGEXP'   => 'REGEXP'
						),
						'eval'                    => array('style'=> 'width:60px')
					),
					'value'         => array(
						'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsValue'],
						'inputType'               => 'text',
						'eval'                    => array('allowHtml'   => true,
						                                   'preserveTags'=> true,
						                                   'style'       => 'width:300px')
					),
					'noescape'      => array(
						'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['integratedFilterByColumnsNoEscape'],
						'inputType'               => 'checkbox',
						'eval'                    => array()
					)
				)
			)
		),

		// members
		'memberBy'                                                                      => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberBy'],
			'inputType'               => 'select',
			'options'                 => array('memberByMailingLists', 'memberByAllMailingLists', 'memberByGroups', 'memberByAllGroups', 'memberByMailingListMembers', 'memberByGroupMembers', 'memberByAllMembers'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source'],
			'eval'                    => array('mandatory'          => true,
			                                   'includeBlankOption' => true,
			                                   'submitOnChange'     => true,
			                                   'tl_class'           => 'w50')
		),
		'memberAllowSingleMailingListSelection'                                         => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberAllowSingleMailingListSelection'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'm12 w50')
		),
		'memberMailingLists'                                                            => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberMailingLists'],
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_avisota_mailing_list.title',
			'eval'                    => array('mandatory'=> true,
			                                   'multiple' => true,
			                                   'tl_class' => 'clr')
		),
		'memberAllowSingleGroupSelection'                                               => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberAllowSingleGroupSelection'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'm12 w50')
		),
		'memberGroups'                                                                  => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberGroups'],
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=> true,
			                                   'multiple' => true,
			                                   'tl_class' => 'clr')
		),
		'memberAllowSingleSelection'                                                    => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberAllowSingleSelection'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'm12 w50')
		),
		'memberFilterByColumns'                                                         => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumns'],
			'inputType'               => 'multiColumnWizard',
			'eval'                    => array(
				'columnFields' => array(
					'field'         => array(
						'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsField'],
						'inputType'               => 'select',
						'options_callback'        => array('tl_avisota_recipient_source', 'getMemberFilterColumns'),
						'eval'                    => array('style'=> 'width:200px')
					),
					'comparator'    => array(
						'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsComparator'],
						'inputType'               => 'select',
						'options'                 => array(
							'='        => '=',
							'!='       => '!=',
							'<'        => '<',
							'<='       => '<=',
							'>'        => '>',
							'>='       => '>=',
							'LIKE'     => 'LIKE',
							'NOT LIKE' => 'NOT LIKE',
							'REGEXP'   => 'REGEXP'
						),
						'eval'                    => array('style'=> 'width:60px')
					),
					'value'         => array(
						'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsValue'],
						'inputType'               => 'text',
						'eval'                    => array('allowHtml'   => true,
						                                   'preserveTags'=> true,
						                                   'style'       => 'width:300px')
					),
					'noescape'      => array(
						'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['memberFilterByColumnsNoEscape'],
						'inputType'               => 'checkbox',
						'eval'                    => array()
					)
				)
			)
		),

		// csv source
		'csvFileSrc'                                                                    => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvFileSrc'],
			'inputType'               => 'fileTree',
			'eval'                    => array('mandatory' => true,
			                                   'files'     => true,
			                                   'filesOnly' => true,
			                                   'extensions'=> 'csv',
			                                   'fieldType' => 'radio')
		),
		'csvColumnAssignment'                                                           => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvColumnAssignment'],
			'inputType'               => 'multiColumnWizard',
			'eval'                    => array(
				'columnFields' => array(
					'column'      => array(
						'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvColumnAssignmentColumn'],
						'inputType'               => 'select',
						'options'                 => range(1, 30),
						'eval'                    => array()
					),
					'field'       => array(
						'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csvColumnAssignmentField'],
						'inputType'               => 'select',
						'options_callback'        => array('tl_avisota_recipient_source', 'getRecipientColumns'),
						'eval'                    => array()
					)
				)
			)
		),

		// filter settings
		'filter'                                                                        => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['filter'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=> true,
			                                   'tl_class'      => 'm12')
		),

		// expert settings
		'disable'                                                                       => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['disable'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=> 'm12')
		)
	)
);

class tl_avisota_recipient_source extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function onload_callback(DataContainer $dc)
	{
		$objSource = $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient_source WHERE id=?")
			->execute($dc->id);

		if ($objSource->next() && $objSource->filter) {
			switch ($objSource->type)
			{
				case 'integrated':
					MetaPalettes::appendFields('tl_avisota_recipient_source', 'integrated', 'filter', array('integratedFilterByColumns'));
					break;

				case 'member':
					MetaPalettes::appendFields('tl_avisota_recipient_source', 'member', 'filter', array('memberFilterByColumns'));
					MetaPalettes::appendFields('tl_avisota_recipient_source', 'memberByMailingLists', 'filter', array('memberFilterByColumns'));
					MetaPalettes::appendFields('tl_avisota_recipient_source', 'memberByGroups', 'filter', array('memberFilterByColumns'));
					MetaPalettes::appendFields('tl_avisota_recipient_source', 'memberByAll', 'filter', array('memberFilterByColumns'));
					break;

			}
		}
	}

	public function onsubmit_callback(DataContainer $dc)
	{
		if ($dc->activeRecord->sorting == 0) {
			$objSource = $this->Database
				->execute("SELECT MAX(sorting) as sorting FROM tl_avisota_recipient_source");
			$this->Database
				->prepare("UPDATE tl_avisota_recipient_source SET sorting=? WHERE id=?")
				->execute($objSource->sorting > 0 ? $objSource->sorting * 2 : 128, $dc->id);
		}
	}


	/**
	 * Check permissions to edit table tl_avisota_recipient_source
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// TODO
	}


	/**
	 * Return the "toggle visibility" button
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
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid'))) {
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_avisota_recipient_source::disable', 'alexf')) {
			return '';
		}

		$href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['disable'] ? '' : '1');

		if ($row['disable']) {
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}


	/**
	 * Toggle the visibility of an element
	 *
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_avisota_recipient_source::disable', 'alexf')) {
			$this->log('Not enough permissions to publish/unpublish newsletter recipient source ID "' . $intId . '"', 'tl_avisota_recipient_source toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_avisota_recipient_source', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['disable']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['disable']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_avisota_recipient_source SET tstamp=" . time() . ", disable='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
			->execute($intId);

		$this->createNewVersion('tl_avisota_recipient_source', $intId);
	}


	public function move_button_callback($arrRow, $href, $label, $title, $icon, $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext)
	{
		$arrDirections = array('up', 'down');
		$href          = '&amp;act=move';
		$return        = '';

		foreach ($arrDirections as $dir)
		{
			$label = strlen($GLOBALS['TL_LANG'][$strTable][$dir][0]) ? $GLOBALS['TL_LANG'][$strTable][$dir][0] : $dir;
			$title = sprintf(strlen($GLOBALS['TL_LANG'][$strTable][$dir][1]) ? $GLOBALS['TL_LANG'][$strTable][$dir][1] : $dir, $arrRow['id']);

			$objSource = $this->Database->prepare("SELECT * FROM tl_avisota_recipient_source WHERE " . ($dir == 'up' ? "sorting<?" : "sorting>?") . " ORDER BY sorting " . ($dir == 'up' ? "DESC" : "ASC"))
				->limit(1)
				->execute($arrRow['sorting']);
			if ($objSource->next()) {
				$return .= ' <a href="' . $this->addToUrl($href . '&amp;id=' . $arrRow['id']) . '&amp;sid=' . intval($objSource->id) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($dir . '.gif', $label) . '</a> ';
			}
			else
			{
				$return .= ' ' . $this->generateImage('system/modules/Avisota/html/' . $dir . '_.gif', $label);
			}
		}

		return trim($return);
	}

	public function getRecipientColumns()
	{
		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');

		$arrOptions = array();

		foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $k=> $v) {
			if ($v['eval']['importable']) {
				$arrOptions[$k] = $v['label'][0];
			}
		}
		asort($arrOptions);

		return $arrOptions;
	}

	public function getRecipientFilterColumns()
	{
		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');

		$arrOptions = array();

		foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $k=> $v) {
			$arrOptions[$k] = $v['label'][0] . ' (' . $k . ')';
		}
		asort($arrOptions);

		return $arrOptions;
	}

	public function getMemberFilterColumns()
	{
		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');

		$arrOptions = array();

		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k=> $v) {
			$arrOptions[$k] = $v['label'][0] . ' (' . $k . ')';
		}
		asort($arrOptions);

		return $arrOptions;
	}
}
