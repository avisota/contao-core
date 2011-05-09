<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Table tl_avisota_newsletter
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_avisota_newsletter_category',
		'ctable'                      => array('tl_avisota_newsletter_content'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback'             => array
		(
			array('tl_avisota_newsletter', 'onload')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('tstamp'),
			'panelLayout'             => 'filter;sort,search,limit',
			'headerFields'            => array('title', 'jumpTo', 'unsubscribePage', 'tstamp', 'useSMTP', 'senderName', 'sender'),
			'child_record_callback'   => array('tl_avisota_newsletter', 'addNewsletter'),
			'child_record_class'      => 'no_padding'
		),
		'global_operations' => array
		(
			'createFromDraft' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['create_from_draft'],
				'href'                => 'table=tl_avisota_newsletter_create_from_draft',
				'class'               => 'header_new',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="d"'
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['edit'],
				'href'                => 'table=tl_avisota_newsletter_content',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'preview' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview'],
				'href'                => 'key=preview',
				'icon'                => 'system/modules/Avisota/html/preview.png'
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addFile'),
		'default'                     => '{newsletter_legend},subject,alias;{attachment_legend},addFile',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addFile'                     => 'files'
	),
	
	// Fields
	'fields' => array
	(
		'tstamp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['tstamp'],
			'sorting'                 => true,
			'filter'                  => true,
			'flag'                    => 8
		),
		'subject' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['subject'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_avisota_newsletter', 'generateAlias')
			)
		),
		'addFile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['addFile'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'files' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['files'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true)
		),
		'recipients' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipients'],
			'inputType'               => 'checkbox',
			'options_callback'        => array('AvisotaBackend', 'getRecipients'),
			'eval'                    => array('multiple'=>true, 'tl_class'=>'clr')
		),
		'template_html' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_html'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('mail_html_'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'template_plain' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_plain'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('mail_plain_'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		)
	)
);


class tl_avisota_newsletter extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	
	public function onload(DataContainer $dc)
	{
		$objCategory = $this->Database->prepare("SELECT c.* FROM tl_avisota_newsletter_category c INNER JOIN tl_avisota_newsletter n ON n.pid=c.id WHERE n.id=?")
			->execute($dc->id);
		if (!$objCategory->force_recipients)
		{
			$GLOBALS['TL_DCA']['tl_avisota_newsletter']['palettes']['default'] .= ';{recipient_legend},recipients';
			
			if (!strlen($objCategory->recipients))
			{
				$GLOBALS['TL_DCA']['tl_avisota_newsletter']['fields']['recipients']['eval']['mandatory'] = true;
			}
		}
		if (!$objCategory->force_template)
		{
			$GLOBALS['TL_DCA']['tl_avisota_newsletter']['palettes']['default'] .= ';{template_legend:hide},template_html,template_plain';
			
			if (!$objCategory->template_html)
			{
				$GLOBALS['TL_DCA']['tl_avisota_newsletter']['fields']['template_html']['eval']['mandatory'] = true;
				$GLOBALS['TL_DCA']['tl_avisota_newsletter']['fields']['template_html']['eval']['includeBlankOption'] = false;
			}
			if (!$objCategory->template_plain)
			{
				$GLOBALS['TL_DCA']['tl_avisota_newsletter']['fields']['template_plain']['eval']['mandatory'] = true;
				$GLOBALS['TL_DCA']['tl_avisota_newsletter']['fields']['template_plain']['eval']['includeBlankOption'] = false;
			}
		}
	}
	
	
	/**
	 * Add the recipient row.
	 * 
	 * @param array
	 */
	public function addNewsletter($arrRow)
	{
		$label = $arrRow['subject'];
		
		return $label;
	}

	
	/**
	 * Autogenerate a news alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue))
		{
			$autoAlias = true;
			$varValue = standardize($dc->activeRecord->subject);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_avisota_newsletter WHERE alias=?")
								   ->execute($varValue);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}
}
?>
