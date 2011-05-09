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
class StoredNewsletter extends Newsletter
{
	/**
	 * The newsletter content data.
	 * 
	 * @var array
	 */
	protected $arrContent = null;
	
	
	/**
	 * The html template.
	 * 
	 * @var string
	 */
	protected $strTemplateHtml;
	
	
	/**
	 * The plain template.
	 * 
	 * @var string
	 */
	protected $strTemplatePlain;
	
	
	public function __construct($arrData, $arrContent, AvisotaRecipient $objRecipient,
		$strTemplateHtml, $strTemplatePlain)
	{
		parent::__construct($arrData, $objRecipient);
		$this->arrContent = $arrContent;
		$this->strTemplateHtml = $strTemplateHtml;
		$this->strTemplatePlain = $strTemplatePlain;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Newsletter::getContentAreas()
	 */
	public function getContentAreas()
	{
		return array_keys($this->arrContent);
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Newsletter::getContent()
	 */
	public function getContentData($strArea = 'body')
	{
		if ($this->arrContent[$strArea])
		{
			return $this->arrContent[$strArea];
		}
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Newsletter::getContentHtmlTemplate()
	 */
	protected function getContentHtmlTemplate()
	{
		return $this->strTemplateHtml;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Newsletter::getContentPlainTemplate()
	 */
	protected function getContentPlainTemplate()
	{
		return $this->strTemplatePlain;
	}
}

?>