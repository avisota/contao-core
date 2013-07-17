<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Constants
 */
define('AVISOTA_VERSION', '2.0.0');
define('AVISOTA_RELEASE', 'alpha1');
define('NL_HTML', 'html');
define('NL_PLAIN', 'plain');
define('AVISOTA_ROOT', dirname(__DIR__));


/**
 * Request starttime
 */
if (!isset($_SERVER['REQUEST_TIME'])) {
	$_SERVER['REQUEST_TIME'] = time();
}


/**
 * Load dynamic generated informations
 */
if (file_exists(__DIR__ . '/dynamics.php')) {
	$GLOBALS['AVISOTA_DYNAMICS'] = include(__DIR__ . '/dynamics.php');
}
else {
	$GLOBALS['AVISOTA_DYNAMICS'] = array();
}


/**
 * Load configs
 */
require __DIR__ . '/config-baseTemplates.php';
require __DIR__ . '/config-backendModules.php';
require __DIR__ . '/config-contentElements.php';
require __DIR__ . '/config-entities.php';
require __DIR__ . '/config-frontendModules.php';


/**
 * Events
 */
$GLOBALS['TL_EVENT_SUBSCRIBERS']['avisota-subscription-log'] = 'Avisota\Contao\SubscriptionLogger';


/**
 * Salutation selection decider
 */
$GLOBALS['AVISOTA_SALUTATION_DECIDER'][] = 'Avisota\Contao\Salutation\GenderDecider';
$GLOBALS['AVISOTA_SALUTATION_DECIDER'][] = 'Avisota\Contao\Salutation\RequiredFieldsDecider';
$GLOBALS['AVISOTA_SALUTATION_DECIDER'][] = 'Avisota\Contao\Salutation\FieldValueDecider';


/**
 * Settings
 */
/*
// TODO to be removed
$GLOBALS['TL_CONFIG']['avisota_max_send_time']      = ini_get('max_execution_time') > 0
	? floor(0.85 * ini_get('max_execution_time')) : 120;
$GLOBALS['TL_CONFIG']['avisota_max_send_count']     = 100;
$GLOBALS['TL_CONFIG']['avisota_max_send_timeout']   = 1;
$GLOBALS['TL_CONFIG']['avisota_notification_time']  = 3;
$GLOBALS['TL_CONFIG']['avisota_notification_count'] = 3;
$GLOBALS['TL_CONFIG']['avisota_cleanup_time']       = 14;
*/


/**
 * Page types
 */
$GLOBALS['TL_PTY']['avisota'] = 'PageAvisotaMailing';


/**
 * Form fields
 */
$GLOBALS['BE_FFL']['upload']                 = 'UploadField';
$GLOBALS['BE_FFL']['columnAssignmentWizard'] = 'ColumnAssignmentWizard';


/**
 * Widgets
 */
$GLOBALS['BE_FFL']['eventchooser'] = 'WidgetEventchooser';
$GLOBALS['BE_FFL']['newschooser']  = 'WidgetNewschooser';


/**
 * Recipient sources
 */
$GLOBALS['AVISOTA_RECIPIENT_SOURCE']['integrated']                 = 'Avisota\Contao\RecipientSource\IntegratedRecipients';
$GLOBALS['AVISOTA_RECIPIENT_SOURCE']['integrated_by_mailing_list'] = 'Avisota\Contao\RecipientSource\IntegratedRecipientsByMailingList';
$GLOBALS['AVISOTA_RECIPIENT_SOURCE']['csv_file']                   = 'Avisota\RecipientSource\CSVFile';


/**
 * Queues
 */
$GLOBALS['AVISOTA_QUEUE']['simpleDatabase'] = 'Avisota\Queue\SimpleDatabaseQueue';

/**
 * Transport modules
 */
$GLOBALS['AVISOTA_TRANSPORT']['swift']   = 'Avisota\Transport\Swift';
$GLOBALS['AVISOTA_TRANSPORT']['service'] = 'Avisota\Transport\Service';


/**
 * Hooks
 */
//$GLOBALS['TL_HOOKS']['outputBackendTemplate'][]   = array('AvisotaBackend', 'hookOutputBackendTemplate');
//$GLOBALS['TL_HOOKS']['replaceInsertTags'][]       = array('AvisotaInsertTag', 'hookReplaceNewsletterInsertTags');
//$GLOBALS['TL_HOOKS']['getEditorStylesLayout'][]   = array('AvisotaBackendEditorStyle', 'hookGetEditorStylesLayout');
//$GLOBALS['TL_HOOKS']['mysqlMultiTriggerCreate'][] = array('AvisotaUpdate', 'hookMysqlMultiTriggerCreate');
//$GLOBALS['TL_HOOKS']['createNewUser'][]           = array('AvisotaDCA', 'hookCreateNewUser');
//$GLOBALS['TL_HOOKS']['activateAccount'][]         = array('AvisotaDCA', 'hookActivateAccount');
//$GLOBALS['TL_HOOKS']['updatePersonalData'][]      = array('AvisotaDCA', 'hookUpdatePersonalData');
//$GLOBALS['TL_HOOKS']['avisotaMailingListLabel'][] = array('AvisotaBackend', 'hookAvisotaMailingListLabel');
//$GLOBALS['TL_HOOKS']['getUserNavigation'][]       = array('AvisotaBackend', 'hookGetUserNavigation');
//$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('orm_avisota_message_content', 'myLoadDataContainer');
$GLOBALS['TL_HOOKS']['nestedMenuPreContent'][]  = array('Avisota\Contao\Backend', 'hookNestedMenuPreContent');
$GLOBALS['TL_HOOKS']['nestedMenuPostContent'][] = array('Avisota\Contao\Backend', 'hookNestedMenuPostContent');


/**
 * Custom user permissions.
 */
/*
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_recipient_lists';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_recipient_list_permissions';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_recipient_permissions';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_newsletter_categories';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_newsletter_category_permissions';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_newsletter_permissions';
*/


/**
 * Cron
 */
/*
$GLOBALS['TL_CRON']['daily'][] = array('AvisotaBackend', 'cronCleanupRecipientList');
$GLOBALS['TL_CRON']['daily'][] = array('AvisotaBackend', 'cronNotifyRecipients');
*/

/**
 * folderurl support
 */
/*
$GLOBALS['URL_KEYWORDS'][] = 'item';
*/

/**
 * Hack: Fix ajax load import source tree.
 */
/*
if (($_GET['table'] == 'orm_avisota_recipient_import' || $_GET['table'] == 'orm_avisota_recipient_remove') && ($_GET['isAjax'] || $_POST['isAjax'])) {
	unset($_GET['table']);
}
*/


/**
 * JavaScript inject
 */
/*
if (TL_MODE == 'BE' && $_GET['do'] == 'avisota_recipients') {
	$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/avisota/assets/css/orm_avisota_recipient.js.php';
}
*/


/**
 * Compatibility check
 */
/*
if (version_compare(
	Database::getInstance()
		->query('SHOW VARIABLES WHERE Variable_name = \'version\'')->Value,
	'5',
	'<'
)
) {
	$environment = Environment::getInstance();
	if ( // The update controller itself
		strpos($environment->requestUri, 'system/modules/avisota/AvisotaCompatibilityController.php') === false
		// Backend login
		&& strpos($environment->requestUri, 'contao/index.php') === false
		// Extension manager
		&& strpos($environment->requestUri, 'contao/main.php?do=repository_manager') === false
		// Install Tool
		&& strpos($environment->requestUri, 'contao/install.php') === false
	) {
		header(
			'Location: ' . $environment->url . $GLOBALS['TL_CONFIG']['websitePath'] . '/system/modules/avisota/AvisotaCompatibilityController.php'
		);
		exit;
	}
}

/**
 * Update script
 * /
else if (TL_MODE == 'BE') {
	/*
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
	* /
}
*/
