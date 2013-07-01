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
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 */
class AvisotaBackendStylepickerDCA extends tl_stylepicker4ward
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	/**
	 * @param mixed         $val
	 * @param DataContainer $dc
	 *
	 * @return string
	 */
	public function saveAvisotaNewsletterCEs($val, $dc)
	{
		// delete all records for this table/pid
		$this->truncateTargets($dc->id, 'orm_avisota_newsletter_content');

		$vals = unserialize($val);
		if (is_array($vals)) {
			// get sections
			$secs = $this->Input->post('_AvisotaNewsletterCE_Row');
			if (!is_array($secs) || !count($secs)) {
				return '';
			}

			// save CEs foreach section
			foreach ($secs as $sec) {
				foreach ($vals as $val) {
					$this->saveTarget($dc->id, 'orm_avisota_newsletter_content', 'cssID', $sec, $val);
				}
			}
		}
		return null;
	}

	/**
	 * @param mixed         $val
	 * @param DataContainer $dc
	 *
	 * @return string
	 */
	public function loadAvisotaNewsletterCEs($val, $dc)
	{
		$return  = array();
		$targets = $this->Database
			->prepare('SELECT DISTINCT(cond) FROM tl_stylepicker4ward_target WHERE pid=? AND tbl=?')
			->execute($dc->id, 'orm_avisota_newsletter_content');
		while ($targets->next()) {
			$return[] = $targets->cond;
		}
		return serialize($return);
	}

	public function loadAvisotaNewsletterCE_Rows($val, $dc)
	{
		$return  = array();
		$targets = $this->Database
			->prepare('SELECT DISTINCT(sec) FROM tl_stylepicker4ward_target WHERE pid=? AND tbl=?')
			->execute($dc->id, 'orm_avisota_newsletter_content');
		while ($targets->next()) {
			$return[] = $targets->sec;
		}
		return serialize($return);
	}

	/**
	 * Load newsletter content elements from $GLOBALS['TL_NLE']
	 *
	 * @return array
	 */
	public function getAvisotaNewsletterContentElements()
	{
		$contentElements = array();
		foreach ($GLOBALS['TL_NLE'] as $key => $arr) {
			foreach ($arr as $elementName => $val) {
				array_push($contentElements, $elementName);
			}
		}

		return $contentElements;
	}

	/**
	 * Get newsletter sections
	 *
	 * @return array
	 */
	public function getAvisotaNewsletterSections()
	{
		$ret = array('body');

		$category = $this->Database
			->query('SELECT * FROM orm_avisota_newsletter_category WHERE areas!=\'\'');
		while ($category->next()) {
			$ret = array_merge(
				$ret,
				trimsplit(',', $category->areas)
			);
		}

		return array_unique($ret);
	}
}