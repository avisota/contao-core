<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class CSVFileRecipientSource
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class CSVFileRecipientSource extends Controller implements AvisotaRecipientSource
{
	private $arrConfig;
	
	public function __construct($arrConfig)
	{
		$this->arrConfig = $arrConfig;
	}
	
	
	/**
	 * List all recipient lists.
	 * 
	 * @return array
	 * Assoziative array ID=>Name of the recipient lists or <strong>null</strong> if this source does not have lists.
	 */
	public function getLists()
	{
		return array($this->arrConfig['csv_file_src']=>$this->arrConfig['csv_file_src']);
	}
	
	
	/**
	 * List all recipients.
	 * 
	 * @param mixed $varList
	 * ID of the recipient list.
	 * 
	 * @return array
	 * List of all recipient emails.
	 */
	public function getRecipients($varList = null)
	{
		$strFile = $this->arrConfig['csv_file_src'];
		if (file_exists(TL_ROOT . '/' . $strFile))
		{
			$objFile = new File($strFile);
			
		}
		return array();
	}
	
	
	/**
	 * Get the recipient details.
	 * 
	 * @param string $strEmail
	 * @return array
	 * Associative array of recipient details.
	 */
	public function getRecipientDetails($strEmail, $varList = null)
	{
		
	}
}
