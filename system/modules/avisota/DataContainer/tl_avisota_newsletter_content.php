<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


class orm_avisota_message_content extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		$this->import('AvisotaNewsletterContent', 'Content');
	}


	/**
	 * Check permissions to edit table orm_avisota_message_content
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// Check the current action
		switch ($this->Input->get('act')) {
			case 'paste':
				// Allow
				break;

			case '': // empty
			case 'create':
			case 'select':
				// Check access to the article
				if (!$this->checkAccessToElement(CURRENT_ID, true)) {
					$this->log(
						'Access to element ID ' . CURRENT_ID . ' denied!',
						'orm_avisota_message_content',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				// Check access to the parent element if a content element is moved
				if (($this->Input->get('act') == 'cutAll' || $this->Input->get(
					'act'
				) == 'copyAll') && !$this->checkAccessToElement(
					$this->Input->get('pid'),
					($this->Input->get('mode') == 2)
				)
				) {
					$this->log(
						'Access to element ID ' . $this->Input->get('pid') . ' denied!',
						'orm_avisota_message_content',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}

				$contentElement = $this->Database
					->prepare("SELECT id FROM orm_avisota_message_content WHERE pid=?")
					->execute(CURRENT_ID);

				$session                   = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $contentElement->fetchEach('id'));
				$this->Session->setData($session);
				break;

			case 'cut':
			case 'copy':
				// Check access to the parent element if a content element is moved
				if (!$this->checkAccessToElement($this->Input->get('pid'), ($this->Input->get('mode') == 2))) {
					$this->log(
						'Access to element ID ' . $this->Input->get('pid') . ' denied!',
						'orm_avisota_message_content',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
			// NO BREAK STATEMENT HERE

			default:
				// Check access to the content element
				if (!$this->checkAccessToElement($this->Input->get('id'))) {
					$this->log(
						'Access to element ID ' . $this->Input->get('id') . ' denied!',
						'orm_avisota_message_content',
						TL_ERROR
					);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Check access to a particular content element
	 *
	 * @param integer
	 * @param array
	 * @param boolean
	 *
	 * @return boolean
	 */
	protected function checkAccessToElement($id, $isPid = false)
	{
		if ($this->User->isAdmin) {
			return true;
		}

		if (!$isPid) {
			$content = $this->Database
				->prepare("SELECT * FROM orm_avisota_message_content WHERE id=?")
				->execute($id);
			if ($content->next()) {
				$id = $content->pid;
			}
			else {
				$this->log(
					'Invalid avisota newsletter content element ID ' . $id,
					'orm_avisota_message_content checkAccessToElement()',
					TL_ERROR
				);
				return false;
			}
		}

		$newsletter = $this->Database
			->prepare("SELECT * FROM orm_avisota_message WHERE id=?")
			->execute($id);
		if ($newsletter->next()) {
			$pid = $newsletter->pid;
		}
		else {
			$this->log(
				'Invalid avisota newsletter ID ' . $id,
				'orm_avisota_message_content checkAccessToElement()',
				TL_ERROR
			);
			return false;
		}

		// Set root IDs
		if (!is_array($this->User->avisota_newsletter_categories) || count(
			$this->User->avisota_newsletter_categories
		) < 1
		) {
			$root = array(0);
		}
		else {
			$root = $this->User->avisota_newsletter_categories;
		}

		// The page is not mounted
		if (!in_array($pid, $root)) {
			$this->log(
				'Not enough permissions to modify newsletter ID ' . $id,
				'orm_avisota_message_content checkAccessToElement()',
				TL_ERROR
			);
			return false;
		}

		return true;
	}


	/**
	 * Return the send button
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
	public function sendNewsletterButton($href, $label, $title, $icon, $attributes)
	{
		if (!($this->User->isAdmin || $this->User->hasAccess('send', 'avisota_newsletter_permissions'))) {
			$label = $GLOBALS['TL_LANG']['orm_avisota_message']['view_only'][0];
			$title = $GLOBALS['TL_LANG']['orm_avisota_message']['view_only'][1];
		}
		return ' &#160; :: &#160; <a href="' . $this->addToUrl(
			$href . '&amp;id=' . $this->Input->get('id')
		) . '" title="' . specialchars($title) . '"' . $attributes . ' class="header_send">' . $label . '</a> ';
	}


	/**
	 * Return the link picker wizard
	 *
	 * @param object
	 *
	 * @return string
	 */
	public function pagePicker(DataContainer $dc)
	{
		$fieldId = 'ctrl_' . $dc->field . (($this->Input->get('act') == 'editAll') ? '_' . $dc->id : '');
		return ' ' . $this->generateImage(
			'pickpage.gif',
			$GLOBALS['TL_LANG']['MSC']['pagepicker'],
			'style="vertical-align:top; cursor:pointer;" onclick="Backend.pickPage(\'' . $fieldId . '\')"'
		);
	}


	/**
	 * Return all newsletter elements as array
	 *
	 * @return array
	 */
	public function getNewsletterElements()
	{
		$groups = array();

		foreach ($GLOBALS['TL_MCE'] as $k => $v) {
			foreach (array_keys($v) as $kk) {
				$groups[$k][] = $kk;
			}
		}

		return $groups;
	}


	/**
	 * Return all gallery templates as array
	 *
	 * @param object
	 *
	 * @return array
	 */
	public function getGalleryTemplates(DataContainer $dc)
	{
		// Get the page ID
		$article = $this->Database
			->prepare("SELECT pid FROM tl_article WHERE id=?")
			->limit(1)
			->execute($dc->activeRecord->pid);

		// Inherit the page settings
		$page = $this->getPageDetails($article->pid);

		// Get the theme ID
		$layout = $this->Database
			->prepare("SELECT pid FROM tl_layout WHERE id=?")
			->limit(1)
			->execute($page->layout);

		// Return all gallery templates
		return $this->getTemplateGroup('nl_gallery_', $layout->pid);
	}


	/**
	 * Add the type of content element
	 *
	 * @param array
	 *
	 * @return string
	 */
	public function addElement($contentData)
	{
		$key = $contentData['invisible'] ? 'unpublished' : 'published';

		return '
<div class="cte_type ' . $key . '">' .
			(isset($GLOBALS['TL_LANG']['MCE'][$contentData['type']][0]) ? $GLOBALS['TL_LANG']['MCE'][$contentData['type']][0]
				: $contentData['type']) .
			($contentData['protected']
				? ' (' . $GLOBALS['TL_LANG']['MSC']['protected'] . ')'
				: ($contentData['guests']
					? ' (' . $GLOBALS['TL_LANG']['MSC']['guests'] . ')' : '')) .
			($this->hasMultipleNewsletterAreas($contentData) ? sprintf(
				' <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
				isset($GLOBALS['TL_LANG']['orm_avisota_message_content']['cell'][$contentData['cell']])
					? $GLOBALS['TL_LANG']['orm_avisota_message_content']['cell'][$contentData['cell']] : $contentData['cell']
			) : '') .
			'</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h64' : '') . ' block">
<table>' . $this->Content->getNewsletterElement($contentData['id']) . '</table>
</div>' . "\n";
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

		$href .= '&amp;id=' . $this->Input->get('id') . '&amp;tid=' . $row['id'] . '&amp;state=' . $row['invisible'];

		if ($row['invisible']) {
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
	public function toggleVisibility($contentId, $visible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $contentId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		$this->createInitialVersion('orm_avisota_message_content', $contentId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['orm_avisota_message_content']['fields']['invisible']['save_callback'])) {
			foreach (
				$GLOBALS['TL_DCA']['orm_avisota_message_content']['fields']['invisible']['save_callback'] as
				$callback
			) {
				$this->import($callback[0]);
				$visible = $this->$callback[0]->$callback[1]($visible, $this);
			}
		}

		// Update the database
		$this->Database
			->prepare(
			"UPDATE orm_avisota_message_content SET tstamp=" . time() . ", invisible='" . ($visible ? ''
				: 1) . "' WHERE id=?"
		)
			->execute($contentId);

		$this->createNewVersion('orm_avisota_message_content', $contentId);
	}


	/**
	 * Check if there are more than the default 'body' cell.
	 *
	 * @param DataContainer $dc
	 */
	public function hasMultipleNewsletterAreas($dc)
	{
		$areas = $this->dcaGetNewsletterAreas($dc);
		return count($areas) > 1;
	}


	/**
	 * Get a list of areas from the parent category.
	 */
	public function dcaGetNewsletterAreas($dc)
	{
		$category = $this->Database
			->prepare(
			"SELECT c.* FROM orm_avisota_message_category c INNER JOIN orm_avisota_message n ON c.id=n.pid INNER JOIN orm_avisota_message_content nle ON n.id=nle.pid WHERE nle.id=?"
		)
			->execute(is_array($dc) ? $dc['id'] : (is_object($dc) ? $dc->id : $dc));
		if ($category->next()) {
			$areas = array_filter(array_merge(array('body'), trimsplit(',', $category->areas)));
		}
		else {
			$areas = array('body');
		}
		return array_unique($areas);
	}

	/**
	 * Get all articles and return them as array (article alias)
	 *
	 * @param object
	 *
	 * @return array
	 */
	public function getArticleAlias(DataContainer $dc)
	{
		$pids  = array();
		$aliases = array();

		if (!$this->User->isAdmin) {
			foreach ($this->User->pagemounts as $id) {
				$pids[] = $id;
				$pids   = array_merge($pids, $this->getChildRecords($id, 'tl_page', true));
			}

			if (empty($pids)) {
				return $aliases;
			}

			$alias = $this->Database->execute(
				"SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(" . implode(
					',',
					array_map('intval', array_unique($pids))
				) . ") ORDER BY parent, a.sorting"
			);
		}
		else {
			$alias = $this->Database->execute(
				"SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting"
			);
		}

		if ($alias->numRows) {
			$this->loadLanguageFile('tl_article');

			while ($alias->next()) {
				$aliases[$alias->parent][$alias->id] = $alias->title . ' (' . (strlen(
					$GLOBALS['TL_LANG']['tl_article'][$alias->inColumn]
				) ? $GLOBALS['TL_LANG']['tl_article'][$alias->inColumn]
					: $alias->inColumn) . ', ID ' . $alias->id . ')';
			}
		}

		return $aliases;
	}


	/**
	 * Return the edit article alias wizard
	 *
	 * @param object
	 *
	 * @return string
	 */
	public function editArticleAlias(DataContainer $dc)
	{
		return ($dc->value < 1)
			? ''
			: ' <a href="contao/main.php?do=article&amp;table=tl_article&amp;act=edit&amp;id=' . $dc->value . '" title="' . sprintf(
				specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]),
				$dc->value
			) . '" style="padding-left:3px;">' . $this->generateImage(
				'alias.gif',
				$GLOBALS['TL_LANG']['tl_content']['editalias'][0],
				'style="vertical-align:top;"'
			) . '</a>';
	}
}
