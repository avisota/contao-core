<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class NewsletterHeadline
 *
 * 
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterHeadline extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_headline_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_headline_plain';
	

	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
	}
}

?>