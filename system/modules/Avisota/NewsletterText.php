<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class NewsletterText
 *
 * 
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterText extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_text_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_text_plain';
	

	/**
	 * Replace an image tag.
	 * @param array $arrMatch
	 */
	public function replaceImage($arrMatch)
	{
		// insert alt or title text
		return sprintf('%s<%s>', $arrMatch[3] ? $arrMatch[3] . ': ' : $arrMatch[2] ? $arrMatch[2] . ': ' : '', $this->extendURL($arrMatch[1]));
	}
	
	
	/**
	 * Replace an link tag.
	 * @param array $arrMatch
	 */
	public function replaceLink($arrMatch)
	{
		// insert title text
		return sprintf('%s%s <%s>', $arrMatch[3], $arrMatch[2] ? ' (' . $arrMatch[2] . ')' : '', $this->extendURL($arrMatch[1]));
	}
	
	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$this->import('String');

		switch ($mode)
		{
		case NL_HTML:
			// Clean RTE output
			$this->Template->text = str_ireplace
			(
				array('<u>', '</u>', '</p>', '<br /><br />', ' target="_self"'),
				array('<span style="text-decoration:underline;">', '</span>', "</p>\n", "<br /><br />\n", ''),
				$this->String->encodeEmail($this->text)
			);
			break;
			
		case NL_PLAIN:
			if ($this->plain)
			{
				$this->Template->text = $this->plain;
			}
			else
			{
				$strText = $this->text;
				
				// remove line breaks
				$strText = str_replace
				(
					array("\r", "\n"),
					'',
					$strText
				);
				
				// replace bold, italic and underlined text
				$strText = preg_replace
				(
					array('#</?(b|strong)>#', '#</?(i|em)>#', '#</?u>#'),
					array('*', '_', '+'),
					$strText
				);
				
				// replace images
				$strText = preg_replace_callback
				(
					'#<img[^>]+src="([^"]+)"[^>]*(?:alt="([^"])")?[^>]*(?:title="([^"])")?[^>]*>#U',
					array(&$this, 'replaceImage'),
					$strText
				);
				
				// replace links
				$strText = preg_replace_callback
				(
					'#<a[^>]+href="([^"]+)"[^>]*(?:title="([^"])")?[^>]*>(.*?)</a>#',
					array(&$this, 'replaceLink'),
					$strText
				);
				
				// replace line breaks and paragraphs
				$strText = str_replace
				(
					array('</div>', '</p>', '<br/>', '<br>'),
					array("\n", "\n\n", "\n", "\n"),
					$strText
				);
				
				// strip all remeaning tags
				$strText = strip_tags($strText);
				
				// decode html entities
				$strText = html_entity_decode($strText);
				
				// wrap the lines
				$strText = wordwrap($strText);
				
				$this->Template->text = $strText;
			}
		}

		$this->Template->addImage = false;

		// Add image
		if ($this->addImage && strlen($this->singleSRC) && is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			$this->addImageToTemplate($this->Template, $this->arrData);
			
			$this->Template->src = $this->extendURL($this->Template->src);
			if ($this->Template->href)
			{
				$this->Template->href = $this->extendURL($this->Template->href);
			}
		}
	}
}

?>