<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class NewsletterImage
 *
 * 
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterImage extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_image_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_image_plain';
	
	
	/**
	 * Parse the html template
	 * @return string
	 */
	public function generateHTML()
	{
		if (!strlen($this->singleSRC) || !is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			return '';
		}
		
		return parent::generateHTML();
	}
	
	
	/**
	 * Parse the plain text template
	 * @return string
	 */
	public function generatePlain()
	{
		if (!strlen($this->singleSRC) || !is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			return '';
		}
		
		return parent::generatePlain();
	}
	
	
	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$this->addImageToTemplate($this->Template, $this->arrData);
	}
}

?>