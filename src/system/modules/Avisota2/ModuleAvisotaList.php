<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleAvisotaList
 *
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class ModuleAvisotaList extends Module
{
	/**
	 * @var Database
	 */
	protected $Database;

	/**
	 * @var Input
	 */
	protected $Input;

	/**
	 * @var AvisotaBase
	 */
	protected $Base;

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_list';


	/**
	 * Construct the content element
	 */
	public function __construct(Database_Result $objModule)
	{
		parent::__construct($objModule);
		$this->import('DomainLink');
		$this->import('FrontendUser', 'User');
		$this->import('AvisotaBase', 'Base');
		$this->loadLanguageFile('avisota');
	}


	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate           = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### Avisota Newsletter List ###';
			return $objTemplate->parse();
		}

		$this->avisota_categories = array_filter(
			array_map(
				'intval',
				deserialize($this->avisota_categories, true)
			)
		);
		if (!count($this->avisota_categories)) {
			$this->avisota_categories = array(0);
		}

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	public function compile()
	{
		$varId = $this->Input->get('items');

		$intPage = $this->Input->get('page');
		if (!$intPage) {
			$intPage = 0;
		}
		$intLimit  = $this->perPage;
		$intOffset = $intPage * $intLimit;

		$objNewsletter = $this->Database
			->prepare(
			"SELECT * FROM tl_avisota_newsletter WHERE sendOn > 0 AND pid IN (" . implode(
				',',
				$this->avisota_categories
			) . ") ORDER BY sendOn DESC"
		);
		if ($intLimit > 0) {
			$objNewsletter->limit($intLimit, $intOffset);
		}
		$objNewsletter = $objNewsletter->execute();

		$objViewPage        = $this->jumpTo ? $this->getPageDetails($this->avisota_view_page) : false;
		$arrViewOnlineCache = array();

		$arrNewsletters = array();
		while ($objNewsletter->next()) {
			$arrNewsletter = $objNewsletter->row();

			$strParams = '/items/' . ($GLOBALS['TL_CONFIG']['disableAlias'] ? $arrNewsletter['id']
				: $arrNewsletter['alias']);

			if ($objViewPage) {
				$arrNewsletter['href'] = $this->generateFrontendUrl($objViewPage->row(), $strParams);
			}
			else {
				if (!isset($arrViewOnlineCache[$objNewsletter->pid])) {
					$objCategory = $this->Database
						->prepare("SELECT * FROM tl_avisota_newsletter_category WHERE id=?")
						->execute($objNewsletter->pid);

					if ($objCategory->next()) {
						$arrViewOnlineCache[$objNewsletter->pid] = $this->Base->getViewOnlinePage($objCategory);
					}
					else {
						$arrViewOnlineCache[$objNewsletter->pid] = false;
					}
				}
				if ($arrViewOnlineCache[$objNewsletter->pid]) {
					$arrNewsletter['href'] = $this->generateFrontendUrl(
						$arrViewOnlineCache[$objNewsletter->pid]->row(),
						$strParams
					);
				}
				else {
					$arrNewsletter['href'] = '';
				}
			}

			$arrNewsletters[] = $arrNewsletter;
		}

		$objTemplate              = new FrontendTemplate($this->avisota_list_template);
		$objTemplate->newsletters = $arrNewsletters;
		$this->Template->list     = $objTemplate->parse();

		$objNewsletter = $this->Database
			->prepare(
			"SELECT COUNT(id) as `count` FROM tl_avisota_newsletter WHERE sendOn > 0 AND pid IN (" . implode(
				',',
				$this->avisota_categories
			) . ")"
		)
			->execute();

		$this->Template->limit = $intLimit;
		$this->Template->total = $objNewsletter->next() ? $objNewsletter->count : 0;
	}
}
