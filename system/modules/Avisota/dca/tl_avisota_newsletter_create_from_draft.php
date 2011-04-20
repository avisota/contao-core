<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright



/**
 * Table tl_avisota_newsletter_create_from_draft
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter_create_from_draft'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Memory',
		'closed'                      => true,
		'onload_callback'           => array
		(
			array('tl_avisota_newsletter_create_from_draft', 'onload_callback'),
		),
		'onsubmit_callback'           => array
		(
			array('tl_avisota_newsletter_create_from_draft', 'onsubmit_callback'),
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{create_legend},category,subject,draft'
	),
	
	// Fields
	'fields' => array
	(
		'category' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_create_from_draft']['category'],
			'inputType'               => 'select',
			'foreignKey'              => 'tl_avisota_newsletter_category.title',
			'eval'                    => array('mandatory'=>true)
		),
		'subject' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_create_from_draft']['subject'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true)
		),
		'draft' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_create_from_draft']['draft'],
			'inputType'               => 'radio',
			'foreignKey'              => 'tl_avisota_newsletter_draft.title',
			'eval'                    => array('mandatory'=>true)
		)
	)
);

class tl_avisota_newsletter_create_from_draft extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	
	/**
	 * 
	 * 
	 * @param DataContainer $dc
	 */
	public function onload_callback(DataContainer $dc)
	{
		$dc->setData('category', $this->Input->get('id'));
	}
	
	
	/**
	 * 
	 * 
	 * @param DataContainer $dc
	 */
	public function onsubmit_callback(DataContainer $dc)
	{
		$intCategory = $dc->getData('category');
		$strSubject = $dc->getData('subject');
		$intDraft = $dc->getData('draft');
		
		$objNewsletterDraft = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_draft WHERE id=?")
			->execute($intDraft);
		if ($objNewsletterDraft->next())
		{
			$arrRow = $objNewsletterDraft->row();
			// remove unwanted fields
			unset($arrRow['id'], $arrRow['tstamp'], $arrRow['title'], $arrRow['description'], $arrRow['alias']);
			// set pid
			$arrRow['pid'] = $intCategory;
			// set subject
			$arrRow['subject'] = $strSubject;
			
			// call hook
			AvisotaHelper::callHook('prepareNewsletterCreateFromDraft', array(&$arrRow));
			
			$strValue = '';
			for ($i=0; $i<count($arrRow); $i++)
			{
				if ($i>0)
				{
					$strValue .= ',';
				}
				$strValue .= '?';
			}
			
			$objNewsletter = $this->Database->prepare("INSERT INTO tl_avisota_newsletter (" . implode(",", array_keys($arrRow)) . ") VALUES ($strValue)")
				->execute($arrRow);
			$intId = $objNewsletter->insertId;
			
			$objContent = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_draft_content WHERE pid=?")
				->execute($intDraft);
			
			while ($objContent->next())
			{
				$arrRow = $objContent->row();
				// remove unwanted fields
				unset($arrRow['id'], $arrRow['tstamp']);
				// set pid
				$arrRow['pid'] = $intId;
			
				// call hook
				AvisotaHelper::callHook('prepareNewsletterContentCreateFromDraft', array(&$arrRow));
				
				// prevent pid changing
				$arrRow['pid'] = $intId;
				
				$strValue = '';
				for ($i=0; $i<count($arrRow); $i++)
				{
					if ($i>0)
					{
						$strValue .= ',';
					}
					$strValue .= '?';
				}
				
				$objNewsletter = $this->Database->prepare("INSERT INTO tl_avisota_newsletter_content (" . implode(",", array_keys($arrRow)) . ") VALUES ($strValue)")
					->execute($arrRow);
			}
			
			$_SESSION['TL_INFO'][] = $GLOBALS['TL_LANG']['tl_avisota_newsletter_create_from_draft']['created'];
			$this->redirect('contao/main.php?do=avisota_newsletter&table=tl_avisota_newsletter_content&id='.$intId);
		}
	}
}

?>