<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class AvisotaEditorStyle
 *
 * InsertTag hook class.
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaEditorStyle extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->import('Input');
	}
	
	
	public function getEditorStylesLayout($strEditor)
	{
		if (	$strEditor == 'newsletter'
			&&	$this->Input->get('do') == 'avisota_newsletter'
			&&	$this->Input->get('table') == 'tl_avisota_newsletter_content'
			&&	$this->Input->get('act') == 'edit')
		{
			$strId = $this->Input->get('id');
			
			$objNewsletter = $this->Database->prepare("
					SELECT
						n.*
					FROM
						`tl_avisota_newsletter` n
					INNER JOIN
						`tl_avisota_newsletter_content` c
					ON
						n.`id`=c.`pid`
					WHERE
						c.`id`=?")
				->execute($strId);
			
			$objCategory = $this->Database->prepare("
					SELECT
						*
					FROM
						`tl_avisota_newsletter_category`
					WHERE
						`id`=?")
				->execute($objNewsletter->pid);
			
			if ($objCategory->viewOnlinePage > 0 && 0)
			{
				// the "view online" page does not contains the option to set a layout, use parent page instead
				$objViewOnlinePage = $this->Database->prepare("
						SELECT
							*
						FROM
							`tl_page`
						WHERE
							`id`=?")
					->execute($objCategory->viewOnlinePage);
				$objPage = $this->getPageDetails($objViewOnlinePage->pid);
			}
			elseif ($objCategory->subscriptionPage > 0)
			{
				$objPage = $this->getPageDetails($objCategory->subscriptionPage);
			}
			else
			{
				return false;
			}
			return $objPage->layout;
		}
		return false;
	}
}
?>