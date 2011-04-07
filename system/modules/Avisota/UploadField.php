<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class UploadField
 *
 * Provide methods to handle upload fields.
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class UploadField extends Widget
{
	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';

	/**
	 * Contents
	 * @var array
	 */
	protected $arrContents = array();


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		if (isset($_FILES[$this->strName]) && is_uploaded_file($_FILES[$this->strName]['tmp_name']))
		{
			$varValue = $_FILES[$this->strName];
		}
		else
		{
			$varValue = false;
		}
		
		if ($this->mandatory && !$varValue)
		{
			if ($this->strLabel == '')
			{
				$this->addError($GLOBALS['TL_LANG']['ERR']['mdtryNoLabel']);
			}
			else
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory'], $this->strLabel));
			}
		}

		if ($this->hasErrors())
		{
			$this->class = 'error';
		}

		$this->varValue = $varValue;
	}
	

	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		return sprintf('<input type="file" name="%s" id="ctrl_%s" class="tl_upload%s" %s onfocus="Backend.getScrollOffset();" />',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes());
	}
}

?>