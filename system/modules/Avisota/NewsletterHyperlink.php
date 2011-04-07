<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class NewsletterHyperlink
 *
 * 
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class NewsletterHyperlink extends NewsletterElement
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = 'nle_hyperlink_html';

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = 'nle_hyperlink_plain';
	
	
	/**
	 * Parse the html template
	 * @return string
	 */
	public function generateHTML()
	{
		if ($this->useImage)
		{
			$this->strTemplate = 'nle_hyperlink_image';
		}
		
		return parent::generateHTML();
	}

	
	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$this->import('String');

		if (substr($this->url, 0, 7) == 'mailto:')
		{
			$this->url = $this->String->encodeEmail($this->url);
		}
		else
		{
			$this->url = $this->extendURL(ampersand($this->url));
		}

		$embed = explode('%s', $this->embed);

		if (!strlen($this->linkTitle))
		{
			$this->linkTitle = $this->url;
		}

		// Use an image instead of the title
		if ($mode == NL_HTML && $this->useImage && strlen($this->singleSRC) && is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			$objFile = new File($this->singleSRC);

			if ($objFile->isGdImage)
			{
				$size = deserialize($this->size);
				$intMaxWidth = (TL_MODE == 'BE') ? 320 : 0;

				// Adjust image size
				if ($intMaxWidth > 0  && ($size[0] > $intMaxWidth || (!$size[0] && $objFile->width > $intMaxWidth)))
				{
					$size[0] = $intMaxWidth;
					$size[1] = floor($intMaxWidth * $objFile->height / $objFile->width);
				}

				$src = $this->getImage($this->urlEncode($this->singleSRC), $size[0], $size[1], $size[2]);

				if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
				{
					$this->Template->imgSize = ' ' . $imgSize[3];
				}

				$this->Template->src = $this->extendURL($src);
				$this->Template->alt = specialchars($this->alt);
				$this->Template->title = specialchars($this->linkTitle);
				$this->Template->caption = $this->caption;
			}
		}

		$this->Template->href = $this->url;
		$this->Template->embed_pre = $embed[0];
		$this->Template->embed_post = $embed[1];
		$this->Template->link = $this->linkTitle;
		$this->Template->title = specialchars($this->linkTitle);
	}
}

?>