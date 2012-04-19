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
 * Class ExtendedEmail
 *
 * Special variant of Email with some quick fixes.
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class ExtendedEmail extends Email
{
	public function __get($strKey)
	{
		switch ($strKey) {
			case 'swiftMessage':
				return $this->objMessage;
		}

		return parent::__get($strKey);
	}
	
	/**
	 * Get e-mail addresses from an array, string or unlimited number of arguments and send the e-mail
	 *
	 * Friendly name portions (e.g. Leo <leo@contao.org>) are allowed.
	 * @param mixed
	 * @return boolean
	 */
	public function sendTo()
	{
		$arrRecipients = $this->compileRecipients(func_get_args());

		if (!count($arrRecipients))
		{
			return false;
		}

		$this->objMessage->setTo($arrRecipients);
		$this->objMessage->setCharset($this->strCharset);
		$this->objMessage->setPriority($this->intPriority);

		// Default subject
		if (empty($this->strSubject))
		{
			$this->strSubject = 'No subject';
		}

		$this->objMessage->setSubject($this->strSubject);

		// HTML e-mail
		if (!empty($this->strHtml))
		{
			// Embed images
			if ($this->blnEmbedImages)
			{
				if (!strlen($this->strImageDir))
				{
					$this->strImageDir = TL_ROOT . '/';
				}

				$arrMatches = array();
				preg_match_all('/(background|src)="([^"]+\.(jpe?g|png|gif|bmp|tiff?|swf))"/Ui', $this->strHtml, $arrMatches, PREG_SET_ORDER);
				$strBase = Environment::getInstance()->base;

				$arrSrcEmbeded = array();
				// Check for internal images
				foreach ($arrMatches as $url)
				{
					// skip replaced urls
					if (in_array($url[2], $arrSrcEmbeded))
					{
						continue;
					}
					$arrSrcEmbeded[] = $url[2];

					// Try to remove the base URL
					$src = str_replace($strBase, '', $url[2]);
					// Embed the image if the URL is now relative
					if (!preg_match('@^https?://@', $src) && file_exists($this->strImageDir . $src))
					{
						$cid = $this->objMessage->embed(Swift_EmbeddedFile::fromPath($this->strImageDir . $src));
						$this->strHtml = preg_replace('#(background|src)="' . $url[2] . '"#', '$1="' . $cid . '"', $this->strHtml);
					}
				}
			}

			$this->objMessage->setBody($this->strHtml, 'text/html');
		}

		// Text content
		if (!empty($this->strText))
		{
			if (!empty($this->strHtml))
			{
				$this->objMessage->addPart($this->strText, 'text/plain');
			}
			else
			{
				$this->objMessage->setBody($this->strText, 'text/plain');
			}
		}

		// Add the administrator e-mail as default sender
		if ($this->strSender == '')
		{
			list($this->strSenderName, $this->strSender) = $this->splitFriendlyName($GLOBALS['TL_CONFIG']['adminEmail']);
		}

		// Sender
		if ($this->strSenderName != '')
		{
			$this->objMessage->setFrom(array($this->strSender=>$this->strSenderName));
		}
		else
		{
			$this->objMessage->setFrom($this->strSender);
		}

		// Send e-mail
		$intSent = self::$objMailer->send($this->objMessage, $this->arrFailures);

		// Log failures
		if (!empty($this->arrFailures))
		{
			log_message('E-mail address rejected: ' . implode(', ', $this->arrFailures), $this->strLogFile);
		}

		// Return if no e-mails have been sent
		if ($intSent < 1)
		{
			return false;
		}

		// Add log entry
		$strMessage = 'An e-mail has been sent to ' . implode(', ', array_keys($this->objMessage->getTo()));

		if (count($this->objMessage->getCc()) > 0)
		{
			$strMessage .= ', CC to ' . implode(', ', array_keys($this->objMessage->getCc()));
		}

		if (count($this->objMessage->getBcc()) > 0)
		{
			$strMessage .= ', BCC to ' . implode(', ', array_keys($this->objMessage->getBcc()));
		}

		log_message($strMessage, $this->strLogFile);
		return true;
	}
	
}

?>
