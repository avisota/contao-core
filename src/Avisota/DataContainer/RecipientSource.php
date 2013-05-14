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

namespace Avisota\DataContainer;

class RecipientSource extends \Backend
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
	 * @param \DataContainer $dc
	 */
	public function onload_callback($dc)
	{
		$source = $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient_source WHERE id=?")
			->execute($dc->id);

		if ($source->next() && $source->filter) {
			switch ($source->type) {
				case 'integrated':
					MetaPalettes::appendFields(
						'tl_avisota_recipient_source',
						'integrated',
						'filter',
						array('integratedFilterByColumns')
					);
					break;

				case 'member':
					MetaPalettes::appendFields(
						'tl_avisota_recipient_source',
						'member',
						'filter',
						array('memberFilterByColumns')
					);
					MetaPalettes::appendFields(
						'tl_avisota_recipient_source',
						'memberByMailingLists',
						'filter',
						array('memberFilterByColumns')
					);
					MetaPalettes::appendFields(
						'tl_avisota_recipient_source',
						'memberByGroups',
						'filter',
						array('memberFilterByColumns')
					);
					MetaPalettes::appendFields(
						'tl_avisota_recipient_source',
						'memberByAll',
						'filter',
						array('memberFilterByColumns')
					);
					break;

			}
		}
	}

	/**
	 * @param \DataContainer $dc
	 */
	public function onsubmit_callback($dc)
	{
		if ($dc->activeRecord->sorting == 0) {
			$source = $this->Database
				->execute("SELECT MAX(sorting) as sorting FROM tl_avisota_recipient_source");
			$this->Database
				->prepare("UPDATE tl_avisota_recipient_source SET sorting=? WHERE id=?")
				->execute($source->sorting > 0 ? $source->sorting * 2 : 128, $dc->id);
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

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars(
			$title
		) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}


	/**
	 * Toggle the visibility of an element
	 *
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($id, $isVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $id);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_avisota_recipient_source::disable', 'alexf')) {
			$this->log(
				'Not enough permissions to publish/unpublish newsletter recipient source ID "' . $id . '"',
				'tl_avisota_recipient_source toggleVisibility',
				TL_ERROR
			);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_avisota_recipient_source', $id);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['disable']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['disable']['save_callback'] as $callback) {
				$this->import($callback[0]);
				$isVisible = $this->$callback[0]->$callback[1]($isVisible, $this);
			}
		}

		// Update the database
		$this->Database
			->prepare(
			"UPDATE tl_avisota_recipient_source SET tstamp=" . time() . ", disable='" . ($isVisible ? ''
				: 1) . "' WHERE id=?"
		)
			->execute($id);

		$this->createNewVersion('tl_avisota_recipient_source', $id);
	}


	public function move_button_callback(
		$row,
		$href,
		$label,
		$title,
		$icon,
		$attributes,
		$table,
		$rootIds,
		$childRecordIds,
		$isCircularReference,
		$previous,
		$next
	) {
		$directions = array('up', 'down');
		$href          = '&amp;act=move';
		$return        = '';

		foreach ($directions as $dir) {
			$label = strlen($GLOBALS['TL_LANG'][$table][$dir][0]) ? $GLOBALS['TL_LANG'][$table][$dir][0] : $dir;
			$title = sprintf(
				strlen($GLOBALS['TL_LANG'][$table][$dir][1]) ? $GLOBALS['TL_LANG'][$table][$dir][1] : $dir,
				$row['id']
			);

			$source = $this->Database
				->prepare(
				"SELECT * FROM tl_avisota_recipient_source WHERE " . ($dir == 'up' ? "sorting<?"
					: "sorting>?") . " ORDER BY sorting " . ($dir == 'up' ? "DESC" : "ASC")
			)
				->limit(1)
				->execute($row['sorting']);
			if ($source->next()) {
				$return .= ' <a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '&amp;sid=' . intval(
					$source->id
				) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage(
					$dir . '.gif',
					$label
				) . '</a> ';
			}
			else {
				$return .= ' ' . $this->generateImage('system/modules/avisota/html/' . $dir . '_.gif', $label);
			}
		}

		return trim($return);
	}

	public function getRecipientColumns()
	{
		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');

		$options = array();

		foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $k => $v) {
			if ($v['eval']['importable']) {
				$options[$k] = $v['label'][0];
			}
		}
		asort($options);

		return $options;
	}

	public function getRecipientFilterColumns()
	{
		$this->loadLanguageFile('tl_avisota_recipient');
		$this->loadDataContainer('tl_avisota_recipient');

		$options = array();

		foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $k => $v) {
			$options[$k] = $v['label'][0] . ' (' . $k . ')';
		}
		asort($options);

		return $options;
	}

	public function getMemberFilterColumns()
	{
		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');

		$options = array();

		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k => $v) {
			$options[$k] = $v['label'][0] . ' (' . $k . ')';
		}
		asort($options);

		return $options;
	}

	/**
	 * Autogenerate a news alias if it has not been set yet
	 *
	 * @param mixed $value
	 * @param \DataContainer $dc
	 *
	 * @return string
	 */
	public function generateAlias($value, $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($value)) {
			$autoAlias = true;
			$value     = standardize($dc->activeRecord->title);
		}

		$aliasResultSet = $this->Database
			->prepare("SELECT id FROM tl_avisota_recipient_source WHERE alias=?")
			->execute($value);

		// Check whether the news alias exists
		if ($aliasResultSet->numRows > 1 && !$autoAlias) {
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $value));
		}

		// Add ID to alias
		if ($aliasResultSet->numRows && $autoAlias) {
			$value .= '-' . $dc->id;
		}

		return $value;
	}
}
