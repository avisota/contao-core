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


class tl_page_avisota extends tl_page
{
	public function alterDataContainer($name)
	{
		if ($name == 'tl_page') {
			$GLOBALS['TL_DCA']['tl_page']['config']['onsubmit_callback'][]                = array(
				'tl_page_avisota',
				'onSubmit'
			);
			$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['paste_button_callback']     = array(
				'tl_page_avisota',
				'pastePage'
			);
			$GLOBALS['TL_DCA']['tl_page']['list']['label']['label_callback']              = array(
				'tl_page_avisota',
				'addIcon'
			);
			$GLOBALS['TL_DCA']['tl_page']['fields']['sitemap']['save_callback'][]         = array(
				'tl_page_avisota',
				'sitemapCallback'
			);
			$GLOBALS['TL_DCA']['tl_page']['fields']['hide']['save_callback'][]            = array(
				'tl_page_avisota',
				'hideCallback'
			);
			$GLOBALS['TL_DCA']['tl_page']['fields']['menu_visibility']['save_callback'][] = array(
				'tl_page_avisota',
				'sitemapCallback'
			);
		}
	}

	public function sitemapCallback($value, DataContainer $dc)
	{
		if (!$dc->activeRecord) {
			$page = \Database::getInstance()
				->prepare("SELECT * FROM tl_page WHERE id=?")
				->execute($dc->id);
			if ($page->next() && $page->type == 'avisota') {
				return 'map_never';
			}
		}
		else if ($dc->activeRecord->type == 'avisota') {
			return 'map_never';
		}
		return $value;
	}

	public function hideCallback($value, DataContainer $dc)
	{
		if (!$dc->activeRecord) {
			$page = \Database::getInstance()
				->prepare("SELECT * FROM tl_page WHERE id=?")
				->execute($dc->id);
			if ($page->next() && $page->type == 'avisota') {
				return '1';
			}
		}
		else if ($dc->activeRecord->type == 'avisota') {
			return '1';
		}
		return $value;
	}

	public function onSubmit(DataContainer $dc)
	{
		if ($dc->activeRecord->type == 'avisota') {
			// note: menu_visibility is a xNavigation field, this is a quick hack
			\Database::getInstance()
				->prepare(
				"UPDATE tl_page
					SET
						sitemap='map_never',
						hide=1
						" . (\Database::getInstance()->fieldExists('menu_visibility', 'tl_page')
					? ", menu_visibility='map_never'" : "") . "
					WHERE id=?"
			)
				->execute($dc->id);
		}
	}


	public function pastePage(DataContainer $dc, $row, $table, $cr, $clipboardData = false)
	{
		if ($row['type'] == 'avisota') {
			$disablePA = false;

			// Disable all buttons if there is a circular reference
			if ($clipboardData !== false && ($clipboardData['mode'] == 'cut' && ($cr == 1 || $clipboardData['id'] == $row['id']) || $clipboardData['mode'] == 'cutAll' && ($cr == 1 || in_array(
				$row['id'],
				$clipboardData['id']
			)))
			) {
				$disablePA = true;
			}

			// Check permissions if the user is not an administrator
			if (!$this->User->isAdmin) {
				$page = \Database::getInstance()
					->prepare("SELECT * FROM " . $table . " WHERE id=?")
					->limit(1)
					->execute($row['pid']);

				// Disable "paste after" button if there is no permission 2 for the parent page
				if (!$disablePA && $page->numRows) {
					if (!$this->User->isAllowed(2, $page->row())) {
						$disablePA = true;
					}
				}

				// Disable "paste after" button if the parent page is a root page and the user is not an administrator
				if (!$disablePA && ($row['pid'] < 1 || in_array($row['id'], $dc->rootIds))) {
					$disablePA = true;
				}
			}

			// Return the buttons
			$imagePasteAfter = $this->generateImage(
				'pasteafter.gif',
				sprintf($GLOBALS['TL_LANG'][$table]['pasteafter'][1], $row['id']),
				'class="blink"'
			);

			if ($row['id'] > 0) {
				return $disablePA
					? $this->generateImage('pasteafter_.gif', '', 'class="blink"') . ' '
					: '<a href="' . $this->addToUrl(
						'act=' . $clipboardData['mode'] . '&amp;mode=1&amp;pid=' . $row['id'] . (!is_array(
							$clipboardData['id']
						) ? '&amp;id=' . $clipboardData['id'] : '')
					) . '" title="' . specialchars(
						sprintf($GLOBALS['TL_LANG'][$table]['pasteafter'][1], $row['id'])
					) . '" onclick="Backend.getScrollOffset();">' . $imagePasteAfter . '</a> ' . $this->generateImage(
						'pasteinto_.gif',
						'',
						'class="blink"'
					);
			}

			return '';
		}
		return parent::pastePage($dc, $row, $table, $cr, $clipboardData);
	}


	public function addIcon(
		$row,
		$label,
		DataContainer $dc = null,
		$imageAttribute = '',
		$returnImage = false,
		$isProtected = false
	) {
		if ($row['type'] == 'avisota') {
			$sub   = 0;
			$image = 'system/modules/avisota/html/page.png';

			// Page not published or not active
			if ((!$row['published'] || $row['start'] && $row['start'] > time() || $row['stop'] && $row['stop'] < time(
			))
			) {
				$sub += 1;
			}

			// Page protected
			if ($row['protected'] && !in_array($row['type'], array('root', 'error_403', 'error_404'))) {
				$sub += 2;
			}

			// Get image name
			if ($sub > 0) {
				$image = 'system/modules/avisota/html/page_' . $sub . '.png';
			}

			// Return the image only
			if ($returnImage) {
				return $this->generateImage($image, '', $imageAttribute);
			}

			// Return image
			return $this->generateImage($image, '', $imageAttribute) . ' ' . $label;
		}
		return parent::addIcon($row, $label, $dc, $imageAttribute, $returnImage);
	}
}

// add hook
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('tl_page_avisota', 'alterDataContainer');
