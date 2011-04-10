<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class AvisotaBase
 *
 * InsertTag hook class.
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBase extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->import('DomainLink');
	}
	
	
	/**
	 * Extend the url to an absolute url.
	 */
	public function extendURL($strUrl, $objPage = null, $objCategory = null, $arrRecipient = null)
	{
		if ($objPage == null)
		{
			$objPage = $this->getViewOnlinePage($objCategory, $arrRecipient);
		}
		
		return $this->DomainLink->absolutizeUrl($strUrl, $objPage);
	}
}
?>