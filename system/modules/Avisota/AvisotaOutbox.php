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
 * Class AvisotaOutbox
 *
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaOutbox extends BackendModule
{
	protected $strTemplate = 'be_avisota_outbox';

	public function __construct()
	{
		parent::__construct();
		$this->import('DomainLink');
		$this->import('BackendUser', 'User');
		$this->import('AvisotaBase', 'Base');
		$this->loadLanguageFile('tl_avisota_newsletter');
	}

	/**
	 * (non-PHPdoc)
	 * @see BackendModule::generate()
	 */
	public function generate()
	{
		if ($this->Input->get('act') == 'details')
		{
			$this->strTemplate = 'be_avisota_outbox_details';
		}
		if ($this->Input->get('act') == 'send')
		{
			$this->strTemplate = 'be_avisota_outbox_send';
		}

		return parent::generate();
	}


	/**
	 * (non-PHPdoc)
	 * @see BackendModule::compile()
	 */
	protected function compile()
	{
		if (!$this->User->isAdmin && !$this->User->hasAccess('send', 'avisota_newsletter_permissions'))
		{
			$this->log('Not enough permissions to send avisota newsletter', 'Avisota outbox', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->loadLanguageFile('tl_avisota_newsletter_outbox');
		$this->loadLanguageFile('tl_avisota_newsletter');

		if ($this->Input->get('act') == 'details')
		{
			$this->details();
			return;
		}

		if ($this->Input->get('act') == 'remove')
		{
			$this->remove();
			return;
		}

		if ($this->Input->get('act') == 'send')
		{
			$this->send();
			return;
		}

		$this->outboxes();
	}

	protected function details()
	{
		$objOutbox = $this->getOutbox();
		$objNewsletter = $this->getNewsletter($objOutbox);

		$this->Template->outbox = $objOutbox->row();
		$this->Template->newsletter = $objNewsletter->row();

		$arrSession = $this->Session->get('AVISOTA_OUTBOX');

		if (!isset($arrSession['state']))
		{
			$arrSession['state'] = '';
		}
		if (!isset($arrSession['offset']) || $arrSession['offset']>$objOutbox->recipients)
		{
			$arrSession['offset'] = 0;
		}
		if (!isset($arrSession['limit']))
		{
			$arrSession['limit'] = 30;
		}
		if ($this->Input->post('FORM_SUBMIT') == 'tl_filters')
		{
			// set new state
			$arrSession['state'] = in_array($this->Input->post('state'), array('outstanding', 'sended', 'failed')) ? $this->Input->post('state') : '';

			// filter all
			if ($this->Input->post('tl_filter') == 'all')
			{
				$arrSession['offset'] = 0;
				$arrSession['limit'] = 500;
			}

			// filter limit
			else if (preg_match('#^(\d+),(\d+)$#', $this->Input->post('tl_filter'), $m))
			{
				$arrSession['offset'] = intval($m[1]);
				$arrSession['limit'] = intval($m[2]);
			}

			// filter default
			else
			{
				$arrSession['offset'] = 0;
				$arrSession['limit'] = 30;
			}

			// store session ...
			$this->Session->set('AVISOTA_OUTBOX', $arrSession);

			// ... and reload
			$this->reload();
		}

		$this->Session->set('AVISOTA_OUTBOX', $arrSession);
		$this->Template->state = $arrSession['state'];
		$this->Template->offset = $arrSession['offset'];
		$this->Template->limit = $arrSession['limit'];

		switch ($arrSession['state'])
		{
		case 'outstanding':
			$strWhere = "AND send=0";
			break;

		case 'sended':
			$strWhere = "AND send>0 AND failed=''";
			break;

		case 'failed':
			$strWhere = "AND send>0 AND failed='1'";
			break;

		default:
			$strWhere = '';
		}
		$arrRecipients = array();
		$objRecipients = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter_outbox_recipient WHERE pid=? $strWhere ORDER BY email")
			->limit($arrSession['limit'], $arrSession['offset'])
			->execute($objOutbox->id);
		while ($objRecipients->next())
		{
			$arrSource = $this->getSource($objRecipients);
			$arrRecipient = $objRecipients->row();
			switch ($objRecipients->source)
			{
			case 'list':
				$arrRecipient['linkedEmail'] = '<a href="contao/main.php?do=avisota_recipients&table=tl_avisota_recipient&act=edit&id=' . $arrRecipient['recipientID'] . '">' . $arrRecipient['email'] . '</a>';
				break;

			case 'mgroup':
				$arrRecipient['linkedEmail'] = '<a href="contao/main.php?do=member&act=edit&id=' . $arrRecipient['recipientID'] . '">' . $arrRecipient['email'] . '</a>';
				break;
			}
			$arrRecipient['source'] = $arrSource;
			$arrRecipients[] = $arrRecipient;
		}
		$this->Template->recipients = $arrRecipients;
	}

	protected function remove()
	{
		$this->Database
			->prepare("DELETE FROM tl_avisota_newsletter_outbox WHERE id=?")
			->execute($this->Input->get('id'));
		$this->Database
			->prepare("DELETE FROM tl_avisota_newsletter_outbox_recipient WHERE pid=?")
			->execute($this->Input->get('id'));

		$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['removed'];

		$this->redirect('contao/main.php?do=avisota_outbox');
	}


	protected function send()
	{
		if (!$this->Base->allowBackendSending())
		{
			$this->redirect($referer);
		}

		$objOutbox = $this->getOutbox();
		$objNewsletter = $this->getNewsletter($objOutbox);

		$this->Template->outbox = $objOutbox->row();
		$this->Template->newsletter = $objNewsletter->row();
		$this->Template->cycleTimeout = $GLOBALS['TL_CONFIG']['avisota_max_send_time'];
		$this->Template->sendTimeout = $GLOBALS['TL_CONFIG']['avisota_max_send_timeout']*1000;
		$this->Template->expectedTime = ($objOutbox->outstanding / $GLOBALS['TL_CONFIG']['avisota_max_send_count']) * ($GLOBALS['TL_CONFIG']['avisota_max_send_time'] + $GLOBALS['TL_CONFIG']['avisota_max_send_timeout']*1000);
	}


	protected function outboxes()
	{
		// allow backend sending
		$this->Template->beSend = $this->Base->allowBackendSending();

		$arrOutbox = array
		(
			'open' => array(),
			'incomplete' => array(),
			'complete' => array()
		);
		$objOutbox = $this->Database->execute("
				SELECT
					o.id,
					n.subject as newsletter,
					o.tstamp,
					o.plannedTime,
					(SELECT COUNT(id) FROM tl_avisota_newsletter_outbox_recipient r WHERE r.pid=o.id) as recipients,
					(SELECT COUNT(id) FROM tl_avisota_newsletter_outbox_recipient r WHERE r.pid=o.id AND r.send=0) as outstanding,
					(SELECT COUNT(id) FROM tl_avisota_newsletter_outbox_recipient r WHERE r.pid=o.id AND r.failed='1') as failed
				FROM
					tl_avisota_newsletter_outbox o
				INNER JOIN
					tl_avisota_newsletter n
				ON
					n.id=o.pid
				ORDER BY
					o.tstamp DESC,
					n.subject ASC");
		while ($objOutbox->next())
		{

			// show source-list-names
			$objSource = $this->Database
				->prepare('SELECT source, sourceID, COUNT(id) as recipients FROM tl_avisota_newsletter_outbox_recipient WHERE pid=? GROUP BY source')
				->execute($objOutbox->id);

			$arrSources = array();
			while($objSource->next())
			{
				$arrSource = $this->getSource($objSource);
				if ($arrSource)
				{
					$arrSources[] = array_merge($arrSource, $objSource->row());
				}
			}
			$objOutbox->sources = $arrSources;

			if ($objOutbox->outstanding == $objOutbox->recipients)
			{
				$arrOutbox['open'][] = $objOutbox->row();
			}
			elseif ($objOutbox->outstanding > 0)
			{
				$arrOutbox['incomplete'][] = $objOutbox->row();
			}
			else
			{
				$arrOutbox['complete'][] = $objOutbox->row();
			}
			if ($objOutbox->failed > 0)
			{
				$this->Template->display_failed = true;
			}
		}
		if (count($arrOutbox['open']) || count($arrOutbox[incomplete]) || count($arrOutbox['complete']))
		{
			$this->Template->outbox = $arrOutbox;
		}
		else
		{
			$this->Template->outbox = false;
		}

		return $this->Template->parse();
	}


	/**
	 * Get a source description from outbox recipient.
	 */
	protected function getSource($objRecipient)
	{
		switch ($objRecipient->source)
		{
		case 'list':
			$objList = $this->Database
				->prepare("SELECT * FROM tl_avisota_recipient_list WHERE id=?")
				->execute($objRecipient->sourceID);
			if ($objList->next())
			{
				$arrSource = $objList->row();
				$arrSource['title'] = sprintf('%s: %s', $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['recipient_list'], $objList->title);
				$arrSource['linkedTitle'] = sprintf('%s: <a href="contao/main.php?do=avisota_recipients&table=tl_avisota_recipient&id=%d">%s</a>', $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['recipient_list'], $objList->id, $objList->title);
				return $arrSource;
			}

		case 'mgroup':
			$objMgroup = $this->Database
				->prepare("SELECT * FROM tl_member_group WHERE id=?")
				->execute($objRecipient->sourceID);
			if ($objMgroup->next())
			{
				$arrSource = $objMgroup->row();
				$arrSource['title'] = sprintf('%s: %s', $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['mgroup'], $objMgroup->name);
				$arrSource['linkedTitle'] = sprintf('%s: <a href="contao/main.php?do=member">%s</a>', $GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['mgroup'], $objMgroup->name);
				return $arrSource;
			}
		}
		return false;
	}

	protected function getOutbox()
	{
		// get the outbox
		$objOutbox = $this->Database
			->prepare("SELECT
					*,
					(SELECT COUNT(id) FROM tl_avisota_newsletter_outbox_recipient r WHERE o.id=r.pid) as recipients,
					(SELECT COUNT(id) FROM tl_avisota_newsletter_outbox_recipient r WHERE o.id=r.pid AND r.send=0) as outstanding,
					(SELECT COUNT(id) FROM tl_avisota_newsletter_outbox_recipient r WHERE o.id=r.pid AND r.failed='1') as failed
				FROM
					tl_avisota_newsletter_outbox o
				WHERE
					id=?")
			->execute($this->Input->get('id'));

		if (!$objOutbox->next())
		{
			$this->redirect('contao/main.php?do=avisota_outbox');
		}

		return $objOutbox;
	}

	protected function getNewsletter($objOutbox)
	{
		$objNewsletter = $this->Database
			->prepare("SELECT * FROM tl_avisota_newsletter WHERE id=?")
			->execute($objOutbox->pid);

		if (!$objNewsletter->next())
		{
			$this->redirect('contao/main.php?do=avisota_outbox');
		}

		return $objNewsletter;
	}
}
