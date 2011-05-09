<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class AvisotaBackend
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBackend extends Controller
{
	/**
	 * Generate array of recipient sources.
	 * 
	 * @return array
	 */
	public function getRecipients()
	{
		$arrRecipients = array();
		
		$objSource = $this->Database->execute("
				SELECT
					*
				FROM
					tl_avisota_recipient_source
				ORDER BY
					title");
		
		while ($objSource->next())
		{
			$strType = $objSource->type;
			$strClass = $GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE'][$strType];
			$objClass = new $strClass($objSource->row());
			$arrLists = $objClass->getLists();
			if (is_null($arrLists))
			{
				$arrRecipients[$objSource->id] = $objSource->title;
			}
			else
			{
				$arrRecipients[$objSource->title] = array();
				foreach ($arrLists as $k=>$v)
				{
					$arrRecipients[$objSource->title][$objSource->id . ':' . $k] = $v;
				}
			}
		}
		
		return $arrRecipients;
	}
	
	
	/**
	 * Render a newsletter indicated by request parameters.
	 */
	public function renderNewsletter()
	{
		$this->render(false);
	}
	
	
	/**
	 * Render a newsletter draft indicated by request parameters.
	 */
	public function renderDraft()
	{
		$this->render(true);
	}
	
	
	/**
	 * Generate and print out the preview.
	 */
	protected function render($blnDraft = false)
	{
		// get preview mode
		if ($this->Input->get('mode'))
		{
			$strMode = $this->Input->get('mode');
		}
		else
		{
			$strMode = $this->Session->get('tl_avisota_preview_mode');
		}
		
		if (!$strMode)
		{
			$strMode = NL_HTML;
		}
		$this->Session->set('tl_avisota_preview_mode', $strMode);
		
		// get personalized state
		if ($this->Input->get('personalized'))
		{
			$blnPersonalized = true;
		}
		else
		{
			$blnPersonalized = $this->Session->get('tl_avisota_preview_personalized');
		}
		
		if (!$blnPersonalized)
		{
			$blnPersonalized = false;
		}
		$this->Session->set('tl_avisota_preview_personalized', $blnPersonalized);
		
		// find the newsletter
		$varId = $this->Input->get('id');
		
		if ($blnDraft)
		{		
			// get templates
			$strTemplateHtml = false;
			if (!$objNewsletter->template_html)
			{
				if ($this->Input->get('template_html'))
				{
					$strTemplateHtml = $this->Input->get('template_html');
				}
				if (!$strTemplateHtml)
				{
					$strTemplateHtml = $this->Session->get('tl_avisota_preview_template_html');
				}
				$this->Session->set('tl_avisota_preview_template_html', $strTemplateHtml);
			}
			
			$strTemplatePlain = false;
			if (!$objNewsletter->template_plain)
			{
				if ($this->Input->get('template_plain'))
				{
					$strTemplatePlain = $this->Input->get('template_plain');
				}
				if ($strTemplatePlain)
				{
					$strTemplatePlain = $this->Session->get('tl_avisota_preview_template_plain');
				}
				$this->Session->set('tl_avisota_preview_template_plain', $strTemplatePlain);
			}
		}

		$objNewsletter = $blnDraft ? new DraftNewsletter($varId, AvisotaRecipient::dummy(), $strTemplateHtml, $strTemplatePlain) : new DatabaseNewsletter($varId, AvisotaRecipient::dummy());
		
		if (!$objNewsletter)
		{
			$this->redirect('contao/main.php?act=tl_error');
		}
		
		// generate the preview
		switch ($strMode)
		{
		case NL_HTML:
			header('Content-Type: text/html; charset=utf-8');
			echo $objNewsletter->getContentHtml();
			exit;
			
		case NL_PLAIN:
			header('Content-Type: text/plain; charset=utf-8');
			echo $objNewsletter->getContentPlain();
			exit;
		
		default:
			$this->redirect('contao/main.php?act=tl_error');
		}
	}
	
	
	/**
	 * Show the newsletter preview form.
	 */
	public function previewNewsletter()
	{
		// find the newsletter
		$varId = $this->Input->get('id');
		
		$objNewsletter = new DatabaseNewsletter($varId, AvisotaRecipient::dummy());
		
		if (!$objNewsletter)
		{
			$this->redirect('contao/main.php?act=tl_error');
		}
		
		$objTemplate = new BackendTemplate('be_avisota_preview');
		$objTemplate->newsletter = $objNewsletter;
		
		$objTemplate->html_templates = $arrTemplatesHtml;
		$objTemplate->plain_templates = $arrTemplatesPlain;
		
		// Store the current referer
		$session = $this->Session->get('referer');
		if ($session['current'] != $this->Environment->requestUri)
		{
			$session['tl_avisota_newsletter'] = $this->Environment->requestUri;
			//$session['last'] = $session['current'];
			//$session['current'] = $this->Environment->requestUri;
			$this->Session->set('referer', $session);
		}
		
		return $objTemplate->parse();
	}
	
	
	/**
	 * Show the draft preview form.
	 */
	public function previewDraft()
	{
		$this->loadLanguageFile('be_avisota');
		
		// find the newsletter
		$varId = $this->Input->get('id');
		
		$arrTemplatesHtml = $this->getTemplateGroup('mail_html_');
		$arrTemplatesPlain = $this->getTemplateGroup('mail_plain_');
		
		if (!$objNewsletter->template_html)
		{
			$this->Session->set('tl_avisota_preview_template_html', $arrTemplatesHtml[0]);
		}
		
		if (!$objNewsletter->template_plain)
		{
			$this->Session->set('tl_avisota_preview_template_plain', $arrTemplatesPlain[0]);
		}
		
		$objNewsletter = new DraftNewsletter($varId, AvisotaRecipient::dummy(),
			$this->Session->get('tl_avisota_preview_template_html'),
			$this->Session->get('tl_avisota_preview_template_plain'));
		
		if (!$objNewsletter)
		{
			$this->redirect('contao/main.php?act=tl_error');
		}
		
		$objTemplate = new BackendTemplate('be_avisota_preview_draft');
		$objTemplate->import('BackendUser', 'User');
		$objTemplate->newsletter = $objNewsletter;
		
		$objTemplate->html_templates = $arrTemplatesHtml;
		$objTemplate->plain_templates = $arrTemplatesPlain;
		
		// Store the current referer
		$session = $this->Session->get('referer');
		if ($session['current'] != $this->Environment->requestUri)
		{
			$session['tl_avisota_newsletter'] = $this->Environment->requestUri;
			$session['last'] = $session['current'];
			$session['current'] = $this->Environment->requestUri;
			$this->Session->set('referer', $session);
		}
		
		return $objTemplate->parse();
	}
	
	
	/**
	 * 
	 */
	public function hookAddCustomRegexp($strRegexp, $varValue, Widget $objWidget)
	{
		return false;
	}
	
	
	/**
	 * Change the form enctype for multipart upload.
	 * 
	 * @param string $strContent
	 * @param string $strTemplate
	 * @return string
	 */
	public function hookOutputBackendTemplate($strContent, $strTemplate)
	{
		if ($strTemplate == 'be_main' && ($this->Input->get('table') == 'tl_avisota_recipient_import' || $this->Input->get('table') == 'tl_avisota_recipient_remove'))
		{
			$strContent = str_replace('<form', '<form enctype="multipart/form-data"', $strContent);
		}
		return $strContent;
	}
}
?>
