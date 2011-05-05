<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class Newsletter
 *
 * Parent class for newsletter content elements.
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterDraft extends Newsletter
{
	/**
	 * Create a new newsletter draft.
	 * 
	 * @param int $varId
	 * @param AvisotaRecipient $objRecipient
	 */
	public function __construct($varId, AvisotaRecipient $objRecipient)
	{
		$this->import('Config');
		$this->import('Database');
		
		$objNewsletter = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter_draft
				WHERE
					id=?
				OR  alias=?")
			->execute($varId, $varId);
		if (!$objNewsletter->next())
		{
			return false;
		}
		
		AbstractNewsletter::__construct((object)$objNewsletter->row());
		$this->objCategory = null;
		$this->objRecipient = $objRecipient;
			
		$this->loadLanguageFile('tl_avisota_newsletter');
	}
	

	/**
	 * (non-PHPdoc)
	 * @see AbstractNewsletter::getContentAreas()
	 */
	public function getContentAreas()
	{
		if ($this->arrAreas == null)
		{
			$this->arrAreas = array('body'=>array());
			
			$objCategory = $this->Database->execute("SELECT * FROM tl_avisota_newsletter_category");
			while ($objCategory->next())
			{
				foreach (array_filter(trimsplit(',', $objCategory->areas)) as $strArea)
				{
					$this->arrAreas[] = $strArea;
				}
			}
			
			$this->arrAreas = array_unique($this->arrAreas);
		}
		
		return $this->arrAreas;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractNewsletter::getContent()
	 */
	public function getContentData($strArea = 'body')
	{
		if ($this->arrAreas == null)
		{
			$this->getContentAreas();
		}
		if ($this->arrContent == null)
		{
			$strSet = "'" . implode("','", $this->arrAreas) . "'";
			$objContent = $this->Database->prepare("
					SELECT
						*
					FROM
						tl_avisota_newsletter_draft_content
					WHERE
							pid=?
						AND invisible=''
						AND area IN ($strSet)
					ORDER BY
						sorting")
				->execute($this->objNewsletter->id);
			while ($objContent->next())
			{
				$this->arrContent[$objContent->area] = (object)$objContent->row();
			}
		}
		
		if (isset($this->arrContent[$strArea]))
		{
			return $this->arrContent[$strArea];
		}
		
		return array();
	}
}

?>