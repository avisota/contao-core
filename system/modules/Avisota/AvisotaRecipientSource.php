<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class AvisotaRecipientSource
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
interface AvisotaRecipientSource
{
	/**
	 * List all recipient lists.
	 * 
	 * @return array
	 * Assoziative array ID=>Name of the recipient lists.
	 */
	public function getLists();
	
	
	/**
	 * List all recipients.
	 * 
	 * @param mixed $varList
	 * ID of the recipient list.
	 * 
	 * @return array
	 * List of all recipient emails.
	 */
	public function getRecipients($varList = null);
	
	
	/**
	 * Get the recipient details.
	 * 
	 * @param string $strEmail
	 * @return array
	 * Associative array of recipient details.
	 */
	public function getRecipientDetails($strEmail, $varList = null);
}

?>