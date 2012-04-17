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
 * Class NewsletterText
 *
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterText extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_text_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_text_plain';

	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$this->import('String');

		switch ($mode)
		{
		case NL_HTML:
			// Clean RTE output
			$this->Template->text = str_ireplace
			(
				array('<u>', '</u>', '</p>', '<br /><br />', ' target="_self"'),
				array('<span style="text-decoration:underline;">', '</span>', "</p>\n", "<br /><br />\n", ''),
				$this->String->encodeEmail($this->text)
			);
			break;

		case NL_PLAIN:
			if ($this->plain)
			{
				$this->Template->text = $this->plain;
			}
			else
			{
				$this->Template->text = $this->getPlainFromHTML($this->text);
			}
		}

		$this->Template->addImage = false;

		// Add image
		if ($this->addImage && strlen($this->singleSRC) && is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			$this->addImageToTemplate($this->Template, $this->arrData);

			$this->Template->src = $this->extendURL($this->Template->src);
			if ($this->Template->href)
			{
				$this->Template->href = $this->extendURL($this->Template->href);
			}
		}
	}
}
