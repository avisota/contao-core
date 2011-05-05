<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class IntegratedAvisotaRecipientSource
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class IntegratedAvisotaRecipientSource extends Controller implements AvisotaRecipientSource
{
	private $arrConfig;
	
	public function __construct($arrConfig)
	{
		$this->import('Database');
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
		if (is_string($this->arrConfig['lists']))
		{
			$this->arrConfig['lists'] = deserialize($this->arrConfig['lists'], true);
		}
		if (count($this->arrConfig['lists']))
		{
			$strIds = implode(',', $this->arrConfig['lists']);
			$arrLists = array();
			$objList = $this->Database->execute("SELECT * FROM tl_avisota_recipient_list WHERE id IN ($strIds) ORDER BY title");
			while ($objList->next())
			{
				$arrLists[$objList->id] = $objList->title;
			}
			return $arrLists;
		}
		return array();
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
		$objRecipient = $this->Database->prepare("
				SELECT
					email
				FROM
					tl_avisota_recipient
				WHERE
					pid=?")
			->execute($varList);
		return $objRecipient->fetchEach('email');
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
		$arrRecipient = array('email'=>$strEmail);
		
		if (	$this->detail_source == 'member_details'
			||	$this->detail_source == 'integrated_member_details')
		{
			$objRecipient = $this->Database->prepare("
					SELECT
						*
					FROM
						tl_member
					WHERE
						email=?")
				->execute($strEmail);
			if ($objRecipient->next())
			{
				$arrRecipient = array_merge($arrRecipient, $objRecipient->fetchAssoc());
			}
		}
		
		if (	$this->detail_source == 'integrated_details'
			||	$this->detail_source == 'integrated_member_details')
		{
			$objRecipient = $this->Database->prepare("
					SELECT
						*
					FROM
						tl_avisota_recipient
					WHERE
						email=?
					AND	pid=?")
				->execute($strEmail, $varList);
			if ($objRecipient->next())
			{
				$arrRecipient = array_merge($arrRecipient, $objRecipient->fetchAssoc());
			}
		}
		
		return $arrRecipient;
	}
}

?>