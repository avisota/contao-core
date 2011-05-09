<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class AvisotaHelper
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaHelper extends System
{
	protected static $objInstance;
	
	public static function getInstance()
	{
		if (!self::$objInstance)
		{
			self::$objInstance = new AvisotaHelper();
		}
		return self::$objInstance;
	}
	
	public static function callHook($strName, $arrArgs)
	{
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS'][$strName]) && is_array($GLOBALS['TL_HOOKS'][$strName]))
		{
			foreach ($GLOBALS['TL_HOOKS'][$strName] as $callback)
			{
				$this->import($callback[0]);
				call_user_func_array(array($this->$callback[0], $callback[1]), $arrArgs);
			}
		}
	}
	
	public static function callHookReturn($strName, $arrArgs)
	{
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS'][$strName]) && is_array($GLOBALS['TL_HOOKS'][$strName]))
		{
			foreach ($GLOBALS['TL_HOOKS'][$strName] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = call_user_func_array(array($this->$callback[0], $callback[1]), $arrArgs);
				if ($strBuffer)
				{
					return $strBuffer;
				}
			}
		}
		return false;
	}
	
	
	/**
	 * Generate a newsletter object from a queue.
	 */
	public static function generateNewsletterFromQueue($intEntry)
	{
		throw new Exception('Missing implementation AvisotaHelper::generateNewsletterFromQueue(..)');
	}
	
	
	protected function __construct()
	{
		// singleton
	}
}

?>