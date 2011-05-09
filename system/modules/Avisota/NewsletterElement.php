<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class NewsletterElement
 *
 * Parent class for newsletter content elements.
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
abstract class NewsletterElement extends Frontend
{

	/**
	 * HTML Template
	 * @var string
	 */
	protected $strTemplateHTML = null;

	/**
	 * Plain text Template
	 * @var string
	 */
	protected $strTemplatePlain = null;
	
	/**
	 * Current record
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Style array
	 * @var array
	 */
	protected $arrStyle = array();


	/**
	 * Initialize the object
	 * @param object
	 * @return string
	 */
	public function __construct($arrData)
	{
		parent::__construct();

		$this->arrData = $arrData;
		$this->space = deserialize($arrData['space']);
		$this->cssID = deserialize($arrData['cssID'], true);

		$arrHeadline = deserialize($arrData['headline']);
		$this->headline = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;
		$this->hl = is_array($arrHeadline) ? $arrHeadline['unit'] : 'h1';
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}

	
	/**
	 * Extend the url to an absolute url.
	 */
	public function extendURL($strUrl)
	{
		$this->import('DomainLink');
		
		$arrRow = null;
		
		// get the newsletter category jump to page
		$objCategory = $this->Database->prepare("
				SELECT
					c.*
				FROM
					`tl_avisota_newsletter_category` c
				INNER JOIN
					`tl_avisota_newsletter` n
				ON
					c.`id`=n.`pid`
				WHERE
					n.`id`=?")
			->execute($this->pid);
		if ($objCategory->next())
		{
			$objPage = $this->getPageDetails($objCategory->jumpTo);
		}
		
		return $this->DomainLink->absolutizeUrl($strUrl, $objPage);
	}

	
	/**
	 * Add an image to a template
	 * @param object
	 * @param array
	 * @param integer
	 * @param string
	 */
	protected function addImageToTemplate($objTemplate, $arrItem, $intMaxWidth=false, $strLightboxId=false)
	{
		$size = deserialize($arrItem['size']);
		$imgSize = getimagesize(TL_ROOT . '/' . $arrItem['singleSRC']);

		if (!$intMaxWidth)
		{
			$intMaxWidth = ($this->Input->get('table') == 'tl_avisota_newsletter_content' ? 320 : $GLOBALS['TL_CONFIG']['maxImageWidth']);
		}

		// Store original dimensions
		$objTemplate->width = $imgSize[0];
		$objTemplate->height = $imgSize[1];

		// Adjust image size
		if ($intMaxWidth > 0 && ($size[0] > $intMaxWidth || (!$size[0] && !$size[1] && $imgSize[0] > $intMaxWidth)))
		{
			$arrMargin = deserialize($arrItem['imagemargin']);

			// Subtract margins
			if (is_array($arrMargin) && $arrMargin['unit'] == 'px')
			{
				$intMaxWidth = $intMaxWidth - $arrMargin['left'] - $arrMargin['right'];
			}

			// See #2268 (thanks to Thyon)
			$ratio = ($size[0] && $size[1]) ? $size[1] / $size[0] : $imgSize[1] / $imgSize[0];

			$size[0] = $intMaxWidth;
			$size[1] = floor($intMaxWidth * $ratio);
		}

		$src = $this->getImage($this->urlEncode($arrItem['singleSRC']), $size[0], $size[1], $size[2]);

		// Image dimensions
		if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
		{
			$objTemplate->arrSize = $imgSize;
			$objTemplate->imgSize = ' ' . $imgSize[3];
		}

		// Float image
		if (in_array($arrItem['floating'], array('left', 'right')))
		{
			$objTemplate->floatClass = ' float_' . $arrItem['floating'];
		}

		// Image link
		if (strlen($arrItem['imageUrl']))
		{
			$objTemplate->href = $this->extendURL($arrItem['imageUrl']);
			$objTemplate->attributes = $arrItem['fullsize'] ? LINK_NEW_WINDOW : '';
		}

		$objTemplate->src = $this->extendURL($src);
		$objTemplate->alt = specialchars($arrItem['alt']);
		$objTemplate->margin = $this->generateMargin(deserialize($arrItem['imagemargin']), 'padding');
		$objTemplate->caption = $arrItem['caption'];
		$objTemplate->addImage = true;
	}
	
	
	/**
	 * Parse the html template
	 * @return string
	 */
	public function generateHTML()
	{
		if (!$this->strTemplateHTML)
		{
			return '';
		}
		
		$this->arrStyle = array();
		
		if (strlen($this->arrData['space'][0]))
		{
			$this->arrStyle[] = 'margin-top:'.$this->arrData['space'][0].'px;';
		}

		if (strlen($this->arrData['space'][1]))
		{
			$this->arrStyle[] = 'margin-bottom:'.$this->arrData['space'][1].'px;';
		}

		$this->Template = new FrontendTemplate($this->strTemplateHTML);
		$this->Template->setData($this->arrData);

		$this->compile(NL_HTML);

		$this->Template->style = count($this->arrStyle) ? implode(' ', $this->arrStyle) : '';
		$this->Template->cssID = strlen($this->cssID[0]) ? ' id="' . $this->cssID[0] . '"' : '';
		$this->Template->class = trim('ce_' . $this->type . ' ' . $this->cssID[1]);

		if (!strlen($this->Template->headline))
		{
			$this->Template->headline = $this->headline;
		}

		if (!strlen($this->Template->hl))
		{
			$this->Template->hl = $this->hl;
		}

		return $this->Template->parse();
	}


	/**
	 * Parse the plain text template
	 * @return string
	 */
	public function generatePlain()
	{
		if (!$this->strTemplatePlain)
		{
			return '';
		}
		
		$this->arrStyle = array();
		
		$this->Template = new FrontendTemplate($this->strTemplatePlain);
		$this->Template->setData($this->arrData);

		$this->compile(NL_PLAIN);

		if (!strlen($this->Template->headline))
		{
			$this->Template->headline = $this->headline;
		}

		$intHl = intval(substr(!strlen($this->Template->hl) ? $this->hl : $this->Template->hl, 1));
		$strHl = '';
		for ($i=0; $i<$intHl; $i++)
		{
			$strHl .= '#';
		}
		$this->Template->hl = $strHl;

		return $this->Template->parse();
	}
	

	/**
	 * Compile the current element
	 */
	abstract protected function compile($mode);
}

?>