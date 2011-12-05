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
 * Class PageAvisotaNewsletter
 *
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class PageAvisotaNewsletter extends Frontend
{
	/**
	 * @var AvisotaContent
	 */
	protected $Content;

	/**
	 * Generate a newsletter
	 * @param object
	 */
	public function generate(Database_Result $objPage)
	{
		$this->import('AvisotaContent', 'Content');

		// force all URLs absolute
		$GLOBALS['TL_CONFIG']['forceAbsoluteDomainLink'] = true;

		$strId = $this->Input->get('item') ? $this->Input->get('item') : $this->Input->get('items');
		$strNewsletter = $this->Content->generateOnlineNewsletter($strId);

		if ($strNewsletter)
		{
			header('Content-Type: text/html; charset=utf-8');
			echo $strNewsletter;
			exit;
		}

		$this->redirect($this->generateFrontendUrl($this->getPageDetails($objPage->jumpBack ? $objPage->jumpBack : $objPage->pid)->row()));
	}
}
?>