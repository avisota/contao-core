<?php

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
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('tl_stylepicker4ward_avisota_callback', 'hookLoadDataContainer');

class tl_stylepicker4ward_avisota_callback
{
	public function hookLoadDataContainer($strName)
	{
		if ($strName == 'tl_stylepicker4ward') {
			$GLOBALS['TL_DCA']['tl_stylepicker4ward']['palettes']['default'] .= ';{Avisota_legend},_AvisotaNewsletterCEs,_AvisotaNewsletterCE_Row';
			$GLOBALS['TL_DCA']['tl_stylepicker4ward']['fields']['_AvisotaNewsletterCEs']    = array
			(
				'label'            => &$GLOBALS['TL_LANG']['tl_stylepicker4ward']['_AvisotaNewsletterCEs'],
				'inputType'        => 'checkbox',
				'options_callback' => array('AvisotaBackendStylepickerDCA', 'getAvisotaNewsletterContentElements'),
				'load_callback'    => array(array('AvisotaBackendStylepickerDCA', 'loadAvisotaNewsletterCEs')),
				'save_callback'    => array(array('AvisotaBackendStylepickerDCA', 'saveAvisotaNewsletterCEs')),
				'reference'        => &$GLOBALS['TL_LANG']['NLE'],
				'eval'             => array(
					'multiple'       => true,
					'doNotSaveEmpty' => true,
					'tl_class'       => 'w50" style="height:auto;'
				)
			);
			$GLOBALS['TL_DCA']['tl_stylepicker4ward']['fields']['_AvisotaNewsletterCE_Row'] = array
			(
				'label'            => &$GLOBALS['TL_LANG']['tl_stylepicker4ward']['_CE_Row'],
				'inputType'        => 'checkbox',
				'options_callback' => array('AvisotaBackendStylepickerDCA', 'getAvisotaNewsletterSections'),
				'load_callback'    => array(array('AvisotaBackendStylepickerDCA', 'loadAvisotaNewsletterCE_Rows')),
				'save_callback'    => array(array('AvisotaBackendStylepickerDCA', 'doNothing')),
				'reference'        => &$GLOBALS['TL_LANG']['tl_article'],
				'eval'             => array(
					'multiple'       => true,
					'doNotSaveEmpty' => true,
					'tl_class'       => 'w50" style="height:auto;'
				)
			);
		}
	}
}
