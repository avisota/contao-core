<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class AvisotaNewsletter
 *
 * Parent class for newsletter content elements.
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaNewsletter extends Backend
{
	protected $draft;
	
	
	protected $category;
	
	
	protected $newsletter;
	
			
	private $htmlHeadCache = false;
	
	
	public function __construct($varId, $blnDraft = false)
	{
		$this->draft = $blnDraft;
		
		parent::__construct();
		$this->import('DomainLink');
		$this->import('BackendUser', 'User');
		$this->import('AvisotaBase', 'Base');
		
		$this->newsletter = $this->Database->prepare("
				SELECT
					*
				FROM
					" . ($this->draft ? "tl_avisota_newsletter_draft" : "tl_avisota_newsletter") . "
				WHERE
					id=?
				OR  alias=?")
			->execute($varId, $varId);
		if (!$this->newsletter->next())
		{
			return false;
		}
		
		if (!$this->draft)
		{
			// get the newsletter category
			$this->category = $this->Database->prepare("
					SELECT
						*
					FROM
						tl_avisota_newsletter_category
					WHERE
						id=?")
				->execute($this->newsletter->pid);
			
			if (!$this->category->next())
			{
				return false;
			}
			
			// Add default sender address
			if (!strlen($this->category->sender))
			{
				list($this->category->senderName, $this->category->sender) = $this->splitFriendlyName($GLOBALS['TL_CONFIG']['adminEmail']);
			}
		}
		else
		{
			$this->category = false;
		}
			
		$this->loadLanguageFile('tl_avisota_newsletter');
	}
	
	
	/**
	 * Get the data array of this newsletter.
	 * 
	 * @return array
	 */
	public function getData()
	{
		return $this->newsletter->row();
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
		// Drafts could not be send
		if ($this->draft)
		{
			throw new Exception("Could not send a newsletter draft.");
		}
		
		// create the contents
		$plain = $this->prepareBeforeSending($this->generatePlain($objRecipient));
		$html  = $this->prepareBeforeSending($this->generateHtml($objRecipient));

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
	 * Generate the newsletter content.
	 * 
	 * @param Database_Result $this->newsletter
	 * @param Database_Result $this->category
	 * @param array $arrRecipient
	 * @param string $personalized
	 * @param string $mode
	 * @return string
	 */
	protected function generateContent(AvisotaRecipient $objRecipient, $mode, $area = false)
	{
		$strContent = '';
		
		$objContent = $this->Database->prepare("
				SELECT
					*
				FROM
					" . ($this->draft ? "tl_avisota_newsletter_draft_content" : "tl_avisota_newsletter_content") . "
				WHERE
						pid=?
					AND invisible=''
					AND area=?
				ORDER BY
					sorting")
			->execute($this->newsletter->id, $area ? $area : 'body');
		
		while ($objContent->next())
		{
			$strContent .= $this->generateNewsletterElement($objContent, $mode, $personalized);
		}
		
		return $strContent;
	}
	
	
	/**
	 * Generate the html newsletter.
	 * 
	 * @param Database_Result $this->newsletter
	 * @param Database_Result $this->category
	 * @param array $arrRecipient
	 * @param string $personalized
	 * @return string
	 */
	public function generateHtml(AvisotaRecipient $objRecipient)
	{
		if ($this->draft && !strlen($this->newsletter->template_html))
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
				$head .= implode("\n", $this->LayoutAdditionalSources->generateIncludeHtml($arrStylesheet, true, $this->Base->getViewOnlinePage($this->category)));
			}
		}
		
		$objTemplate = new FrontendTemplate($this->newsletter->template_html ? $this->newsletter->template_html : $this->category->template_html);
		$objTemplate->title = $this->newsletter->subject;
		$objTemplate->head = $head;
		foreach ($this->getNewsletterAreas($this->category) as $strArea)
		{
			$objTemplate->$strArea = $this->generateContent($objRecipient, NL_HTML, $strArea);
		}
		$objTemplate->newsletter = $this->newsletter->row();
		$objTemplate->category = $this->category->row();
		return $objTemplate->parse();
	}
	
	
	/**
	 * Generate the plain text newsletter.
	 * 
	 * @param Database_Result $this->newsletter
	 * @param Database_Result $this->category
	 * @param array $arrRecipient
	 * @param string $personalized
	 * @return string
	 */
	public function generatePlain(AvisotaRecipient $objRecipient)
	{
		if ($this->draft && !strlen($this->newsletter->template_plain))
		{
			return false;
		}
		
		$objTemplate = new FrontendTemplate($this->newsletter->template_plain ? $this->newsletter->template_plain : $this->category->template_plain);
		foreach ($this->getNewsletterAreas($this->category) as $strArea)
		{
			$objTemplate->$strArea = $this->generateContent($objRecipient, NL_PLAIN, $strArea);
		}
		$objTemplate->newsletter = $this->newsletter->row();
		$objTemplate->category = $this->category->row();
		return $objTemplate->parse();
	}
	
	
	/**
	 * Clean up CSS Code.
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
	 * Get a list of areas.
	 * 
	 * @return array
	 */
	protected function getNewsletterAreas()
	{
		return array_unique(array_filter(array_merge(array('body'), trimsplit(',', $this->category->areas))));
	}
	
	
	/**
	 * Generate a content element return it as string.
	 * 
	 * @param AvisotaRecipient $objRecipient
	 * @param integer $intId
	 * @param string $mode
	 * @return string
	 */
	public function getNewsletterElement(AvisotaRecipient $objRecipient, $intId, $mode = NL_HTML)
	{
		if (!strlen($intId) || $intId < 1)
		{
			return '';
		}

		$objElement = $this->Database->prepare("
				SELECT
					*
				FROM
					" . ($this->draft ? "tl_avisota_newsletter_draft_content" : "tl_avisota_newsletter_content") . "
				WHERE
					id=?
				AND pid=?")
			->limit(1)
			->execute($intId, $this->newsletter->id);

		if ($objElement->numRows < 1)
		{
			return '';
		}
		
		$strBuffer = $this->generateNewsletterElement($objRecipient, $objElement, $mode);
		$strBuffer = $this->replaceInsertTags($strBuffer);
		
		return $strBuffer;
	}

	
	/**
	 * Generate a content element return it as plain text string
	 * @param integer
	 * @return string
	 */
	public function generateNewsletterElement(AvisotaRecipient $objRecipient, Database_Result $objElement, $mode = NL_HTML)
	{
		if (!$objRecipient->personal && $objElement->personalize == 'private')
		{
			return '';
		}
		
		$strClass = $this->findNewsletterElement($objElement->type);

		// Return if the class does not exist
		if (!$this->classFileExists($strClass))
		{
			$this->log('Newsletter content element class "'.$strClass.'" (newsletter content element "'.$objElement->type.'") does not exist', 'AvisotaNewsletter getNewsletterElement()', TL_ERROR);
			return '';
		}

		$objElement->typePrefix = 'nle_';
		$objElement = new $strClass($objElement);
		switch ($mode)
		{
		case NL_HTML:
			$strBuffer = $objElement->generateHTML($objRecipient);
			break;
		
		case NL_PLAIN:
			$strBuffer = $objElement->generatePlain($objRecipient);
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
}
?>