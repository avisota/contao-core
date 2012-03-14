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
 * @author     Oliver Hoff <oliver@hofff.com>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Constants
 */
define('AVISOTA_VERSION', '1.6.0');
define('NL_HTML', 'html');
define('NL_PLAIN', 'plain');


/**
 * Update check
 */
$blnAvisotaUpdate = false;
foreach (AvisotaUpdate::$updates as $strVersion)
{
	$blnAvisotaUpdate = $blnAvisotaUpdate
		|| !isset($GLOBALS['TL_CONFIG']['avisota_update'][$strVersion])
		|| !$GLOBALS['TL_CONFIG']['avisota_update'][$strVersion];
}


/**
 * Request starttime
 */
if (!isset($_SERVER['REQUEST_TIME'])) {
	$_SERVER['REQUEST_TIME'] = time();
}


/**
 * Settings
 */
$GLOBALS['TL_CONFIG']['avisota_max_send_time']      = ini_get('max_execution_time') > 0 ? floor(0.85 * ini_get('max_execution_time')) : 120;
$GLOBALS['TL_CONFIG']['avisota_max_send_count']     = 100;
$GLOBALS['TL_CONFIG']['avisota_max_send_timeout']   = 1;
$GLOBALS['TL_CONFIG']['avisota_notification_time']  = 3;
$GLOBALS['TL_CONFIG']['avisota_notification_count'] = 3;
$GLOBALS['TL_CONFIG']['avisota_cleanup_time']       = 14;


/**
 * Salutation
 */
if (!isset($GLOBALS['TL_CONFIG']['avisota_salutations'])) {
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array('salutation' => 'Sehr geehrter Herr',
	                                                       'title'      => true,
	                                                       'firstname'  => true,
	                                                       'lastname'   => true);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array('salutation' => 'Sehr geehrte Frau',
	                                                       'title'      => true,
	                                                       'firstname'  => true,
	                                                       'lastname'   => true);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array('salutation' => 'Sehr geehrte/-r Herr/Frau',
	                                                       'title'      => true,
	                                                       'firstname'  => true,
	                                                       'lastname'   => true);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array('salutation' => 'Sehr geehrter Herr',
	                                                       'title'      => false,
	                                                       'firstname'  => true,
	                                                       'lastname'   => true);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array('salutation' => 'Sehr geehrte Frau',
	                                                       'title'      => false,
	                                                       'firstname'  => true,
	                                                       'lastname'   => true);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array('salutation' => 'Sehr geehrte/-r Herr/Frau',
	                                                       'title'      => false,
	                                                       'firstname'  => true,
	                                                       'lastname'   => true);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array('salutation' => 'Sehr geehrter',
	                                                       'title'      => false,
	                                                       'firstname'  => true,
	                                                       'lastname'   => true);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array('salutation' => 'Sehr geehrte',
	                                                       'title'      => false,
	                                                       'firstname'  => true,
	                                                       'lastname'   => true);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array('salutation' => 'Sehr geehrte/-r',
	                                                       'title'      => false,
	                                                       'firstname'  => true,
	                                                       'lastname'   => true);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array('salutation' => 'Hallo',
	                                                       'title'      => false,
	                                                       'firstname'  => true,
	                                                       'lastname'   => false);
} else if (is_string($GLOBALS['TL_CONFIG']['avisota_salutations'])) {
	$GLOBALS['TL_CONFIG']['avisota_salutations'] = deserialize($GLOBALS['TL_CONFIG']['avisota_salutations'], true);
}

/**
 * Page types
 */
$GLOBALS['TL_PTY']['avisota'] = 'PageAvisotaNewsletter';


/**
 * Form fields
 */
$GLOBALS['BE_FFL']['upload']                 = 'UploadField';
$GLOBALS['BE_FFL']['columnAssignmentWizard'] = 'ColumnAssignmentWizard';


/**
 * Back end modules
 */
$arrAvisotaBeMod = array
(
	'avisota' => array
	(
		'avisota_mailing_list'     => array
		(
			'tables'     => array('tl_avisota_mailing_list'),
			'icon'       => 'system/modules/Avisota/html/mailing_list.png',
			'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
		),
		'avisota_recipients'       => array
		(
			'tables'     => array('tl_avisota_recipient', 'tl_avisota_recipient_migrate', 'tl_avisota_recipient_import', 'tl_avisota_recipient_export', 'tl_avisota_recipient_remove', 'tl_avisota_recipient_notify'),
			'icon'       => 'system/modules/Avisota/html/recipients.png',
			'stylesheet' => 'system/modules/Avisota/html/stylesheet.css',
			'javascript' => 'system/modules/Avisota/html/backend.js'
		),
		'avisota_newsletter'       => array
		(
			'tables'     => array('tl_avisota_newsletter_category', 'tl_avisota_newsletter', 'tl_avisota_newsletter_content'),
			'send'       => array('Avisota', 'send'),
			'icon'       => 'system/modules/Avisota/html/newsletter.png',
			'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
		),
		'avisota_tracking'         => array
		(
			'callback'   => 'AvisotaTracking',
			'tables'     => array('tl_avisota_tracking_export'),
			'icon'       => 'system/modules/Avisota/html/tracking.png',
			'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
		),
		'avisota_outbox'           => array
		(
			'callback'   => 'AvisotaOutbox',
			'icon'       => 'system/modules/Avisota/html/outbox.png',
			'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
		),
		'avisota_recipient_source' => array
		(
			'tables'     => array('tl_avisota_recipient_source'),
			'icon'       => 'system/modules/Avisota/html/recipient_source.png',
			'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
		),
		'avisota_transport'        => array
		(
			'tables'     => array('tl_avisota_transport'),
			'icon'       => 'system/modules/Avisota/html/transport.png',
			'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
		),
		'avisota_settings'         => array
		(
			'tables'     => array('tl_avisota_settings'),
			'icon'       => 'system/modules/Avisota/html/settings.png',
			'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
		)
	)
);
if ($blnAvisotaUpdate) {
	$arrAvisotaBeMod['avisota']['avisota_update'] = array
	(
		'callback'   => 'AvisotaUpdate',
		'icon'       => 'system/modules/Avisota/html/update.png',
		'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
	);
}

$i                 = array_search('design', array_keys($GLOBALS['BE_MOD']));
$GLOBALS['BE_MOD'] = array_merge(
	array_slice($GLOBALS['BE_MOD'], 0, $i),
	$arrAvisotaBeMod,
	array_slice($GLOBALS['BE_MOD'], $i)
);


/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['avisota']['avisota_subscription'] = 'ModuleAvisotaSubscription';
$GLOBALS['FE_MOD']['avisota']['avisota_list']         = 'ModuleAvisotaList';
$GLOBALS['FE_MOD']['avisota']['avisota_reader']       = 'ModuleAvisotaReader';


/**
 * Newsletter elements
 */
$GLOBALS['TL_NLE'] = array_merge_recursive(
	array
	(
		'texts'    => array
		(
			'headline'  => 'NewsletterHeadline',
			'text'      => 'NewsletterText',
			'list'      => 'NewsletterList',
			'table'     => 'NewsletterTable'
		),
		'links'    => array
		(
			'hyperlink' => 'NewsletterHyperlink'
		),
		'images'   => array
		(
			'image'     => 'NewsletterImage',
			'gallery'   => 'NewsletterGallery'
		),
		'includes' => array
		(
			'news'      => 'NewsletterNews',
			'events'    => 'NewsletterEvent',
			'article'   => 'NewsletterArticleTeaser'
		)
	),
	is_array($GLOBALS['TL_NLE']) ? $GLOBALS['TL_NLE'] : array()
);


/**
 * Widgets
 */
$GLOBALS['BE_FFL']['eventchooser'] = 'WidgetEventchooser';
$GLOBALS['BE_FFL']['newschooser']  = 'WidgetNewschooser';


/**
 * Recipient sources
 */
$GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE']['integrated'] = 'IntegratedAvisotaRecipientSource';
$GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE']['member']     = 'MemberGroupRecipientSource';
$GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE']['csv_file']   = 'CSVFileRecipientSource';


/**
 * Transport modules
 */
$GLOBALS['TL_AVISOTA_TRANSPORT']['swift'] = 'SwiftTransport';


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['outputBackendTemplate'][]   = array('AvisotaBackend', 'hookOutputBackendTemplate');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]       = array('AvisotaInsertTag', 'hookReplaceNewsletterInsertTags');
$GLOBALS['TL_HOOKS']['getEditorStylesLayout'][]   = array('AvisotaEditorStyle', 'hookGetEditorStylesLayout');
$GLOBALS['TL_HOOKS']['mysqlMultiTriggerCreate'][] = array('AvisotaUpdate', 'hookMysqlMultiTriggerCreate');
$GLOBALS['TL_HOOKS']['createNewUser'][]           = array('AvisotaDCA', 'hookCreateNewUser');
$GLOBALS['TL_HOOKS']['activateAccount'][]         = array('AvisotaDCA', 'hookActivateAccount');
$GLOBALS['TL_HOOKS']['updatePersonalData'][]      = array('AvisotaDCA', 'hookUpdatePersonalData');
$GLOBALS['TL_HOOKS']['avisotaMailingListLabel'][] = array('AvisotaBackend', 'hookAvisotaMailingListLabel');


/**
 * Procedures
 */
$GLOBALS['TL_PROCEDURE']['member_to_mailing_list(IN MEMBER_ID INT, IN LIST_IDS BLOB)'] = '
-- clear the association table
DELETE FROM tl_member_to_mailing_list WHERE member=MEMBER_ID
	AND list NOT IN (SELECT id FROM tl_avisota_mailing_list WHERE FIND_IN_SET(id, LIST_IDS));

-- insert new association
INSERT INTO tl_member_to_mailing_list (member, list)
	SELECT MEMBER_ID, id FROM tl_avisota_mailing_list WHERE FIND_IN_SET(id, LIST_IDS)
		AND id NOT IN (SELECT list FROM tl_member_to_mailing_list WHERE member=MEMBER_ID);
';


/**
 * Multi Triggers
 */
$GLOBALS['TL_TRIGGER']['tl_avisota_recipient']['before']['delete'][]    = 'DELETE FROM tl_avisota_recipient_to_mailing_list WHERE recipient=OLD.id;';
$GLOBALS['TL_TRIGGER']['tl_member']['after']['insert'][]                = 'CALL member_to_mailing_list(NEW.id, NEW.avisota_lists);';
$GLOBALS['TL_TRIGGER']['tl_member']['after']['update'][]                = 'CALL member_to_mailing_list(NEW.id, NEW.avisota_lists);';
$GLOBALS['TL_TRIGGER']['tl_member']['before']['delete'][]               = 'DELETE FROM tl_member_to_mailing_list WHERE member=OLD.id;';
$GLOBALS['TL_TRIGGER']['tl_avisota_mailing_list']['before']['delete'][] = 'DELETE FROM tl_avisota_recipient_to_mailing_list WHERE list=OLD.id;
DELETE FROM tl_member_to_mailing_list WHERE list=OLD.id;';


/**
 * Graphical text support.
 */
if (in_array('graphicaltext', $this->getActiveModules())) {
	$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array('FrontendGraphicalText', 'replaceGraphicalTextTag');
}


/**
 * Custom user permissions.
 */
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_recipient_lists';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_recipient_list_permissions';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_recipient_permissions';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_newsletter_categories';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_newsletter_category_permissions';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_newsletter_permissions';


/**
 * Cron
 */
$GLOBALS['TL_CRON']['daily'][] = array('AvisotaBackend', 'cronCleanupRecipientList');
$GLOBALS['TL_CRON']['daily'][] = array('AvisotaBackend', 'cronNotifyRecipients');


/**
 * folderurl support
 */
$GLOBALS['URL_KEYWORDS'][] = 'item';


/**
 * Hack: Fix ajax load import source tree.
 */
if (($_GET['table'] == 'tl_avisota_recipient_import' || $_GET['table'] == 'tl_avisota_recipient_remove') && ($_GET['isAjax'] || $_POST['isAjax'])) {
	unset($_GET['table']);
}


/**
 * JavaScript inject
 */
if (TL_MODE == 'BE' && $_GET['do'] == 'avisota_recipients') {
	$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/Avisota/html/tl_avisota_recipient.js.php';
}


/**
 * Compatibility check
 */
if (version_compare(Database::getInstance()->query('SHOW VARIABLES WHERE Variable_name = \'version\'')->Value, '5', '<')) {
	$objEnvironment = Environment::getInstance();
	if ( // The update controller itself
		strpos($objEnvironment->requestUri, 'system/modules/Avisota/AvisotaCompatibilityController.php') === false
		// Backend login
		&& strpos($objEnvironment->requestUri, 'contao/index.php') === false
		// Extension manager
		&& strpos($objEnvironment->requestUri, 'contao/main.php?do=repository_manager') === false
		// Install Tool
		&& strpos($objEnvironment->requestUri, 'contao/install.php') === false
	) {
		header('Location: ' . $objEnvironment->url . $GLOBALS['TL_CONFIG']['websitePath'] . '/system/modules/Avisota/AvisotaCompatibilityController.php');
		exit;
	}
}

/**
 * Update script
 */
else if (TL_MODE == 'BE') {
	$objEnvironment = Environment::getInstance();
	if ($blnAvisotaUpdate
		// The update controller itself
		&& strpos($objEnvironment->requestUri, 'contao/main.php?do=avisota_update') === false
		// The system log
		&& strpos($objEnvironment->requestUri, 'contao/main.php?do=log') === false
		// Backend login
		&& strpos($objEnvironment->requestUri, 'contao/index.php') === false
		// Install Tool
		&& strpos($objEnvironment->requestUri, 'contao/install.php') === false
	) {
		header('Location: ' . $objEnvironment->url . $GLOBALS['TL_CONFIG']['websitePath'] . '/contao/main.php?do=avisota_update');
		exit;
	}
}
