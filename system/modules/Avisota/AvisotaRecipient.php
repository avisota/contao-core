<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
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
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


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