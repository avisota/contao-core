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

namespace Avisota\Contao\DataContainer;

class Settings extends \Backend
{
	public function onload_callback()
	{
		if (!is_dir(TL_ROOT . '/system/modules/avisota/highstock')
			|| !is_file(TL_ROOT . '/system/modules/avisota/highstock/js/highstock.js')
		) {
			$GLOBALS['TL_DCA']['tl_avisota_settings']['fields']['avisota_chart_highstock_confirm']['input_field_callback'] = array(
				'tl_avisota_settings',
				'renderMissingHighstockField'
			);
		}
	}

	/**
	 * @param \DataContainer $dc
	 * @param $label
	 *
	 * @return mixed
	 */
	public function renderMissingHighstockField($dc, $label)
	{
		return $GLOBALS['TL_LANG']['tl_avisota_settings']['missing_highstock'];
	}

	/**
	 * @param \DataContainer $dc
	 *
	 * @return array
	 */
	public function getBoilerplateNewsletters($dc)
	{
		$database = \Database::getInstance();

		$resultSet = $database->query(
			'SELECT c.title AS category, n.*
			 FROM orm_avisota_mailing n
			 INNER JOIN orm_avisota_mailing_category c
			 ON c.id = n.pid
			 WHERE c.boilerplates = \'1\'
			 ORDER BY c.title, n.subject'
		);

		$options = array();

		while ($resultSet->next()) {
			$options[$resultSet->category][$resultSet->id] = $resultSet->subject;
		}

		return $options;
	}
}
