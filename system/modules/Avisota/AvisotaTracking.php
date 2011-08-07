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
 * Class Avisota
 *
 * Parent class for newsletter content elements.
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaTracking extends BackendModule
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_avisota_tracking';


	public function compile()
	{
		$this->loadLanguageFile('avisota_tracking');

		# load the session settings
		$arrSession = $this->Session->get('avisota_tracking');

		# create an empty session
		if (!is_array($arrSession))
		{
			$arrSession = array
			(
				'newsletter' => 0,
				'recipient'  => ''
			);
		}

		# evaluate the post and get parameters
		if ($this->Input->post('newsletter'))
		{
			$arrSession['newsletter'] = $this->Input->post('newsletter');
		}
		if ($this->Input->post('recipient'))
		{
			$arrSession['recipient'] = $this->Input->post('recipient');
		}
		if ($this->Input->get('newsletter'))
		{
			$arrSession['newsletter'] = $this->Input->get('newsletter');
		}
		if ($this->Input->get('recipient'))
		{
			$arrSession['recipient'] = $this->Input->get('recipient');
		}

		$arrRecipients = $this->Database->execute("SELECT recipient FROM tl_avisota_newsletter_read GROUP BY recipient ORDER BY recipient")->fetchEach('recipient');
		if ($arrSession['recipient'] && !in_array($arrSession['recipient'], $arrRecipients))
		{
			$arrSession['recipient'] = '';
		}

		# where statement, if the newsletters have to filter by a specific recipient
		$strWhere = '';

		# collect read state and build where statement for a specific recipient
		if ($arrSession['recipient'])
		{
			$objRead = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_read WHERE recipient=?")->execute($arrSession['recipient']);
			$arrIds = $objRead->fetchEach('pid');
			if (count($arrIds))
			{
				$strWhere = ' AND id IN (' . implode(',', $arrIds) . ')';
			}
			else
			{
				$strWhere = ' AND id=0';
			}

			$objRead = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_read WHERE recipient=? AND readed=?")->execute($arrSession['recipient'], 1);
			$this->Template->read = $objRead->fetchEach('pid');
		}
		else
		{
			$this->Template->read = array();
		}

		# read all available newsletters (if set, only for a specific recipient)
		$arrNewsletters = array();
		$objNewsletters = $this->Database->execute("SELECT * FROM tl_avisota_newsletter WHERE sendOn!='' $strWhere ORDER BY sendOn DESC");
		while ($objNewsletters->next())
		{
			$arrNewsletters[$objNewsletters->id] = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objNewsletters->sendOn) . ' ' . $objNewsletters->subject;
		}

		# find last sended newsletter
		if (!isset($arrNewsletters[$arrSession['newsletter']]))
		{
			$arrSession['newsletter'] = '';
		}

		if ($arrSession['newsletter'])
		{
			$objNewsletter = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")->execute($arrSession['newsletter']);
			if ($objNewsletter->next())
			{
				$this->Template->newsletter = $objNewsletter;

				# collect links hits
				if ($arrSession['recipient'])
				{
					$objLink = $this->Database->prepare("SELECT url,(SELECT COUNT(id) FROM tl_avisota_newsletter_link_hit h WHERE l.id=h.pid) as hits FROM tl_avisota_newsletter_link l WHERE pid=? AND recipient=? ORDER BY hits DESC")->execute($arrSession['newsletter'], $arrSession['recipient']);
				}
				else
				{
					$objLink = $this->Database->prepare("SELECT url,SUM(hits) as hits FROM (SELECT url,(SELECT COUNT(id) FROM tl_avisota_newsletter_link_hit h WHERE l.id=h.pid) as hits FROM tl_avisota_newsletter_link l WHERE pid=?) t GROUP BY url ORDER BY hits DESC")->execute($arrSession['newsletter']);
				}
				$this->Template->links = $objLink->fetchAllAssoc();

				// collect newsletter/recipient, reads and reacts count
				if ($arrSession['recipient'])
				{
					// total number of recived newsletters
					$this->Template->total  = $this->Database->prepare("SELECT COUNT(pid) as total FROM tl_avisota_newsletter_read WHERE recipient=?")->execute($arrSession['recipient'])->total;
					// total number of readed newsletters
					$this->Template->reads  = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_read WHERE recipient=? AND readed=? ORDER BY tstamp")->execute($arrSession['recipient'], 1)->fetchAllAssoc();
					// total number of newsletters the recipients reacts on (clicked a link)
					$this->Template->reacts = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_link_hit WHERE recipient=? AND hits>0 GROUP BY pid ORDER BY tstamp")->execute($arrSession['recipient'])->fetchAllAssoc();
				}
				else
				{
					// total number of recipients for this newsletter
					$this->Template->total  = $this->Database->prepare("SELECT COUNT(recipient) as total FROM tl_avisota_newsletter_read WHERE pid=?")->execute($objNewsletter->id)->total;
					// total number of recipients that reads this newsletter
					$this->Template->reads  = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_read WHERE pid=? AND readed=? ORDER BY tstamp")->execute($objNewsletter->id, 1)->fetchAllAssoc();
					// total number ov recipients taht reacts on this newsletter (clicked a link)
					$this->Template->reacts = $this->Database->prepare("SELECT * FROM tl_avisota_newsletter_link_hit WHERE pid=? AND hits>0 GROUP BY recipient ORDER BY tstamp")->execute($objNewsletter->id)->fetchAllAssoc();
				}
			}
			else
			{
				$arrSession['newsletter'] = '';
			}
		}

		if (!$arrSession['newsletter'])
		{

		}

		$this->Template->mode = ($arrSession['recipient']) ? 'recipient' : 'newsletter';
		$this->Template->newsletters = $arrNewsletters;
		$this->Template->recipients = $arrRecipients;
		$this->Template->recipient = $arrSession['recipient'];

		$this->Session->set('avisota_tracking', $arrSession);
	}

	protected function search_intersect($a, $b)
	{
		foreach ($a as $e)
		{
			if (in_array($e, $b))
			{
				return true;
			}
		}
		return false;
	}
}
