<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Table tl_avisota_recipient_source
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_source'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onsubmit_callback'           => array(array('tl_avisota_recipient_source', 'onsubmit_callback'))
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'flag'                    => 11,
			'fields'                  => array('sorting'),
			'disableGrouping'         => true,
			'root'                    => 0
		),
		'label' => array
		(
			'fields'                  => array('title', 'type'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">(%s)</span>'
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
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_avisota_recipient_source', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'move' => array
			(
				'button_callback'     => array('tl_avisota_recipient_source', 'move_button_callback')
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type'),
		'default'                     => '{source_legend},type',
		'integrated'                  => '{source_legend},title;{integrated_legend},lists,detail_source;{expert_legend},disable',
		'csv_file'                    => '{source_legend},title;{csv_file_legend},csv_file_src,csv_column_assignment;{expert_legend},disable'
	),

	// Fields
	'fields' => array
	(
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['type'],
			'inputType'               => 'select',
			'options'                 => array_keys($GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE']),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source'],
			'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true, 'includeBlankOption'=>true)
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['title'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'lists' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['lists'],
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_avisota_recipient_list.title',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true)
		),
		'detail_source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['detail_source'],
			'default'                 => 'integrated_details',
			'inputType'               => 'select',
			'options'                 => array('integrated_details', 'member_details', 'integrated_member_details'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source'],
			'eval'                    => array('mandatory'=>true)
		),
		'csv_file_src' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csv_file_src'],
			'inputType'               => 'fileTree',
			'eval'                    => array('mandatory'=>true, 'files'=>true, 'filesOnly'=>true, 'extensions'=>'csv', 'fieldType'=>'radio')
		),
		'csv_column_assignment' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['csv_column_assignment'],
			'inputType'               => 'columnAssignmentWizard',
			'eval'                    => array('mandatory'=>true)
		),
		'disable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['disable'],
			'inputType'               => 'checkbox'
		)
	)
);

class tl_avisota_recipient_source extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	
	public function onsubmit_callback(DataContainer $dc)
	{
		$objSource = $this->Database->execute("SELECT MAX(sorting) as sorting FROM tl_avisota_recipient_source");
		$this->Database->prepare("UPDATE tl_avisota_recipient_source SET sorting=? WHERE id=?")
			->execute($objSource->next() && $objSource->sorting ? $objSource->sorting * 2 : 128, $dc->id);
	}
	
	
	/**
	 * Check permissions to edit table tl_avisota_recipient_source
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// TODO
	}
	
	
	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_avisota_recipient_source::disable', 'alexf'))
		{
			return '';
		}
		
		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['disable']?'':'1');

		if ($row['disable'])
		{
			$icon = 'invisible.gif';
		}		

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
	
	
	/**
	 * Toggle the visibility of an element
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();
	
		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_avisota_recipient_source::disable', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish newsletter recipient source ID "'.$intId.'"', 'tl_avisota_recipient_source toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
		
		$this->createInitialVersion('tl_avisota_recipient_source', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['disable']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['disable']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_avisota_recipient_source SET tstamp=". time() .", disable='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_avisota_recipient_source', $intId);
	}


	public function move_button_callback($arrRow, $href, $label, $title, $icon, $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext)
	{
		$arrDirections = array('up', 'down');
		$href = '&amp;act=move';
		$return = '';
		
		foreach ($arrDirections as $dir)
		{
			$label = strlen($GLOBALS['TL_LANG'][$strTable][$dir][0]) ? $GLOBALS['TL_LANG'][$strTable][$dir][0] : $dir;
			$title = sprintf(strlen($GLOBALS['TL_LANG'][$strTable][$dir][1]) ? $GLOBALS['TL_LANG'][$strTable][$dir][1] : $dir, $arrRow['id']);
			
			$objSource = $this->Database->prepare("SELECT * FROM tl_avisota_recipient_source WHERE " . ($dir == 'up' ? "sorting<?" : "sorting>?") . " ORDER BY sorting " . ($dir == 'up' ? "DESC" : "ASC"))
				->limit(1)
				->execute($arrRow['sorting']);
			if ($objSource->next())
			{
				$return .= ' <a href="'.$this->addToUrl($href.'&amp;id='.$arrRow['id']).'&amp;sid='.intval($objSource->id).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($dir.'.gif', $label).'</a> ';
			}
			else
			{
				$return .= ' '.$this->generateImage('system/modules/Avisota/html/'.$dir.'_.gif', $label);
			}
		}

		return trim($return);
	}
}

?>