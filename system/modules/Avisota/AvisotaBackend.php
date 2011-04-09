<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class AvisotaBackend
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBackend extends Backend
{
	public function renderNewsletter()
	{
		$this->render(false);
	}
	
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
		
		$objNewsletter = new AvisotaNewsletter($varId, $blnDraft);
		
		if (!$objNewsletter)
		{
			$this->redirect('contao/main.php?act=tl_error');
		}
		
		// generate the preview
		switch ($strMode)
		{
		case NL_HTML:
			header('Content-Type: text/html; charset=utf-8');
			echo $objNewsletter->generateHtml(AvisotaRecipient::dummy($blnPersonalized));
			exit(0);
			
		case NL_PLAIN:
			header('Content-Type: text/plain; charset=utf-8');
			echo $objNewsletter->generatePlain(AvisotaRecipient::dummy($blnPersonalized));
			exit(0);
		}
	}
	
	public function previewDraft()
	{
		// find the newsletter
		$varId = $this->Input->get('id');
		
		$objNewsletter = new AvisotaNewsletter($varId, true);
		
		if (!$objNewsletter)
		{
			$this->redirect('contao/main.php?act=tl_error');
		}
		
		$objTemplate = new BackendTemplate('be_avisota_preview_draft');
		$objTemplate->setData($objNewsletter->getData());
		
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