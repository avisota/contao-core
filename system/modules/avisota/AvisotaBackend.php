<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Class AvisotaBackend
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 */
class AvisotaBackend extends Controller
{
	/**
	 * @var AvisotaBackend
	 */
	protected static $instance = null;

	/**
	 * @static
	 * @return AvisotaBackend
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new AvisotaBackend();
		}
		return self::$instance;
	}

	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	/**
	 * Get options list of recipients.
	 *
	 * @return array
	 */
	public function getRecipients($prefixSourceId = false)
	{
		$recipients = array();

		$source = \Database::getInstance()
			->execute("SELECT * FROM orm_avisota_recipient_source WHERE disable='' ORDER BY sorting");
		while ($source->next()) {
			if (isset($GLOBALS['orm_avisota_RECIPIENT_SOURCE'][$source->type])) {
				$class    = $GLOBALS['orm_avisota_RECIPIENT_SOURCE'][$source->type];
				$instance = new $class($source->row());
				$options  = $instance->getRecipientOptions();
				if (count($options)) {
					$sourceOptions = array();
					foreach ($options as $k => $v) {
						$sourceOptions[$source->id . ':' . $k] = $v;
					}
					$recipients[($prefixSourceId ? $source->id . ':'
						: '') . $source->title] = $sourceOptions;
				}
			}
			else {
				$this->log(
					'Recipient source "' . $source->type . '" type not found!',
					'AvisotaBackend::getRecipients()',
					TL_ERROR
				);
				$this->redirect('contao/main.php?act=error');
			}
		}

		return $recipients;
	}

	public function hookOutputBackendTemplate($content, $template)
	{
		if ($template == 'be_main') {
			# add form multipart enctype
			if (($this->Input->get('table') == 'orm_avisota_recipient_import' || $this->Input->get(
				'table'
			) == 'orm_avisota_recipient_remove')
			) {
				$content = str_replace('<form', '<form enctype="multipart/form-data"', $content);
			}
		}
		return $content;
	}

	public function hookAvisotaMailingListLabel($row, $label, DataContainer $dc)
	{
		$result = \Database::getInstance()
			->prepare(
			"SELECT
				(SELECT COUNT(rl.recipient) FROM orm_avisota_recipient_to_mailing_list rl WHERE rl.list=?) as total_recipients,
				(SELECT COUNT(rl.recipient) FROM orm_avisota_recipient_to_mailing_list rl INNER JOIN orm_avisota_recipient r ON r.id=rl.recipient WHERE rl.confirmed=? AND rl.list=?) as disabled_recipients,
				(SELECT COUNT(ml.member) FROM tl_member_to_mailing_list ml WHERE ml.list=?) as total_members,
				(SELECT COUNT(ml.member) FROM tl_member_to_mailing_list ml INNER JOIN tl_member m ON m.id=ml.member WHERE m.disable=? AND ml.list=?) as disabled_members"
		)
			->execute($row['id'], '', $row['id'], $row['id'], '1', $row['id']);
		if ($result->next()) {
			if ($result->total_recipients > 0) {
				$label .= '<div style="padding: 1px 0;">' .
					'<a href="contao/main.php?do=avisota_recipients&amp;showlist=' . $row['id'] . '">' .
					$this->generateImage('system/modules/avisota/html/recipients.png', '') .
					' ' .
					sprintf(
						$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['label_recipients'],
						$result->total_recipients,
						$result->total_recipients - $result->disabled_recipients,
						$result->disabled_recipients
					) .
					'</a>' .
					'</div>';
			}
			if ($result->total_members > 0) {
				$label .= '<div style="padding: 1px 0;">' .
					'<a href="contao/main.php?do=member&amp;avisota_showlist=' . $row['id'] . '">' .
					$this->generateImage('system/themes/default/images/member.gif', '') .
					' ' .
					sprintf(
						$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['label_members'],
						$result->total_members,
						$result->total_members - $result->disabled_members,
						$result->disabled_members
					) .
					'</a>' .
					'</div>';
			}
		}
		return $label;
	}

	/**
	 * Build custom back end modules
	 */
	public function hookGetUserNavigation($modules, $showAll)
	{
		if (isset($modules['avisota'])) {
			foreach ($modules['avisota']['modules'] as $moduleName => &$module) {
				if (preg_match('#^avisota_newsletter_(\d+)$#', $moduleName, $match)) {
					$categoryId = $match[1];

					// $module['class'] = str_replace(' active', '', $module['class']);
					$module['href'] .= '&amp;table=orm_avisota_message&amp;id=' . $categoryId;

					// if this category is active
					if ($this->Input->get('do') == 'avisota_newsletter' &&
						$this->Input->get('table') == 'orm_avisota_message' &&
						$this->Input->get('act') != 'edit' &&
						$this->Input->get('id') == $categoryId
					) {
						// remove active class from avisota_newsletter menu item
						$modules['avisota']['modules']['avisota_newsletter']['class'] = str_replace(
							' active',
							'',
							$modules['avisota']['modules']['avisota_newsletter']['class']
						);
						// add active class to this category menu item
						$module['class'] .= ' active';
					}
				}
			}
			/*
			$arrCustomModules = array();
			if (\Database::getInstance()->fieldExists('showInMenu', 'orm_avisota_message_category')) {
				$objCategory = \Database::getInstance()->query('SELECT * FROM orm_avisota_message_category WHERE showInMenu=\'1\' ORDER BY title');
				while ($objCategory->next()) {
					$arrCustomModules['avisota_newsletter_' . $objCategory->id] = array_slice($arrModules['avisota']['modules']['avisota_newsletter'], 0);
					if ($objCategory->menuIcon) {
						$arrCustomModules['avisota_newsletter_' . $objCategory->id]['icon'] = sprintf(' style="background-image:url(\'%s\')"', $objCategory->menuIcon);
					}
					$arrCustomModules['avisota_newsletter_' . $objCategory->id]['label'] = $objCategory->title;
					$arrCustomModules['avisota_newsletter_' . $objCategory->id]['class'] = str_replace(' active', '', $arrCustomModules['avisota_newsletter_' . $objCategory->id]['class']);
					$arrCustomModules['avisota_newsletter_' . $objCategory->id]['class'] .= ' avisota_newsletter_' . $objCategory->id;
					$arrCustomModules['avisota_newsletter_' . $objCategory->id]['href']  .= '&amp;table=orm_avisota_message&amp;id=' . $objCategory->id;

					// if this category is active
					if ($this->Input->get('do') == 'avisota_newsletter' &&
						$this->Input->get('table') == 'orm_avisota_message' &&
						$this->Input->get('act') != 'edit' &&
						$this->Input->get('id') == $objCategory->id) {
						// remove active class from avisota_newsletter menu item
						$arrModules['avisota']['modules']['avisota_newsletter']['class'] = str_replace(' active', '', $arrModules['avisota']['modules']['avisota_newsletter']['class']);
						// add active class to this category menu item
						$arrCustomModules['avisota_newsletter_' . $objCategory->id]['class'] .= ' active';
					}
				}
			}

			$i = array_search('avisota_newsletter', array_keys($arrModules['avisota']['modules']));

			$arrModules['avisota']['modules'] = array_merge(
				array_slice($arrModules['avisota']['modules'], 0, $i),
				$arrCustomModules,
				array_slice($arrModules['avisota']['modules'], $i)
			);
			*/
		}

		return $modules;
	}

	/**
	 * Send an email.
	 *
	 * @param string $strMode
	 * @param string $plainContent
	 * @param string $htmlContent
	 * @param string $recipientMail
	 */
	protected function sendMail($module, $page, $plainContent, $htmlContent, $recipientMail)
	{
		$rootPage = $this->getPageDetails($page->rootId);

		$email = new Email();

		$email->subject = $GLOBALS['TL_LANG']['avisota']['notification']['mail']['subject'];
		$email->logFile = 'subscription.log';
		$email->text    = $plainContent;
		$email->html    = $htmlContent;

		$email->from = $module->avisota_subscription_sender
			? $module->avisota_subscription_sender
			: (strlen(
				$rootPage->adminEmail
			) ? $rootPage->adminEmail : $GLOBALS['TL_CONFIG']['adminEmail']);

		// Add sender name
		if (strlen($module->avisota_subscription_sender_name)) {
			$email->fromName = $module->avisota_subscription_sender_name;
		}

		$email->imageDir = TL_ROOT . '/';

		try {
			$email->sendTo($recipientMail);
			return true;
		}
		catch (Swift_RfcComplianceException $e) {
			return $e->getMessage();
		}
	}


	/**
	 * Convert id list to name list.
	 *
	 * @param array $listIds
	 *
	 * @return array
	 */
	protected function getListNames($listIds)
	{
		$lists = array();

		$placeholders = array();
		for ($i = 0; $i < count($listIds); $i++) {
			$placeholders[] = '?';
		}

		$list = \Database::getInstance()
			->prepare(
			"
					SELECT
						*
					FROM
						`orm_avisota_mailing_list`
					WHERE
						`id` IN (" . implode(',', $placeholders) . ")
					ORDER BY
						`title`"
		)
			->execute($listIds);
		while ($list->next()) {
			$lists[] = $list->title;
		}

		return $lists;
	}
}
