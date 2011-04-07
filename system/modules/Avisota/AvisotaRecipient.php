<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class AvisotaRecipient
 *
 * A recipient object
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaRecipient
{
	/**
	 * Get a dummy recipient based on be user data.
	 */
	public static function dummy()
	{
		$objUser = BackendUser::getInstance();
		$objUser->authenticate();
		
		return new AvisotaRecipient($objUser->id, $objUser->email, 0);
	}
	
	
	/**
	 * The identification of this recipient.
	 * 
	 * @var mixed
	 */
	protected $varId;
	
	
	/**
	 * The email of this recipient.
	 * 
	 * @var string
	 */
	protected $strEmail;
	
	
	/**
	 * The source of this recipient.
	 * 
	 * @var int
	 */
	protected $intSource;
	
	
	/**
	 * The personal data of this recipient.
	 * 
	 * @var array
	 */
	protected $arrData;
	

	/**
	 * Create a new recipient object.
	 *
	 * @param mixed $varId
	 * @param string $strEmail
	 * @param int $intSource
	 * @param array $arrData
	 */
	public function __construct($varId, $strEmail, $intSource, $arrData = null)
	{
		$this->id = $varId;
		$this->strEmail = $strEmail;
		$this->intSource = $intSource;
		if (empty($arrData))
		{
			$this->arrData = array();
		}
		else
		{
			$this->arrData = $arrData;
		}
	}
	
	
	public function __get($k)
	{
		switch ($k)
		{
		case 'id':
			return $this->id;
			
		case 'email':
			return $this->email;
			
		case 'source':
			return $this->source;
		
		case 'personal':
			return count($this->arrData) > 0;
		
		default:
			if (isset($this->arrData[$k]))
			{
				return $this->arrData[$k];
			}
		}
		return '';
	}
}
?>