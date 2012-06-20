<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleAvisotaReader
 *
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class ModuleAvisotaReader extends Module
{
	/**
	 * @var AvisotaNewsletterContent
	 */
	protected $AvisotaNewsletterContent;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_reader';


	/**
	 * Construct the content element
	 */
	public function __construct(Database_Result $objModule)
	{
		parent::__construct($objModule);
		$this->import('DomainLink');
		$this->import('FrontendUser', 'User');
		$this->import('AvisotaNewsletterContent');
		$this->loadLanguageFile('avisota');
	}


	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### Avisota Newsletter Reader ###';
			return $objTemplate->parse();
		}

		$this->avisota_categories = array_filter(array_map('intval',
			deserialize($this->avisota_categories, true)));
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

		$objNewsletter = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter WHERE (id=? OR alias=?) AND pid IN (" . implode(',', $this->avisota_categories) . ")")
			->execute($varId, $varId);

		if ($objNewsletter->next())
		{
			$objCategory = $this->Database
				->prepare("SELECT * FROM tl_avisota_newsletter_category WHERE id=?")
				->execute($objNewsletter->pid);
			if ($objCategory->next())
			{
				$objNewsletter->template_html = $this->avisota_reader_template;

				$this->Template->newsletter = $objNewsletter->row();
				$this->Template->html = $this->AvisotaNewsletterContent->generateHtml($objNewsletter, $objCategory, false);
			}
		}
	}
}
