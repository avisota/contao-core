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

class tl_stylepicker4ward_avisota_callback
{
	public function hookLoadDataContainer($name)
	{
		if ($name == 'tl_stylepicker4ward') {
			$GLOBALS['TL_DCA']['tl_stylepicker4ward']['palettes']['default'] .= ';{Avisota_legend},_AvisotaNewsletterCEs,_AvisotaNewsletterCE_Row';
			$GLOBALS['TL_DCA']['tl_stylepicker4ward']['fields']['_AvisotaNewsletterCEs']    = array
			(
				'label'            => &$GLOBALS['TL_LANG']['tl_stylepicker4ward']['_AvisotaNewsletterCEs'],
				'inputType'        => 'checkbox',
				'options_callback' => array('AvisotaBackendStylepickerDCA', 'getAvisotaNewsletterContentElements'),
				'load_callback'    => array(array('AvisotaBackendStylepickerDCA', 'loadAvisotaNewsletterCEs')),
				'save_callback'    => array(array('AvisotaBackendStylepickerDCA', 'saveAvisotaNewsletterCEs')),
				'reference'        => &$GLOBALS['TL_LANG']['MCE'],
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
