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
 * Table tl_avisota_newsletter_content
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter_content'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_avisota_newsletter',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_avisota_newsletter_content', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'panelLayout'             => 'filter;search,limit',
			'headerFields'            => array('subject'),
			'child_record_callback'   => array('tl_avisota_newsletter_content', 'addElement')
		),
		'global_operations' => array
		(
			'view' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['view'],
				'href'                => 'table=tl_avisota_newsletter&amp;key=send',
				'class'               => 'header_send',
				'button_callback'     => array('tl_avisota_newsletter_content', 'sendNewsletterButton')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_avisota_newsletter_content', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'__selector__' => array('type')
	),
	'metapalettes' => array
	(
		'default' => array
		(
			'type' => array('type')
		),
		'headline' => array
		(
			'type'   => array('type', 'area', 'headline'),
			'expert' => array(':hide', 'cssID', 'space')
		),
		'text' => array
		(
			'type'   => array('type', 'area', 'headline'),
			'text'   => array('text', 'definePlain', 'personalize'),
			'image'  => array('addImage'),
			'expert' => array(':hide', 'cssID', 'space')
		),
		'list' => array
		(
			'type'   => array('type', 'area', 'headline'),
			'list'   => array('listtype', 'listitems'),
			'expert' => array(':hide', 'cssID', 'space')
		),
		'table' => array
		(
			'type'     => array('type', 'area', 'headline'),
			'table'    => array('tableitems'),
			'tconfig'  => array('summary', 'thead', 'tfoot'),
			'sortable' => array(':hide', 'sortable'),
			'expert'   => array(':hide', 'cssID', 'space')
		),
		'hyperlink' => array
		(
			'type'   => array('type', 'area', 'headline'),
			'link'   => array('url', 'linkTitle', 'embed'),
			'expert' => array(':hide', 'cssID', 'space')
		),
		'image' => array
		(
			'type'   => array('type', 'area', 'headline'),
			'source' => array('singleSRC'),
			'image'  => array('alt', 'size', 'imagemargin', 'imageUrl', 'caption'),
			'expert' => array(':hide', 'cssID', 'space')
		),
		'gallery' => array
		(
			'type'    => array('type', 'area', 'headline'),
			'source'   => array('multiSRC'),
			'image'    => array('size', 'imagemargin', 'perRow', 'sortBy'),
			'template' => array(':hide', 'galleryHtmlTpl', 'galleryPlainTpl'),
			'expert'   => array(':hide', 'cssID', 'space')
		),
		'news' => array
		(
			'type'    => array('type', 'area', 'headline'),
			'include' => array('news'),
			'expert' => array(':hide', 'cssID', 'space')
		),
		'events' => array
		(
			'type'   => array('type', 'area', 'headline'),
			'events' => array('events'),
			'expert' => array(':hide', 'cssID', 'space')
		),
		'article' => array
		(
			'type'    => array('type', 'area', 'headline'),
			'include' => array('articleAlias'),
			'expert' => array(':hide', 'cssID', 'space')
		)
	),

	// Subpalettes
	'metasubpalettes' => array
	(
		'definePlain' => array('plain'),
		'addImage'    => array('singleSRC', 'alt', 'size', 'imagemargin', 'imageUrl', 'caption', 'floating'),
		'useImage'    => array('singleSRC', 'alt', 'size', 'caption'),
		'protected'   => array('groups')
	),

	// Fields
	'fields' => array
	(
		'invisible' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['invisible']
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['type'],
			'default'                 => 'text',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_newsletter_content', 'getNewsletterElements'),
			'reference'               => &$GLOBALS['TL_LANG']['NLE'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50')
		),
		'area' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['area'],
			'default'                 => 'body',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_newsletter_content', 'dcaGetNewsletterAreas'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['area'],
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
		),
		'headline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['headline'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'clr')
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['text'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'rte'=>'tinyNews', 'helpwizard'=>true),
			'explanation'             => 'insertTags'
		),
		'definePlain' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['definePlain'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'plain' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['plain'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'helpwizard'=>true),
			'explanation'             => 'insertTags'
		),
		'personalize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['personalize'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => array('anonymous', 'private'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content'],
			'eval'                    => array('tl_class'=>'long')
		),
		'addImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['addImage'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'mandatory'=>true, 'tl_class'=>'clr')
		),
		'alt' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['alt'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'long')
		),
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['size'],
			'exclude'                 => true,
			'inputType'               => 'imageSize',
			'options'                 => array('crop', 'proportional', 'box'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'imagemargin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['imagemargin'],
			'exclude'                 => true,
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'imageUrl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['imageUrl'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_avisota_newsletter_content', 'pagePicker')
			)
		),
		'caption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['caption'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50')
		),
		'floating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['floating'],
			'default'                 => 'above',
			'exclude'                 => true,
			'inputType'               => 'radioTable',
			'options'                 => array('above', 'left', 'right', 'below'),
			'eval'                    => array('cols'=>2),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
		),
		'listtype' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['listtype'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('ordered', 'unordered'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']
		),
		'listitems' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['listitems'],
			'exclude'                 => true,
			'inputType'               => 'listWizard',
			'eval'                    => array('allowHtml'=>true)
		),
		'tableitems' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['tableitems'],
			'exclude'                 => true,
			'inputType'               => 'tableWizard',
			'eval'                    => array('allowHtml'=>true, 'doNotSaveEmpty'=>true, 'style'=>'width:142px; height:66px;')
		),
		'summary' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['summary'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'thead' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['thead'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'tfoot' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['tfoot'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_avisota_newsletter_content', 'pagePicker')
			)
		),
		'linkTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['linkTitle'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50')
		),
		'embed' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['embed'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'long clr')
		),
		'multiSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['multiSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'mandatory'=>true)
		),
		'perRow' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['perRow'],
			'default'                 => 4,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
			'eval'                    => array('tl_class'=>'w50')
		),
		'sortBy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['sortBy'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('name_asc', 'name_desc', 'date_asc', 'date_desc', 'meta', 'random'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content'],
			'eval'                    => array('tl_class'=>'w50')
		),
		'galleryHtmlTpl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['galleryHtmlTpl'],
			'default'                 => 'nl_gallery_default_html',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_newsletter_content', 'getGalleryTemplates'),
			'eval'                    => array('tl_class'=>'w50')
		),
		'galleryPlainTpl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['galleryPlainTpl'],
			'default'                 => 'nl_gallery_default_plain',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_newsletter_content', 'getGalleryTemplates'),
			'eval'                    => array('tl_class'=>'w50')
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['protected'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true)
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['guests'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox'
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50')
		),
		'space' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['space'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'events' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['events'],
			'exclude'                 => true,
			'inputType'               => 'eventchooser',
			'eval'                    => array('mandatory'=>true)
		),
		'news' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['news'],
			'exclude'                 => true,
			'inputType'               => 'newschooser',
			'eval'                    => array('mandatory'=>true)
		),
		'articleAlias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['articleAlias'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_avisota_newsletter_content', 'getArticleAlias'),
			'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true),
			'wizard' => array
			(
				array('tl_avisota_newsletter_content', 'editArticleAlias')
			)
		)
	)
);

class tl_avisota_newsletter_content extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		$this->import('AvisotaContent', 'Content');
	}


	/**
	 * Check permissions to edit table tl_avisota_newsletter_content
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

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
				if (!$this->checkAccessToElement(CURRENT_ID, true))
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
				if (($this->Input->get('act') == 'cutAll' || $this->Input->get('act') == 'copyAll') && !$this->checkAccessToElement($this->Input->get('pid'), ($this->Input->get('mode') == 2)))
				{
					$this->redirect('contao/main.php?act=error');
				}

				$objCes = $this->Database->prepare("SELECT id FROM tl_avisota_newsletter_content WHERE pid=?")
										 ->execute(CURRENT_ID);

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objCes->fetchEach('id'));
				$this->Session->setData($session);
				break;

			case 'cut':
			case 'copy':
				// Check access to the parent element if a content element is moved
				if (!$this->checkAccessToElement($this->Input->get('pid'), ($this->Input->get('mode') == 2)))
				{
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			default:
				// Check access to the content element
				if (!$this->checkAccessToElement($this->Input->get('id')))
				{
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Check access to a particular content element
	 * @param integer
	 * @param array
	 * @param boolean
	 * @return boolean
	 */
	protected function checkAccessToElement($id, $blnIsPid=false)
	{
		if ($this->User->isAdmin)
		{
			return true;
		}

		if (!$blnIsPid)
		{
			$objContent = $this->Database
				->prepare("SELECT * FROM tl_avisota_newsletter_content WHERE id=?")
				->execute($id);
			if ($objContent->next())
			{
				$id = $objContent->pid;
			}
			else
			{
				$this->log('Invalid avisota newsletter content element ID ' . $id, 'tl_avisota_newsletter_content checkAccessToElement()', TL_ERROR);
				return false;
			}
		}

		$objNewsletter = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
			->execute($id);
		if ($objNewsletter->next())
		{
			$pid = $objNewsletter->pid;
		}
		else
		{
			$this->log('Invalid avisota newsletter ID ' . $id, 'tl_avisota_newsletter_content checkAccessToElement()', TL_ERROR);
			return false;
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

		// The page is not mounted
		if (!in_array($pid, $root))
		{
			$this->log('Not enough permissions to modify newsletter ID ' . $id, 'tl_avisota_newsletter_content checkAccessToElement()', TL_ERROR);
			return false;
		}

		return true;
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
	public function sendNewsletterButton($href, $label, $title, $icon, $attributes)
	{
		if (!($this->User->isAdmin || $this->User->hasAccess('send', 'avisota_newsletter_permissions')))
		{
			$label = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['view_only'][0];
			$title = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['view_only'][1];
		}
		return ' &#160; :: &#160; <a href="'.$this->addToUrl($href.'&amp;id='.$this->Input->get('id')).'" title="'.specialchars($title).'"'.$attributes.' class="header_send">'.$label.'</a> ';
	}


	/**
	 * Return the link picker wizard
	 * @param object
	 * @return string
	 */
	public function pagePicker(DataContainer $dc)
	{
		$strField = 'ctrl_' . $dc->field . (($this->Input->get('act') == 'editAll') ? '_' . $dc->id : '');
		return ' ' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top; cursor:pointer;" onclick="Backend.pickPage(\'' . $strField . '\')"');
	}


	/**
	 * Return all newsletter elements as array
	 * @return array
	 */
	public function getNewsletterElements()
	{
		$groups = array();

		foreach ($GLOBALS['TL_NLE'] as $k=>$v)
		{
			foreach (array_keys($v) as $kk)
			{
				$groups[$k][] = $kk;
			}
		}

		return $groups;
	}


	/**
	 * Return all gallery templates as array
	 * @param object
	 * @return array
	 */
	public function getGalleryTemplates(DataContainer $dc)
	{
		// Get the page ID
		$objArticle = $this->Database->prepare("SELECT pid FROM tl_article WHERE id=?")
									 ->limit(1)
									 ->execute($dc->activeRecord->pid);

		// Inherit the page settings
		$objPage = $this->getPageDetails($objArticle->pid);

		// Get the theme ID
		$objLayout = $this->Database->prepare("SELECT pid FROM tl_layout WHERE id=?")
									->limit(1)
									->execute($objPage->layout);

		// Return all gallery templates
		return $this->getTemplateGroup('nl_gallery_', $objLayout->pid);
	}


	/**
	 * Add the type of content element
	 * @param array
	 * @return string
	 */
	public function addElement($arrRow)
	{
		$key = $arrRow['invisible'] ? 'unpublished' : 'published';

		return '
<div class="cte_type ' . $key . '">' .
	(isset($GLOBALS['TL_LANG']['NLE'][$arrRow['type']][0]) ? $GLOBALS['TL_LANG']['NLE'][$arrRow['type']][0] : $arrRow['type']) .
	($arrRow['protected'] ? ' (' . $GLOBALS['TL_LANG']['MSC']['protected'] . ')' : ($arrRow['guests'] ? ' (' . $GLOBALS['TL_LANG']['MSC']['guests'] . ')' : '')) .
	($this->hasMultipleNewsletterAreas($arrRow) ? sprintf(' <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>', isset($GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['area'][$arrRow['area']]) ? $GLOBALS['TL_LANG']['tl_avisota_newsletter_content']['area'][$arrRow['area']] : $arrRow['area']) : '') .
'</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h64' : '') . ' block">
' . $this->Content->getNewsletterElement($arrRow['id']) . '
</div>' . "\n";
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

		$href .= '&amp;id='.$this->Input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
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

		$this->createInitialVersion('tl_avisota_newsletter_content', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_avisota_newsletter_content']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_avisota_newsletter_content']['fields']['invisible']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_avisota_newsletter_content SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_avisota_newsletter_content', $intId);
	}


	/**
	 * Check if there are more than the default 'body' area.
	 *
	 * @param DataContainer $dc
	 */
	public function hasMultipleNewsletterAreas($dc)
	{
		$arrAreas = $this->dcaGetNewsletterAreas($dc);
		return count($arrAreas)>1;
	}


	/**
	 * Get a list of areas from the parent category.
	 */
	public function dcaGetNewsletterAreas($dc)
	{
		$objCategory = $this->Database->prepare("SELECT c.* FROM tl_avisota_newsletter_category c INNER JOIN tl_avisota_newsletter n ON c.id=n.pid INNER JOIN tl_avisota_newsletter_content nle ON n.id=nle.pid WHERE nle.id=?")->execute(is_array($dc) ? $dc['id'] : (is_object($dc) ? $dc->id : $dc));
		if ($objCategory->next())
		{
			$arrAreas = array_filter(array_merge(array('body'), trimsplit(',', $objCategory->areas)));
		}
		else
		{
			$arrAreas = array('body');
		}
		return array_unique($arrAreas);
	}


	/**
	 * Update this data container.
	 *
	 * @param unknown_type $strName
	 */
	public function myLoadDataContainer($strName)
	{
		if ($strName == 'tl_avisota_newsletter_content')
		{
			if ($this->Input->get('table') == 'tl_avisota_newsletter_content' && $this->Input->get('act') == 'edit')
			{
				if (!$this->hasMultipleNewsletterAreas($this->Input->get('id')))
				{
					foreach ($GLOBALS['TL_DCA']['tl_avisota_newsletter_content']['palettes'] as $k=>$v)
					{
						$GLOBALS['TL_DCA']['tl_avisota_newsletter_content']['palettes'][$k] = str_replace(',area', '', $v);
					}
					$GLOBALS['TL_DCA']['tl_avisota_newsletter_content']['fields']['area']['filter'] = false;
				}
			}
		}
	}


	/**
	 * Get all articles and return them as array (article alias)
	 * @param object
	 * @return array
	 */
	public function getArticleAlias(DataContainer $dc)
	{
		$arrPids = array();
		$arrAlias = array();

		if (!$this->User->isAdmin)
		{
			foreach ($this->User->pagemounts as $id)
			{
				$arrPids[] = $id;
				$arrPids = array_merge($arrPids, $this->getChildRecords($id, 'tl_page', true));
			}

			if (empty($arrPids))
			{
				return $arrAlias;
			}

			$objAlias = $this->Database->execute("SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(". implode(',', array_map('intval', array_unique($arrPids))) .") ORDER BY parent, a.sorting");
		}
		else
		{
			$objAlias = $this->Database->execute("SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting");
		}

		if ($objAlias->numRows)
		{
			$this->loadLanguageFile('tl_article');

			while ($objAlias->next())
			{
				$arrAlias[$objAlias->parent][$objAlias->id] = $objAlias->title . ' (' . (strlen($GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn]) ? $GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] : $objAlias->inColumn) . ', ID ' . $objAlias->id . ')';
			}
		}

		return $arrAlias;
	}


	/**
	 * Return the edit article alias wizard
	 * @param object
	 * @return string
	 */
	public function editArticleAlias(DataContainer $dc)
	{
		return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=article&amp;table=tl_article&amp;act=edit&amp;id=' . $dc->value . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value).'" style="padding-left:3px;">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top;"') . '</a>';
	}
}

// add hook
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('tl_avisota_newsletter_content', 'myLoadDataContainer');

?>