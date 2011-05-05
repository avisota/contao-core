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
		
		$objNewsletter = new Newsletter($varId, $blnDraft);
		
		if (!$objNewsletter)
		{
			$this->redirect('contao/main.php?act=tl_error');
		}
		
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
		
		$objRecipient = AvisotaRecipient::dummy($blnPersonalized);
		AvisotaInsertTag::setCurrent($objNewsletter, $objRecipient);
		
		// generate the preview
		switch ($strMode)
		{
		case NL_HTML:
			header('Content-Type: text/html; charset=utf-8');
			echo $this->replaceInsertTags($objNewsletter->generateHtml($objRecipient, $strTemplateHtml));
			exit;
			
		case NL_PLAIN:
			header('Content-Type: text/plain; charset=utf-8');
			echo $this->replaceInsertTags($objNewsletter->generatePlain($objRecipient, $strTemplatePlain));
			exit;
		
		default:
			$this->redirect('contao/main.php?act=tl_error');
		}
	}
	
	
	/**
	 * Show the draft preview form.
	 */
	public function previewDraft()
	{
		// find the newsletter
		$varId = $this->Input->get('id');
		
		$objNewsletter = new Newsletter($varId, true);
		
		if (!$objNewsletter)
		{
			$this->redirect('contao/main.php?act=tl_error');
		}
		
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
		
		$objTemplate = new BackendTemplate('be_avisota_preview_draft');
		$objTemplate->setData($objNewsletter->getData());
		
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
		if ($strTemplate == 'be_main' && $this->Input->get('table') == 'tl_avisota_recipient_import')
		{
			$strContent = str_replace('<form', '<form enctype="multipart/form-data"', $strContent);
		}
		return $strContent;
	}
}
?>