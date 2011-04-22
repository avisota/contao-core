<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Table tl_avisota_newsletter_category
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter_category'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_avisota_newsletter'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback'             => array
		(
			array('tl_avisota_newsletter_category', 'onload')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'flag'                    => 1,
			'fields'                  => array('title'),
			'panelLayout'             => 'search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
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
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['edit'],
				'href'                => 'table=tl_avisota_newsletter',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{category_legend},title,alias;{expert_legend:hide},areas;{recipient_legend:hide},recipients,force_recipients;{template_legend:hide},template_html,template_plain,force_template,stylesheets',
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_avisota_newsletter_category', 'generateAlias')
			)
		),
		'areas' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['areas'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'rgxp'=>'extnd', 'nospace'=>true)
		),
		'recipients' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipients'],
			'inputType'               => 'checkbox',
			'options_callback'        => array('AvisotaBackend', 'getRecipients'),
			'eval'                    => array('multiple'=>true, 'tl_class'=>'clr')
		),
		'force_recipients' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['force_recipients'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'clr m12')
		),
		'template_html' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_html'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('mail_html_'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'template_plain' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_plain'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('mail_plain_'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'force_template' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['force_template'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'clr m12')
		),
		'stylesheets' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['stylesheets'],
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_avisota_newsletter_category', 'getStylesheets'),
			'eval'                    => array('tl_class'=>'clr', 'multiple'=>true)
		)
	)
);

class tl_avisota_newsletter_category extends Backend
{
	public function onload(DataContainer $dc)
	{
		$objCategory = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_category WHERE id=?")
			->execute($dc->id);
		if ($objCategory->force_recipients)
		{
			$GLOBALS['TL_DCA']['tl_avisota_newsletter_category']['fields']['recipients']['eval']['mandatory'] = true;
		}
		if ($objCategory->force_template)
		{
			$GLOBALS['TL_DCA']['tl_avisota_newsletter_category']['fields']['template_html']['eval']['mandatory'] = true;
			$GLOBALS['TL_DCA']['tl_avisota_newsletter_category']['fields']['template_plain']['eval']['mandatory'] = true;
		}
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
			$varValue = standardize($dc->activeRecord->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_avisota_newsletter_category WHERE alias=?")
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
	
	
	public function getStylesheets($dc)
	{
		$arrStylesheets = array();
		
		$objTheme = $this->Database->execute("SELECT * FROM tl_theme ORDER BY name");
		while ($objTheme->next())
		{
			$objStylesheet = $this->Database->prepare("SELECT * FROM tl_style_sheet WHERE pid=? ORDER BY name")
				->execute($objTheme->id);
			while ($objStylesheet->next())
			{
				$arrStylesheets[$objTheme->name][TL_ROOT . '/' . $objStylesheet->name . '.css'] = $objStylesheet->name;
			}
			
			if (in_array('layout_additional_sources', $this->Config->getActiveModules()))
			{
				$arrAdditionalSource = array();
				$objAdditionalSource = $this->Database->prepare("SELECT * FROM tl_additional_source WHERE (type=? OR type=?) AND pid=? ORDER BY sorting")
				   ->execute('css_file', 'css_url', $objTheme->id);
				while ($objAdditionalSource->next())
				{
					$strFile = $objAdditionalSource->$strType;
					$arrStylesheets[$objTheme->name][$strFile] = $strFile;
				}
			}
		}
		
		return $arrStylesheets;
	}
}

?>