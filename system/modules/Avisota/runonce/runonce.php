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
 * Class AvisotaRunonce
 * 
 * 
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaRunonce extends Controller
{

	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->import('Database');
	}
	
	
	public function run()
	{
		$this->upgrade0_4_5();
		$this->addFolderUrlSupport();
	}
	
	
	/**
	 * Database upgrade to 0.4.5
	 */
	protected function upgrade0_4_5()
	{
		if (!$this->Database->fieldExists('area', 'tl_avisota_newsletter_content'))
		{
			$this->Database->execute("ALTER TABLE tl_avisota_newsletter_content ADD area varchar(32) NOT NULL default ''");
		}
		$this->Database->prepare("UPDATE tl_avisota_newsletter_content SET area=? WHERE area=?")->execute('body', '');
	}
	
	
	/**
	 * Add folderUrl keyword.
	 */
	protected function addFolderUrlSupport()
	{
		$strUrlKeywords = '';
		if (isset($GLOBALS['TL_CONFIG']['urlKeywords']))
		{
			$strUrlKeywords = $GLOBALS['TL_CONFIG']['urlKeywords'];
		}
		$arrUrlKeywords = trimsplit(',', $strUrlKeywords);
		
		# check if "item" url keyword exists
		if (!in_array('item', $arrUrlKeywords))
		{
			# add "item" url keyword
			$arrUrlKeywords[] = 'item';
			$strUrlKeywords = implode(',', $arrUrlKeywords);
			
			# update urlKeywords setting
			$this->Config->update('$GLOBALS[\'TL_CONFIG\'][\'urlKeywords\']', $strUrlKeywords);
			$GLOBALS['TL_CONFIG']['urlKeywords'] = $strUrlKeywords;
		}
	}
}

/**
 * Instantiate controller
 */
$objAvisotaRunonce = new AvisotaRunonce();
$objAvisotaRunonce->run();

?>