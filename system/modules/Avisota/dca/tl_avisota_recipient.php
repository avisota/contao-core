<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Table tl_avisota_recipient
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_avisota_recipient_list',
		'switchToEdit'                => true,
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('email'),
			'panelLayout'             => 'filter;sort,search,limit',
			'headerFields'            => array('title'),
			'child_record_callback'   => array('tl_avisota_recipient', 'addRecipient'),
			'child_record_class'      => 'no_padding'
		),
		'global_operations' => array
		(
			'import' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['import'],
				'href'                => 'table=tl_avisota_recipient_import',
				'class'               => 'header_css_import',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
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
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_avisota_recipient', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{recipient_legend},email;{personals_legend},salutation,title,firstname,lastname,gender,confirmed',
	),

	// Fields
	'fields' => array
	(
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'importable'=>true)
		),
		'salutation' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['salutation'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'select',
			'options'                 => array_combine($GLOBALS['TL_CONFIG']['avisota_salutations'], $GLOBALS['TL_CONFIG']['avisota_salutations']),
			'eval'                    => array('maxlength'=>255, 'includeBlankOption'=>true, 'importable'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50')
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'importable'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50')
		),
		'firstname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['firstname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'importable'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50')
		),
		'lastname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['lastname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'importable'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50')
		),
		'gender' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['gender'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => array('male', 'female'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('includeBlankOption'=>true, 'importable'=>true, 'tl_class'=>'clr')
		),
		'confirmed' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['confirmed'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox'
		),
		'token' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['token']
		),
		'addedOn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedOn'],
			'default'                 => time(),
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'eval'                    => array('doNotShow'=>true, 'doNotCopy'=>true)
		),
		'addedBy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedBy'],
			'default'                 => $this->User->id,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('doNotShow'=>true, 'doNotCopy'=>true)
		)		
	)
);

class tl_avisota_recipient extends Backend
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
	 * Add the recipient row.
	 * 
	 * @param array
	 */
	public function addRecipient($arrRow)
	{
		$icon = $arrRow['confirmed'] ? 'visible' : 'invisible';

		$label = trim($arrRow['firstname'] . ' ' . $arrRow['lastname']);
		if (strlen($label))
		{
			$label .= ' &lt;' . $arrRow['email'] . '&gt;';
		}
		else
		{
			$label = $arrRow['email'];
		}
		
		$label .= ' <span style="color:#b3b3b3; padding-left:3px;">(';
		$label .= sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient']['addedOn'][2], $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['addedOn']));
		if ($arrRow['addedBy'] > 0)
		{
			$objUser = $this->Database->prepare("SELECT * FROM tl_user WHERE id=?")
				->execute($arrRow['addedBy']);
			$label .= sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient']['addedBy'][2], $objUser->next() ? $objUser->name : $GLOBALS['TL_LANG']['tl_avisota_recipient']['addedBy'][3]);
		}
		$label .= ')</span>';
		
		return sprintf('<div class="list_icon" style="background-image:url(\'system/themes/%s/images/%s.gif\');">%s</div>', $this->getTheme(), $icon, $label);
	}
	
	
	/**
	 * Check permissions to edit table tl_avisota_recipient
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->avisota_lists) || count($this->User->avisota_lists) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->avisota_lists;
		}

		$id = strlen($this->Input->get('id')) ? $this->Input->get('id') : CURRENT_ID;

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
				if (!strlen($this->Input->get('pid')) || !in_array($this->Input->get('pid'), $root))
				{
					$this->log('Not enough permissions to create newsletters recipients in list ID "'.$this->Input->get('pid').'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'edit':
			case 'show':
			case 'copy':
			case 'delete':
			case 'toggle':
				$objRecipient = $this->Database->prepare("SELECT pid FROM tl_avisota_recipient WHERE id=?")
											   ->limit(1)
											   ->execute($id);

				if ($objRecipient->numRows < 1)
				{
					$this->log('Invalid newsletter recipient ID "'.$id.'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!in_array($objRecipient->pid, $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' recipient ID "'.$id.'" of recipient list ID "'.$objRecipient->pid.'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'select':
			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				if (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access recipient list ID "'.$id.'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objRecipient = $this->Database->prepare("SELECT id FROM tl_avisota_recipient WHERE pid=?")
											 ->execute($id);

				if ($objRecipient->numRows < 1)
				{
					$this->log('Invalid newsletter recipient ID "'.$id.'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objRecipient->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Invalid command "'.$this->Input->get('act').'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access newsletter recipient ID "'.$id.'"', 'tl_avisota_recipient checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_avisota_recipient::confirmed', 'alexf'))
		{
			return '';
		}
		
		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['confirmed']?'':'1');

		if (!$row['confirmed'])
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_avisota_recipient::confirmed', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish newsletter recipient ID "'.$intId.'"', 'tl_avisota_recipient toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
		
		$this->createInitialVersion('tl_avisota_recipient', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['confirmed']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields']['confirmed']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_avisota_recipient SET tstamp=". time() .", confirmed='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_avisota_recipient', $intId);
	}
}

?>