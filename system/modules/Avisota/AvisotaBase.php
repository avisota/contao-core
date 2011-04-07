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
	
	
	public function getViewOnlinePage($objCategory = null, $arrRecipient = null)
	{
		if (is_null($objCategory))
		{
			$objCategory = Avisota::getCurrentCategory();
		}
		
		if (is_null($arrRecipient))
		{
			$arrRecipient = Avisota::getCurrentRecipient();
		}
		
		if ($arrRecipient && preg_match('#^list:(\d+)$#', $arrRecipient['outbox_source'], $arrMatch))
		{
			// the dummy list, used on preview
			if ($arrMatch[1] > 0)
			{
				$objRecipientList = $this->Database->prepare("
						SELECT
							*
						FROM
							`tl_avisota_recipient_list`
						WHERE
							`id`=?")
					->execute($arrMatch[1]);
				if ($objRecipientList->next())
				{
					return $this->getPageDetails($objRecipientList->viewOnlinePage);
				}
			}
		}
		
		if ($objCategory->viewOnlinePage > 0)
		{
			return $this->getPageDetails($objCategory->viewOnlinePage);
		}
		
		return null;
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