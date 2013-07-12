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

use Avisota\Contao\Entity\Message;
use Contao\Doctrine\ORM\EntityHelper;

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
	public function getBoilerplateMessages($dc)
	{
		$entityManager = EntityHelper::getEntityManager();
		$queryBuilder = $entityManager->createQueryBuilder();

		/** @var Message[] $messages */
		$messages = $queryBuilder
			->select('m')
			->from('Avisota\Contao:Message', 'm')
			->innerJoin('Avisota\Contao:MessageCategory', 'c', 'c.id=m.category')
			->where('c.boilerplates=:boilerplate')
			->orderBy('c.title', 'ASC')
			->addOrderBy('m.subject', 'ASC')
			->setParameter(':boilerplate', true)
			->getQuery()
			->getResult();

		$options = array();

		foreach ($messages as $message) {
			$options[$message->getCategory()->getTitle()][$message->getId()] = $message->getSubject();
		}

		return $options;
	}
}
