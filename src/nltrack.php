<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @author     Leo Unglaub <leo@leo-unglaub.net>
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
		if ($intId = $this->Input->get('read')) {
			// prepare the set array
			$arrSet = array(
				'tstamp' => time(),
				'readed' => 1
			);

			// mark the newsletter as read
			$this->Database
				->prepare("UPDATE tl_avisota_statistic_raw_recipient %s WHERE readed='' AND id=?")
				->set($arrSet)
				->execute($intId);

			$strFile = 'system/modules/Avisota2/html/blank.gif';
			$objFile = new File($strFile);

			// stream the file to the client
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
		if ($intId = $this->Input->get('link')) {
			$objRecipientLink = $this->Database
				->prepare("SELECT * FROM tl_avisota_statistic_raw_recipient_link WHERE id=?")
				->execute($intId);

			if ($objRecipientLink->next()) {
				// prepare the set values for the read state
				$arrSetReadState = array
				(
					'tstamp' => time(),
					'readed' => 1
				);

				// set read state
				$this->Database
					->prepare("UPDATE tl_avisota_statistic_raw_recipient %s WHERE readed='' AND pid=? AND recipient=?")
					->set($arrSetReadState)
					->execute($objRecipientLink->pid, $objRecipientLink->recipient);


				// prepare the insert values for the hit counter
				$arrSetHitCounter = array
				(
					'pid'             => $objRecipientLink->pid,
					'linkID'          => $objRecipientLink->linkID,
					'recipientLinkID' => $objRecipientLink->id,
					'recipient'       => $objRecipientLink->recipient,
					'tstamp'          => time()
				);

				// increase hit count
				$this->Database
					->prepare("INSERT INTO tl_avisota_statistic_raw_link_hit %s")
					->set($arrSetHitCounter)
					->execute();

				header('HTTP/1.1 303 See Other');
				header(
					'Location: ' . ($objRecipientLink->real_url ? $objRecipientLink->real_url : $objRecipientLink->url)
				);
				exit;
			}

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
