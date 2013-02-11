<?php

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
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_avisota_newsletter_draft_content
 */
$this->loadLanguageFile('tl_avisota_newsletter_content');
$this->loadDataContainer('tl_avisota_newsletter_content');


/**
 * Note: do not move to end of file!
 */
class tl_avisota_newsletter_draft_content extends tl_avisota_newsletter_content
{
	/**
	 * @var tl_avisota_newsletter_draft_content
	 */
	protected static $objInstance = null;

	/**
	 * @static
	 * @return tl_avisota_newsletter_draft_content
	 */
	public static function getInstance()
	{
		if (self::$objInstance === null) {
			self::$objInstance = new tl_avisota_newsletter_draft_content();
		}
		return self::$objInstance;
	}

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function getPalettes()
	{
		$arrPalette = array_slice(
			$GLOBALS['TL_DCA']['tl_avisota_newsletter_content']['palettes'],
			0
		);

		foreach ($arrPalette as $k => $v) {
			if ($k != '__selector__' && $k != 'default') {
				$arrPalette[$k] .= ';{draft_legend:hide},unmodifiable,undeletable';
			}
		}

		return $arrPalette;
	}

	public function getMetaPalettes()
	{
		$arrPalette = array_slice(
			$GLOBALS['TL_DCA']['tl_avisota_newsletter_content']['metapalettes'],
			0
		);

		foreach ($arrPalette as $k => $v) {
			if ($k != '__selector__' && $k != 'default') {
				$arrPalette[$k]['draft'] = array(':hide', 'unmodifiable', 'undeletable');
			}
		}

		return $arrPalette;
	}

	public function getSubpalettes()
	{
		return array_slice(
			$GLOBALS['TL_DCA']['tl_avisota_newsletter_content']['subpalettes'],
			0
		);
	}

	public function getMetaSubpalettes()
	{
		return array_slice(
			$GLOBALS['TL_DCA']['tl_avisota_newsletter_content']['metasubpalettes'],
			0
		);
	}

	public function getFields($arrFields)
	{
		return array_merge(
			$GLOBALS['TL_DCA']['tl_avisota_newsletter_content']['fields'],
			$arrFields
		);
	}

	/**
	 * Check permissions to edit table tl_avisota_newsletter_draft_content
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		/*
		 * TODO
		// Check the current action
		switch ($this->Input->get('act'))
		{
			case 'paste':
				// Allow
				break;

			case '': // empty
			case 'create':
			case 'select':
				// Check access to the article
				if (!$this->checkAccessToElement(CURRENT_ID, $pagemounts, true))
				{
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				// Check access to the parent element if a content element is moved
				if (($this->Input->get('act') == 'cutAll' || $this->Input->get('act') == 'copyAll') && !$this->checkAccessToElement($this->Input->get('pid'), $pagemounts, ($this->Input->get('mode') == 2)))
				{
					$this->redirect('contao/main.php?act=error');
				}

				$objCes = $this->Database->prepare("SELECT id FROM tl_avisota_newsletter_draft_content WHERE pid=?")
										 ->execute(CURRENT_ID);

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objCes->fetchEach('id'));
				$this->Session->setData($session);
				break;

			case 'cut':
			case 'copy':
				// Check access to the parent element if a content element is moved
				if (!$this->checkAccessToElement($this->Input->get('pid'), $pagemounts, ($this->Input->get('mode') == 2)))
				{
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			default:
				// Check access to the content element
				if (!$this->checkAccessToElement($this->Input->get('id'), $pagemounts))
				{
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
		*/
	}
}


$GLOBALS['TL_DCA']['tl_avisota_newsletter_draft_content'] = array
(

	// Config
	'config'          => array
	(
		'dataContainer'    => 'Table',
		'ptable'           => 'tl_avisota_newsletter_draft',
		'enableVersioning' => true,
		'onload_callback'  => array
		(
			array('tl_avisota_newsletter_draft_content', 'checkPermission')
		)
	),
	// List
	'list'            => array
	(
		'sorting'           => array
		(
			'mode'                  => 4,
			'fields'                => array('sorting'),
			'panelLayout'           => 'filter;search,limit',
			'headerFields'          => array('title', 'description'),
			'child_record_callback' => array('tl_avisota_newsletter_draft_content', 'addElement')
		),
		'global_operations' => array
		(
			'preview' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['preview'],
				'href'  => 'table=&amp;key=preview',
				'class' => 'header_preview'
			),
			'all'     => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations'        => array
		(
			'edit'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'copy'   => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['copy'],
				'href'       => 'act=paste&amp;mode=copy',
				'icon'       => 'copy.gif',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
			'cut'    => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['cut'],
				'href'       => 'act=paste&amp;mode=cut',
				'icon'       => 'cut.gif',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['toggle'],
				'icon'            => 'visible.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback' => array('tl_avisota_newsletter_draft_content', 'toggleIcon')
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		),
	),
	// Palettes
	'palettes'        => tl_avisota_newsletter_draft_content::getInstance()
		->getPalettes(),
	'metapalettes'    => tl_avisota_newsletter_draft_content::getInstance()
		->getMetaPalettes(),
	// Subpalettes
	'subpalettes'     => tl_avisota_newsletter_draft_content::getInstance()
		->getSubpalettes(),
	'metasubpalettes' => tl_avisota_newsletter_draft_content::getInstance()
		->getMetaSubpalettes(),
	// Fields
	'fields'          => tl_avisota_newsletter_draft_content::getInstance()
		->getFields(
		array(
			'unmodifiable' => array
			(
				'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['unmodifiable'],
				'exclude'   => true,
				'inputType' => 'checkbox',
				'eval'      => array('tl_class' => 'w50 clr')
			),
			'undeletable'  => array
			(
				'label'     => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft_content']['undeletable'],
				'exclude'   => true,
				'inputType' => 'checkbox',
				'eval'      => array('tl_class' => 'w50')
			)
		)
	)
);
