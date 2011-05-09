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
abstract class Newsletter extends Controller
{
	/**
	 * The data.
	 * 
	 * @var array
	 */
	protected $arrData;
	
	
	/**
	 * The recipient object.
	 * 
	 * @var AvisotaRecipient
	 */
	protected $objRecipient;
	
	
	/**
	 * Create a new newsletter.
	 * 
	 * @var array
	 *     The newsletter data.
	 * @var AvisotaRecipient
	 *     The recipient object.
	 */
	public function __construct($arrData, AvisotaRecipient $objRecipient)
	{
		$this->arrData = $arrData;
		$this->objRecipient = $objRecipient;
	}
	
	
	public function __get($k)
	{
		switch ($k)
		{
			case 'recipient':
				return $this->objRecipient;
			
			case 'data':
				return $this->arrData;
				
			default:
				if ($this->arrData[$k])
				{
					return $this->arrData[$k];
				}
		}
		return '';
	}
	
	
	/**
	 * Get all existing content areas.
	 * 
	 * @return array<string>
	 */
	protected abstract function getContentAreas();
	
	
	/**
	 * Get the content of a specific area.
	 * 
	 * @return array<stdClass>
	 */
	protected abstract function getContentData($strArea = 'body');
	
	
	/**
	 * Get the html template
	 * 
	 * @return string
	 * @throws
	 */
	protected abstract function getContentHtmlTemplate();


	/**
	 * Get the html content.
	 * 
	 * @return string
	 */
	public function getContentHtml()
	{
		$strTemplate = $this->getContentHtmlTemplate();
		if (!$strTemplate)
		{
			return false;
		}
		
		$head = '';
		
		// Add style sheet newsletter.css
		if (file_exists(TL_ROOT . '/newsletter.css'))
		{
			$head .= '<style type="text/css">' . "\n" . $this->cleanCSS(file_get_contents(TL_ROOT . '/newsletter.css')) . "\n" . '</style>' . "\n";
		}
		
		if (in_array('layout_additional_sources', $this->Config->getActiveModules()))
		{
			$arrStylesheet = unserialize($this->category->stylesheets);
			if (is_array($arrStylesheet) && count($arrStylesheet))
			{
				$this->import('LayoutAdditionalSources');
				$this->LayoutAdditionalSources->productive = true;
				$head .= implode("\n", $this->LayoutAdditionalSources->generateIncludeHtml($arrStylesheet, true /* TODO , $this->Base->getViewOnlinePage($this->category) */));
			}
		}
		
		$objTemplate = $this->compileTemplate($strTemplate, NL_HTML);
		$objTemplate->head = $head;
		return $this->parseTemplate($objTemplate);
	}
	
	
	/**
	 * Get the plain template
	 * 
	 * @return string
	 * @throws
	 */
	protected abstract function getContentPlainTemplate();
	
	
	/**
	 * (non-PHPdoc)
	 * @see Newsletter::generateContent()
	 */
	public function getContentPlain()
	{
		$strTemplate = $this->getContentPlainTemplate();
		if (!$strTemplate)
		{
			return false;
		}
		
		$objTemplate = $this->compileTemplate($strTemplate, NL_PLAIN);
		$objTemplate->head = $head;
		return $this->parseTemplate($objTemplate);
	}
	
	
	/**
	 * Create the template object.
	 */
	protected function compileTemplate($strTemplate, $strMode)
	{
		$objTemplate = new FrontendTemplate($strTemplate);
		$objTemplate->setData($this->objData);
		$this->generateContent($objTemplate, $strMode);
		return $objTemplate;
	}
	
	
	/**
	 * Parse the template object.
	 */
	protected function parseTemplate($objTemplate)
	{
		$strBuffer = $objTemplate->parse();
		$strBuffer = $this->prepareBeforeSending($strBuffer);
		AvisotaInsertTag::setCurrent($this, $this->objRecipient);
		$strBuffer = $this->replaceInsertTags($strBuffer);
		AvisotaInsertTag::clearCurrent();
		return $strBuffer;
	}
	
	
	/**
	 * Generate the content areas and add them to the template.
	 * 
	 * @param string
	 *     The rendering mode
	 * @param Template
	 *     The template object.
	 * @return void;
	 */
	protected function generateContent($objTemplate, $strMode)
	{
		foreach ($this->getContentAreas() as $strArea)
		{
			$arrContentData = $this->getContentData($strArea);
			
			$strContent = '';
			foreach ($arrContentData as $objContent)
			{
				$strContent .= $this->generateNewsletterElement($objContent, $strMode);
			}
			
			$objTemplate->$strArea = $strContent;
		}
	}
	
	/**
	 * Clean up CSS Code.
	 * 
	 * @param string
	 *     The css code to clean.
	 * @param string
	 *     The source path to calculate relative path difference.
	 * @return string
	 *     The cleaned css code.
	 */
	protected function cleanCSS($css, $source = '')
	{
		if ($source)
		{
			$source = dirname($source);
		}
		
		// remove comments
		$css = trim(preg_replace('@/\*\*.*\*/@Us', '', $css));
		
		// handle @charset
		if (preg_match('#\@charset\s+[\'"]([\w\-]+)[\'"]\;#Ui', $css, $arrMatch))
		{
			// convert character encoding to utf-8
			if (strtoupper($arrMatch[1]) != 'UTF-8')
			{
				$css = iconv(strtoupper($arrMatch[1]), 'UTF-8', $css);
			}
			// remove @charset tag
			$css = str_replace($arrMatch[0], '', $css);
		}
		
		// extends css urls
		if (preg_match_all('#url\((.+)\)#U', $css, $arrMatches, PREG_SET_ORDER))
		{
			foreach ($arrMatches as $arrMatch)
			{
				$path = $source;
				
				$strUrl = $arrMatch[1];
				if (preg_match('#^".*"$#', $strUrl) || preg_match("#^'.*'$#", $strUrl))
				{
					$strUrl = substr($strUrl, 1, -1);
				}
				while (preg_match('#^\.\./#', $strUrl))
				{
					$path = dirname($path);
					$strUrl = substr($strUrl, 3);
				}
				if (!preg_match('#^\w+://#', $strUrl) && $strUrl[0] != '/')
				{
					$strUrl = ($path ? $path . '/' : '') . $strUrl;
				}
				
				$css = str_replace($arrMatch[0], sprintf('url("%s")', $this->Base->extendURL($strUrl)), $css);
			}
		}
		
		return trim($css);
	}
	
	
	
	/**
	 * Generate a content element return it as plain text string
	 * 
	 * @param stdClass
	 *     The element data object.
	 * @param string
	 *     The rendering mode.
	 * @return string
	 *     The rendered content element.
	 */
	public function generateNewsletterElement(stdClass $objElement, $strMode)
	{
		if (!$this->objRecipient->personal && $objElement->personalize == 'private')
		{
			return '';
		}
		
		$strClass = $this->findNewsletterElement($objElement->type);

		// Return if the class does not exist
		if (!$this->classFileExists($strClass))
		{
			$this->log('Newsletter content element class "'.$strClass.'" (newsletter content element "'.$objElement->type.'") does not exist', 'Newsletter getNewsletterElement()', TL_ERROR);
			return '';
		}

		$objElement->typePrefix = 'nle_';
		$objElement = new $strClass((array)$objElement);
		switch ($strMode)
		{
		case NL_HTML:
			$strBuffer = $objElement->generateHTML($this->objRecipient);
			break;
		
		case NL_PLAIN:
			$strBuffer = $objElement->generatePlain($this->objRecipient);
			break;
		}
		
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getNewsletterElement']) && is_array($GLOBALS['TL_HOOKS']['getNewsletterElement']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getNewsletterElement'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($objElement, $strBuffer, $mode);
			}
		}
		
		return $strBuffer;
	}
	
	
	/**
	 * Find a newsletter content element in the TL_NLE array and return its value
	 * 
	 * @param string $strName
	 * @return string
	 */
	protected function findNewsletterElement($strName)
	{
		foreach ($GLOBALS['TL_NLE'] as $v)
		{
			foreach ($v as $kk=>$vv)
			{
				if ($kk == $strName)
				{
					return $vv;
				}
			}
		}

		return '';
	}
	
	
	public function getHref($objRecipient = false)
	{
		if ($objRecipient)
		{
			$objPage = $objRecipient->getViewOnlinePage();
		}
		if (!$objRecipient || !$objPage)
		{
			$objPage = $this->getViewOnlinePage();
		}
		if (!$objPage)
		{
			return false;
		}
		$this->import('DomainLink');
		return $this->DomainLink->absolutizeUrl($this->generateFrontendUrl($objPage->row(), '/item/' . (self::$objNewsletter->alias ? self::$objNewsletter->alias : self::$objNewsletter->id)), $objPage);
	}
	
	public function getViewOnlinePage()
	{
		// TODO
		return false;
	}
	
	
	/**
	 * Generate the email.
	 * 
	 * @param string $strRecipient
	 * @param mixed $arrRecipient
	 * @return Email
	 */
	public function generateEmail(AvisotaRecipient $objRecipient)
	{
		/*
		// Drafts could not be send
		if ($this->draft)
		{
			throw new Exception("Could not send a newsletter draft.");
		}
		
		// create the contents
		$plain = ;
		$html  = ;

		// Generate the email object
		$objEmail = new Email();

		// Add sender name
		$objEmail->from = $this->category->sender;
		if (strlen($this->category->senderName))
		{
			$objEmail->fromName = $this->category->senderName;
		}

		// Set basics
		$objEmail->subject = $this->newsletter->subject;
		$objEmail->logFile = 'newsletter_' . $this->newsletter->id . '.log';

		// Prepare text content
		$objEmail->text = $this->replaceInsertTags($plain);

		// Prepare html content
		$objEmail->html = $this->replaceInsertTags($html);
		$objEmail->imageDir = TL_ROOT . '/';
		
		// Attachments
		if ($this->newsletter->addFile)
		{
			$files = deserialize($this->newsletter->files);

			if (is_array($files) && count($files) > 0)
			{
				foreach ($files as $file)
				{
					if (is_file(TL_ROOT . '/' . $file))
					{
						$objEmail->attachFile(TL_ROOT . '/' . $file);
					}
				}
			}
		}

		return $objEmail;
		*/
	}
	
	
	/**
	 * Prepare the html body code before sending.
	 * 
	 * @param string
	 * @return string
	 */
	protected function prepareBeforeSending($strContent)
	{
		$strContent = str_replace('{{env::request}}', '{{newsletter::href}}', $strContent);
		$strContent = preg_replace('#\{\{env::.*\}\}#U', '', $strContent);
		
		return $strContent;
	}
	
	
	/**
	 * Store the newsletter.
	 * 
	 * @return StoredNewsletter
	 */
	protected function store()
	{
		
	}
}

?>