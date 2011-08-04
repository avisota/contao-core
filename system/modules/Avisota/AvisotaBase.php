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
 * Class AvisotaBase
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaBase extends Controller
{
	/**
	 * Singleton instance.
	 *
	 * @var AvisotaBase
	 */
	private static $objInstance = null;


	/**
	 * Get singleton instance.
	 */
	public static function getInstance()
	{
		if (self::$objInstance === null)
		{
			self::$objInstance = new AvisotaBase();
		}
		return self::$objInstance;
	}


	/**
	 * Singleton
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('AvisotaStatic', 'Static');
		$this->import('BackendUser', 'User');
		$this->import('Database');
		$this->import('DomainLink');
		$this->User->authenticate();
	}


	public function getViewOnlinePage($objCategory = null, $arrRecipient = null)
	{
		if (is_null($objCategory))
		{
			$objCategory = $this->Static->getCategory();
		}

		if (is_null($arrRecipient))
		{
			$arrRecipient = $this->Static->getRecipient();
		}

		if ($arrRecipient && preg_match('#^list:(\d+)$#', $arrRecipient['outbox_source'], $arrMatch))
		{
			// the dummy list, used on preview
			if ($arrMatch[1] > 0)
			{
				$objRecipientList = $this->Database->prepare("
						SELECT
							*
						FROM
							`tl_avisota_recipient_list`
						WHERE
							`id`=?")
					->execute($arrMatch[1]);
				if ($objRecipientList->next())
				{
					return $this->getPageDetails($objRecipientList->viewOnlinePage);
				}
			}
		}

		if ($objCategory->viewOnlinePage > 0)
		{
			return $this->getPageDetails($objCategory->viewOnlinePage);
		}

		return null;
	}


	/**
	 * Test if backend sending is allowed.
	 */
	public function allowBackendSending()
	{
		if ($GLOBALS['TL_CONFIG']['avisota_backend_send'])
		{
			if ($GLOBALS['TL_CONFIG']['avisota_backend_send'] == 'disabled')
			{
				return false;
			}
			if ($GLOBALS['TL_CONFIG']['avisota_disable_backend_send'] == 'admin' && !$this->User->admin)
			{
				return false;
			}
		}
		return true;
	}


	/**
	 * Extend the url to an absolute url.
	 */
	public function extendURL($strUrl, $objPage = null, $objCategory = null, $arrRecipient = null)
	{
		if ($objPage == null)
		{
			$objPage = $this->getViewOnlinePage($objCategory, $arrRecipient);
		}

		return $this->DomainLink->absolutizeUrl($strUrl, $objPage);
	}


	/**
	 * Get a dummy recipient array.
	 */
	public function getPreviewRecipient($personalized)
	{
		$arrRecipient = array();
		if ($personalized == 'private')
		{
			$objMember = $this->Database->prepare("
					SELECT
						*
					FROM
						tl_member
					WHERE
							email=?
						AND disable=''")
				->execute($this->User->email);
			if ($objMember->next())
			{
				$arrRecipient = $objMember->row();
				$arrRecipient['name'] = $arrRecipient['firstname'] . ' ' . $arrRecipient['lastname'];
				$arrRecipient['personalized'] = 'private';
			}
			else
			{
				$arrRecipient = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous'];
				$arrRecipient['email'] = $this->User->email;
				list($arrRecipient['firstname'], $arrRecipient['lastname']) = $this->splitFriendlyName($arrRecipient['name']);
				$arrRecipient['personalized'] = 'anonymous';
			}
		}
		else
		{
			$arrRecipient = $GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous'];
			$arrRecipient['email'] = $this->User->email;
			$arrRecipient['personalized'] = 'anonymous';
		}

		$arrRecipient['outbox_source'] = 'list:0';

		return $arrRecipient;
	}
}
?>