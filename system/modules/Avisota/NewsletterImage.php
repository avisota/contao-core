<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
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
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Class NewsletterImage
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterImage extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_image_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_image_plain';
	
	
	/**
	 * Parse the html template
	 * @return string
	 */
	public function generateHTML()
	{
		if (!strlen($this->singleSRC) || !is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			return '';
		}
		
		return parent::generateHTML();
	}
	
	
	/**
	 * Parse the plain text template
	 * @return string
	 */
	public function generatePlain()
	{
		if (!strlen($this->singleSRC) || !is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			return '';
		}
		
		return parent::generatePlain();
	}
	
	
	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$this->addImageToTemplate($this->Template, $this->arrData);
		
		$this->Template->src = $this->extendURL($this->Template->src);
		if ($this->Template->href)
		{
			$this->Template->href = $this->extendURL($this->Template->href);
		}
	}
}

?>