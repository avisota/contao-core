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
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 0,
			'flag'                    => 11,
			'fields'                  => array('title')
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
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
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
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type'),
		'default'                     => '{recipient_source_legend},type,title,disable',
		'integrated'                  => '{recipient_source_legend},type,title,detail_source,disable',
		'csv_file'                    => '{recipient_source_legend},type,title,csv_file_src,csv_column_assignment,disable'
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
			'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true)
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_source']['title'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
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
		
		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['confirmed']?'':'1');

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
}

?>