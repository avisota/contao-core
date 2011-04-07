<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


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
	 * Generate a newsletter
	 * @param object
	 */
	public function generate(Database_Result $objPage)
	{
		$this->import('Avisota');
		
		$strId = $this->Input->get('item');
		$strNewsletter = $this->Avisota->generateOnlineNewsletter($strId);
		
		if ($strNewsletter)
		{
			header('Content-Type: text/html; charset=utf-8');
			echo $strNewsletter;
			exit;
		}
		
		$this->redirect($this->generateFrontendUrl($this->getPageDetails($objPage->jumpBack ? $objPage->jumpBack : $objPage->pid))->row());
	}
}
?>