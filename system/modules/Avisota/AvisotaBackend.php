<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class AvisotaBackend
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBackend extends System
{
	public function hookOutputBackendTemplate($strContent, $strTemplate)
	{
		if ($strTemplate == 'be_main' && $this->Input->get('table') == 'tl_avisota_recipient_import')
		{
			$strContent = str_replace('<form', '<form enctype="multipart/form-data"', $strContent);
		}
		return $strContent;
	}
}
?>