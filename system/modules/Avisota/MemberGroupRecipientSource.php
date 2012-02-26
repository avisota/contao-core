<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class MemberGroupRecipientSource
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class MemberGroupRecipientSource extends Controller implements AvisotaRecipientSource
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
		$arrLists = array();
		$objMemberGroup = $this->Database->execute("
				SELECT
					*
				FROM
					tl_member_group
				ORDER BY
					name");
		while ($objMemberGroup->next())
		{
			$arrLists[$objMemberGroup->id] = $objMemberGroup->name;
		}
		return $arrLists;
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
					tl_member
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
			return $objRecipient->fetchAssoc();
		}
		
		return array('email'=>$strEmail);
	}
}
