<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @filesource
 */


/**
 * Class ColumnAssignmentWizard
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class ColumnAssignmentWizard extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = false;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'value':
				$this->varValue = deserialize($varValue);
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}

	
	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$this->import('Database');
		$this->loadLanguageFile('tl_avisota_recipient');
		
		$arrButtons = array('copy', 'up', 'down', 'delete');
		$strCommand = 'cmd_' . $this->strField;

		// Change the order
		if ($this->Input->get($strCommand) && is_numeric($this->Input->get('cid')) && $this->Input->get('id') == $this->currentRecord)
		{
			switch ($this->Input->get($strCommand))
			{
				case 'copy':
					$this->varValue = array_duplicate($this->varValue, $this->Input->get('cid'));
					break;

				case 'up':
					$this->varValue = array_move_up($this->varValue, $this->Input->get('cid'));
					break;

				case 'down':
					$this->varValue = array_move_down($this->varValue, $this->Input->get('cid'));
					break;

				case 'delete':
					$this->varValue = array_delete($this->varValue, $this->Input->get('cid'));
					break;
			}
		}

		$objRow = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE id=?")
								 ->limit(1)
								 ->execute($this->currentRecord);

		$strField = $this->strField;
		$arrModules = deserialize($objRow->$strField);

		// Get new value
		if ($this->Input->post('FORM_SUBMIT') == $this->strTable)
		{
			$this->varValue = $this->Input->post($this->strId);
		}

		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || !$this->varValue[0])
		{
			$this->varValue = array('');
		}

		// Save the value
		if ($this->Input->get($strCommand) || $this->Input->post('FORM_SUBMIT') == $this->strTable)
		{
			$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);

			// Reload the page
			if (is_numeric($this->Input->get('cid')) && $this->Input->get('id') == $this->currentRecord)
			{
				$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', $this->Environment->request)));
			}
		}
		
		$return = "<script type='text/javascript'>
	/**
	 * Column source wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	if (!Backend.columnAssignmentWizard) Backend.columnAssignmentWizard = function(el, command, id)
	{
		var table = $(id);
		var tbody = table.getFirst().getNext();
		var parent = $(el).getParent('tr');
		var rows = tbody.getChildren();

		Backend.getScrollOffset();

		switch (command)
		{
			case 'copy':
				var tr = new Element('tr');
				var childs = parent.getChildren();

				for (var i=0; i<childs.length; i++)
				{
					var next = childs[i].clone(true).injectInside(tr);
					next.getFirst().value = childs[i].getFirst().value;
				}

				tr.injectAfter(parent);
				break;

			case 'up':
				parent.getPrevious() ? parent.injectBefore(parent.getPrevious()) : parent.injectInside(tbody);
				break;

			case 'down':
				parent.getNext() ? parent.injectAfter(parent.getNext()) : parent.injectBefore(tbody.getFirst());
				break;

			case 'delete':
				(rows.length > 1) ? parent.destroy() : null;
				break;
		}

		rows = tbody.getChildren();

		for (var i=0; i<rows.length; i++)
		{
			var childs = rows[i].getChildren();

			for (var j=0; j<childs.length; j++)
			{
				var first = childs[j].getFirst();

				if (first.type == 'text' || first.type == 'select-one')
				{
					first.name = first.name.replace(/\[[0-9]+\]/ig, '[' + i + ']');
				}
			}
		}
	}; </script>";

		// Add label and return wizard
		$return .= '<table cellspacing="0" cellpadding="0" id="ctrl_'.$this->strId.'" class="tl_modulewizard" summary="Module wizard">
  <thead>
  <tr>
    <th>'.$GLOBALS['TL_LANG'][$this->strTable]['label_column'].'</th>
    <th>'.$GLOBALS['TL_LANG'][$this->strTable]['label_assignment'].'</th>
    <th>&nbsp;</th>
  </tr>
  </thead>
  <tbody>';

		// Add input fields
		for ($i=0; $i<count($this->varValue); $i++)
		{
			// Add modules
			$return .= '
  <tr>
    <td><select name="'.$this->strId.'['.$i.'][column]" class="tl_select" onfocus="Backend.getScrollOffset();" style="width: 50px;">'.$this->generateColumnOptions($this->varValue[$i]['column']).'</select></td>
    <td><select name="'.$this->strId.'['.$i.'][assignment]" class="tl_select" onfocus="Backend.getScrollOffset();" style="width: 160px;">'.$this->generateAssignmentOptions($this->varValue[$i]['assignment']).'</select></td>
    <td>';

			foreach ($arrButtons as $button)
			{
				$return .= '<a href="'.$this->addToUrl('&amp;'.$strCommand.'='.$button.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['wz_'.$button]).'" onclick="Backend.columnAssignmentWizard(this, \''.$button.'\',  \'ctrl_'.$this->strId.'\'); return false;">'.$this->generateImage($button.'.gif', $GLOBALS['TL_LANG'][$this->strTable]['wz_'.$button], 'class="tl_listwizard_img"').'</a> ';
			}

			$return .= '</td>
  </tr>';
		}

		return $return.'
  </tbody>
  </table>';
	}
	
	
	protected function generateColumnOptions($intColumn)
	{
		$strHtml = '';
		for ($i=1; $i<=50; $i++)
		{
			$strHtml .= sprintf('<option value="%d"%s>%d</option>', $i, ($i==$intColumn ? ' selected="selected"' : ''), $i);
		}
		return $strHtml;
	}
	
	
	protected function generateAssignmentOptions($strAssignment)
	{
		$strHtml = '';
		foreach (array('email', 'firstname', 'lastname', 'gender', 'addedOn') as $strOption)
		{
			$strHtml .= sprintf('<option value="%s"%s>%s</option>', $strOption, ($strOption==$strAssignment ? ' selected="selected"' : ''), $GLOBALS['TL_LANG']['tl_avisota_recipient'][$strOption][0]);
		}
		return $strHtml;
	}
	
	
	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		parent::validate();
		
		$arrColumns = array();
		for ($i=1; $i<=50; $i++)
		{
			$arrColumns[$i] = false;
		}
		foreach (array('email', 'firstname', 'lastname', 'gender', 'addedOn') as $strOption)
		{
			$arrColumns[$strOption] = false;
		}
		
		$blnDubColumn = false;
		$blnEmail = false;
		foreach ($this->varValue as $v)
		{
			if ($arrColumns[$v['column']])
			{
				$blnDubColumn = true;
			}
			$arrColumns[$v['column']] = true;
			
			if ($arrColumns[$v['assignment']])
			{
				$blnDubColumn = true;
			}
			$arrColumns[$v['assignment']] = true;
			
			if ($v['assignment'] == 'email')
			{
				$blnEmail = true;
			}
		}
		if ($blnDubColumn)
		{
			$this->addError($GLOBALS['TL_LANG']['tl_avisota_recipient_source']['duplicated_column']);
		}
		if (!$blnEmail)
		{
			$this->addError($GLOBALS['TL_LANG']['tl_avisota_recipient_source']['missing_email_column']);
		}
	}
}

?>