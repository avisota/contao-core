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
class DatabaseNewsletter extends Newsletter
{
	/**
	 * The category object.
	 * 
	 * @var stdClass
	 */
	protected $objCategory;
	
	
	
	/**
	 * List of content areas.
	 * 
	 * @var array
	 */
	protected $arrAreas = null;
	
	
	/**
	 * The newsletter content data.
	 * 
	 * @var array
	 */
	protected $arrContent = null;
	
	
	/**
	 * Create a new newsletter.
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
					tl_avisota_newsletter
				WHERE
					id=?
				OR  alias=?")
			->execute($varId, $varId);
		if (!$objNewsletter->next())
		{
			return false;
		}
		
		// get the newsletter category
		$objCategory = $this->Database->prepare("
				SELECT
					*
				FROM
					tl_avisota_newsletter_category
				WHERE
					id=?")
			->execute($this->newsletter->pid);
		if (!$objCategory->next())
		{
			return false;
		}
		
		parent::__construct($objNewsletter->row(), $objRecipient);
		$this->objCategory = (object)$objCategory->row();
		
		$this->loadLanguageFile('tl_avisota_newsletter');
	}
		
	
	/**
	 * (non-PHPdoc)
	 */
	public function __get($k)
	{
		switch ($k)
		{
		case 'category':
			return $this->objCategory;
		
		default:
			return parent::__get($k);
		}
	}
	

	/**
	 * (non-PHPdoc)
	 * @see AbstractNewsletter::getContentAreas()
	 */
	protected function getContentAreas()
	{
		if ($this->arrAreas == null)
		{
			$this->arrAreas = array('body'=>array());
			foreach (array_filter(trimsplit(',', $this->objCategory->areas)) as $strArea)
			{
				$this->arrAreas[] = $strArea;
			}
		}
		
		return $this->arrAreas;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractNewsletter::getContent()
	 */
	protected function getContentData($strArea = 'body')
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
						tl_avisota_newsletter_content
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
	
	
	/**
	 * (non-PHPdoc)
	 * @see Newsletter::getContentHtmlTemplate()
	 */
	public function getContentHtmlTemplate()
	{
		if ($this->objNewsletter->template_html)
		{
			return $this->objNewsletter->template_html;
		}
		if ($this->objCategory->template_html)
		{
			return $this->objCategory->template_html;
		}
		return false;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Newsletter::getContentPlainTemplate()
	 */
	public function getContentPlainTemplate()
	{
		if ($this->objNewsletter->template_plain)
		{
			return $this->objNewsletter->template_plain;
		}
		if ($this->objCategory->template_plain)
		{
			return $this->objCategory->template_plain;
		}
		return false;
	}
}
?>