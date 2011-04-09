<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Table tl_avisota_newsletter_draft
 */
$GLOBALS['TL_DCA']['tl_avisota_newsletter_draft'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_avisota_newsletter_draft_content'),
		'switchToEdit'                => true,
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'flag'                    => 11,
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
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['edit'],
				'href'                => 'table=tl_avisota_newsletter_draft_content',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'preview' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['preview'],
				'href'                => 'key=preview',
				'icon'                => 'system/modules/Avisota/html/preview.png'
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addFile'),
		'default'                     => '{newsletter_legend},title,alias;{attachment_legend},addFile;{template_legend:hide},template_html,template_plain',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addFile'                     => 'files'
	),
	
	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_avisota_newsletter_draft', 'generateAlias')
			)
		),
		'addFile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['addFile'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'files' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['files'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true)
		),
		'template_html' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['template_html'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('mail_html_'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'template_plain' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_newsletter_draft']['template_plain'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('mail_plain_'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		)
	)
);


class tl_avisota_newsletter_draft extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	
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

		$objAlias = $this->Database->prepare("SELECT id FROM tl_avisota_newsletter_draft WHERE alias=?")
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