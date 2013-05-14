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
 * Class AvisotaFrontend
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class AvisotaFrontend extends Frontend
{
	/**
	 * Find a particular template file and return its path
	 *
	 * @author     Leo Feyer <http://www.contao.org>
	 * @see        Controll::getTemplate in Contao OpenSource CMS
	 *
	 * @param string
	 * @param string
	 *
	 * @return string
	 * @throws Exception
	 */
	public function getTemplate($template)
	{
		return AvisotaBase::getInstance()
			->getTemplate($template);
	}

	protected function getTemplateGroup($prefix, $themeId = 0)
	{
		return AvisotaBase::getInstance()
			->getTemplateGroup($prefix, $themeId);
	}
}
