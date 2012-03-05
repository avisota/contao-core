<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


$this->loadLanguageFile('tl_avisota_recipient');
$this->loadDataContainer('tl_avisota_recipient');

/**
 * Class AvisotaIntegratedRecipient
 *
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class AvisotaIntegratedRecipient extends AvisotaRecipient
{
	/**
	 * @static
	 * @param $strEmail
	 */
	public static function byEmail($strEmail)
	{
		$objRecipient = new AvisotaIntegratedRecipient(array('email' => $strEmail));
		$objRecipient->load();
		return $objRecipient;
	}

	protected function load()
	{
		$objRecipient = $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient WHERE email=?")
			->execute($this->email);
		if ($objRecipient->next()) {
			$arrRecipient = $objRecipient->row();

			foreach ($arrRecipient as $k=>$v) {
				if (isset($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['load_callback']) && is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['load_callback'])) {
					foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['load_callback'] as $callback) {
						$this->import($callback[0]);
						$arrRecipient[$k] = $v = $this->$callback[0]->$callback[1]($v);
					}
				}
			}

			$this->setData($arrRecipient);
		} else {
			throw new AvisotaRecipientException($this->arrData, 'The recipient data could not be loaded!');
		}
	}

	/**
	 * Store this recipient into the database.
	 *
	 * @throws AvisotaRecipientException
	 */
	public function store()
	{
		self::validate($this->arrData);

		$arrData = $this->arrData;
		$arrData['tstamp'] = time();

		$arrSet = array();
		$arrArgs = array();

		foreach ($arrData as $k=>$v)
		{
			if (isset($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['save_callback']) && is_array($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['save_callback'])) {
				foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['save_callback'] as $callback) {
					$this->import($callback[0]);
					$v = $this->$callback[0]->$callback[1]($v);
				}
			}

			$arrSet[] = $k;
			$arrArgs[] = trim($v);
		}

		$this->Database
			->prepare(sprintf('INSERT INTO tl_avisota_recipient SET %1$s ON DUPLICATE KEY UPDATE %1$s', implode(',', $arrSet)))
			->execute(array_merge($arrArgs, $arrArgs));

		$this->load();
	}

	/**
	 * Validate the recipient object.
	 *
	 * @static
	 * @param array $arrData
	 * @throws AvisotaRecipientException
	 */
	public function validate(array $arrData)
	{
		foreach ($arrData as $k=>$v) {
			$v = trim($v);
			if ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'][$k]['eval']['mandatory'] && empty($v)) {
				throw new AvisotaRecipientException($arrData, 'The recipient data field "' . $k . '" is mandatory!');
			}
		}
		parent::validate($arrData);
	}

	public function getMailingLists()
	{
		return $this->Database
			->prepare("SELECT * FROM tl_avisota_recipient_to_mailing_list rtml WHERE recipient=?")
			->execute($this->id)
			->fetchEach('mailing_list');
	}

	/**
	 * Subscribe this recipient to the mailing lists.
	 * Will <strong>not</strong> send any confirmation mails.
	 * Throws an exception, if the recipient is in the blacklist.
	 *
	 * @param array $arrLists
	 * @param bool $blnIgnoreBlacklist
	 * @throws AvisotaSubscriptionException
	 * @throws AvisotaBlacklistException
	 */
	public function subscribe(array $arrLists, $blnIgnoreBlacklist = false)
	{
		throw new AvisotaSubscriptionException('This recipient cannot subscribe!');

	}

	/**
	 * Confirm the subscription of the mailing lists.
	 *
	 * @param array $arrLists
	 * @throws AvisotaSubscriptionException
	 */
	public function confirmSubscription(array $arrLists)
	{
		throw new AvisotaSubscriptionException('This recipient cannot subscribe!');
	}

	/**
	 * Remove the subscription to the mailing lists.
	 *
	 * @param array $arrLists
	 * @param bool $blnDoNotBlacklist
	 * @throws AvisotaSubscriptionException
	 */
	public function unsubscribe(array $arrLists, $blnDoNotBlacklist = false)
	{
		throw new AvisotaSubscriptionException('This recipient cannot subscribe!');
	}

	/**
	 * Send the subscription confirmation mail to the given mailing lists
	 * or all unconfirmed mailing lists, the recipient has subscribed.
	 *
	 * @param array|null $arrLists
	 * @throws AvisotaSubscriptionException
	 */
	public function sendSubscriptionConfirmation(array $arrLists = null)
	{
		throw new AvisotaSubscriptionException('This recipient cannot subscribe!');
	}

	/**
	 * Send a reminder to the given mailing lists
	 * or all unconfirmed, not reminded mailing lists, the recipient has subscribed.
	 *
	 * @param array|null $arrLists
	 * @throws AvisotaSubscriptionException
	 */
	public function sendRemind(array $arrLists = null)
	{
		throw new AvisotaSubscriptionException('This recipient cannot subscribe!');
	}
}
