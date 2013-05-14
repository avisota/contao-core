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


/**
 * Class PageAvisotaNewsletter
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class PageAvisotaNewsletter extends Frontend
{
	/**
	 * @var AvisotaNewsletterContent
	 */
	protected $Content;

	/**
	 * Generate a newsletter
	 *
	 * @param object
	 */
	public function generate(Database_Result $pageResultSet)
	{
		// Define the static URL constants
		define('TL_FILES_URL', ($pageResultSet->staticFiles != '' && !$GLOBALS['TL_CONFIG']['debugMode'])
			? $pageResultSet->staticFiles . TL_PATH . '/' : '');
		define('TL_SCRIPT_URL', ($pageResultSet->staticSystem != '' && !$GLOBALS['TL_CONFIG']['debugMode'])
			? $pageResultSet->staticSystem . TL_PATH . '/' : '');
		define('TL_PLUGINS_URL', ($pageResultSet->staticPlugins != '' && !$GLOBALS['TL_CONFIG']['debugMode'])
			? $pageResultSet->staticPlugins . TL_PATH . '/' : '');

		$this->import('AvisotaNewsletterContent', 'Content');

		// force all URLs absolute
		$GLOBALS['TL_CONFIG']['forceAbsoluteDomainLink'] = true;

		$newsletterId = $this->Input->get('item') ? $this->Input->get('item') : $this->Input->get('items');
		$newsletterContent = $this->Content->generateOnlineNewsletter($newsletterId);

		if ($newsletterContent) {
			header('Content-Type: text/html; charset=utf-8');
			echo $newsletterContent;
			exit;
		}

		$this->redirect(
			$this->generateFrontendUrl(
				$this
					->getPageDetails($pageResultSet->jumpBack ? $pageResultSet->jumpBack : $pageResultSet->pid)
					->row()
			)
		);
	}
}
