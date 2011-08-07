<?php

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
* Initialize the system
*/
define('TL_MODE', 'FE');
require('system/initialize.php');

/**
* Class Track
*
* Newsletter tracking controller.
* @package    Avisota
*/
class Tracking extends Frontend
{
	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Run the controller.
	 */
	public function run()
	{
		// newsletter read
		if ($intId = $this->Input->get('read'))
		{
			$this->Database->prepare("UPDATE tl_avisota_newsletter_read SET tstamp=?, readed=? WHERE readed='' AND id=?")->execute(time(), '1', $intId);

			$strFile = 'system/modules/Avisota/html/blank.gif';
			$objFile = new File($strFile);

			// Open the "save as …" dialogue
			header('Content-Type: ' . $objFile->mime);
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . $objFile->filesize);
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Expires: 0');

			$resFile = fopen(TL_ROOT . '/' . $strFile, 'rb');
			fpassthru($resFile);
			fclose($resFile);

			exit;
		}

		// newsletter link click
		if ($intId = $this->Input->get('link'))
		{
			$objLink = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_link_hit WHERE id=?")->execute($intId);
			if ($objLink->next())
			{
				// set read state
				$this->Database->prepare("UPDATE tl_avisota_newsletter_read SET tstamp=?, readed=? WHERE readed='' AND pid=? AND recipient=?")->execute(time(), '1', $objLink->pid, $objLink->recipient);

				// increase hit count
				$this->Database->prepare("UPDATE tl_avisota_newsletter_link_hit SET hits=hits+1, times=IF(times='' OR times=NULL, DATE_FORMAT(NOW(), '%%d.%%m.%%Y %%H'), CONCAT(times, ',', DATE_FORMAT(NOW(), '%%d.%%m.%%Y %%H'))) WHERE id=?")->execute($intId);

				header('HTTP/1.1 303 See Other');
				header('Location: ' . $objLink->url);
				exit;
			}

			$this->import('FrontendUser', 'User');
			$this->User->authenticate();
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate('nltrack.php');
		}
	}
}

/**
 * Instantiate controller
 */
$objTracking = new Tracking();
$objTracking->run();
