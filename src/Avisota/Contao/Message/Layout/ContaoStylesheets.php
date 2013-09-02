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
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Message\Layout;

use Avisota\Contao\Event\CollectStylesheetsEvent;
use Avisota\Contao\Event\CollectThemeStylesheetsEvent;
use Avisota\Contao\Event\ResolveStylesheetEvent;

class ContaoStylesheets
{
	static public function collectStylesheets(CollectStylesheetsEvent $event)
	{
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$database = \Database::getInstance();
		$theme = $database->query("SELECT * FROM tl_theme ORDER BY name");

		$stylesheets = $event->getStylesheets();

		while ($theme->next()) {
			$stylesheet = $database
				->prepare("SELECT * FROM tl_style_sheet WHERE pid=?")
				->execute($theme->id);
			while ($stylesheet->next()) {
				$stylesheets['contao:' . $stylesheet->name] = '<span style="color:#A6A6A6;display:inline">' . $theme->name . ': </span>' . $stylesheet->name . '<span style="color:#A6A6A6;display:inline">.css</span>';
			}

			$eventDispatcher->dispatch('avisota-layout-collect-theme-stylesheets', new CollectThemeStylesheetsEvent($theme->row(), $stylesheets));
		}
	}

	static public function resolveStylesheet(ResolveStylesheetEvent $event)
	{
		$stylesheet = $event->getStylesheet();

		if (preg_match('#^contao:(.*)$#', $stylesheet, $matches)) {
			if (version_compare(VERSION, '3', '>=')) {
				$stylesheet = 'assets/css/' . $matches[1] . '.css';
			}
			else {
				$stylesheet = 'system/scripts/' . $matches[1] . '.css';
			}
			$event->setStylesheet($stylesheet);
		}
	}
}
