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
 * Request start time
 */
if (!isset($_SERVER['REQUEST_TIME'])) {
	$_SERVER['REQUEST_TIME'] = time();
}


/**
 * Static back end modules
 */
$designModuleIndex = array_search('design', array_keys($GLOBALS['BE_MOD']));
$GLOBALS['BE_MOD'] = array_merge(
	array_slice($GLOBALS['BE_MOD'], 0, $designModuleIndex),
	array('avisota' => array()),
	array_slice($GLOBALS['BE_MOD'], $designModuleIndex)
);

$GLOBALS['BE_MOD']['avisota']['avisota_outbox']     = array(
	'callback'   => 'Avisota\Contao\Core\Backend\Outbox',
	'icon'       => 'assets/avisota/core/images/outbox.png',
	'stylesheet' => 'assets/avisota/core/css/stylesheet.css'
);
$GLOBALS['BE_MOD']['avisota']['avisota_config']           = array
(
	'icon'          => 'assets/avisota/core/images/avisota_config.png',
	'stylesheet'    => 'assets/avisota/core/css/stylesheet.css',
	'nested-config' => array(
		'headline' => false
	)
);
$GLOBALS['BE_MOD']['avisota']['avisota_settings']         = array
(
	'nested'     => 'avisota_config',
	'tables'     => array('tl_avisota_settings'),
	'icon'       => 'assets/avisota/core/images/settings.png',
	'stylesheet' => 'assets/avisota/core/css/stylesheet.css'
);
$GLOBALS['BE_MOD']['avisota']['avisota_mailing_list']     = array
(
	'nested'     => 'avisota_config:recipient',
	'tables'     => array('orm_avisota_mailing_list'),
	'icon'       => 'assets/avisota/core/images/mailing_list.png',
	'stylesheet' => 'assets/avisota/core/css/stylesheet.css'
);
$GLOBALS['BE_MOD']['avisota']['avisota_recipient_source'] = array
(
	'nested'     => 'avisota_config:recipient',
	'tables'     => array('orm_avisota_recipient_source'),
	'icon'       => 'assets/avisota/core/images/recipient_source.png',
	'stylesheet' => 'assets/avisota/core/css/stylesheet.css'
);
$GLOBALS['BE_MOD']['avisota']['avisota_queue']            = array
(
	'nested'     => 'avisota_config:transport',
	'tables'     => array('orm_avisota_queue'),
	'icon'       => 'assets/avisota/core/images/queue.png',
	'stylesheet' => 'assets/avisota/core/css/stylesheet.css'
);
$GLOBALS['BE_MOD']['avisota']['avisota_transport']        = array
(
	'nested'     => 'avisota_config:transport',
	'tables'     => array('orm_avisota_transport'),
	'icon'       => 'assets/avisota/core/images/transport.png',
	'stylesheet' => 'assets/avisota/core/css/stylesheet.css'
);
$GLOBALS['BE_MOD']['avisota']['avisota_support']          = array
(
	'icon'       => 'assets/avisota/core/images/avisota_support.png',
	'stylesheet' => 'assets/avisota/core/css/stylesheet.css',
	'callback'   => 'Avisota\Contao\Core\Backend\Support',
);


/**
 * Entities
 */
$GLOBALS['DOCTRINE_ENTITY_NAMESPACE_ALIAS']['Avisota\Contao'] = 'Avisota\Contao\Entity';

$GLOBALS['DOCTRINE_ENTITY_NAMESPACE_MAP']['orm_avisota'] = 'Avisota\Contao\Entity';

$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_mailing_list';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_recipient_source';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_queue';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_transport';

/**
 * Transport renderer
 */
$GLOBALS['AVISOTA_TRANSPORT_RENDERER']['native'] = 'Avisota\Renderer\NativeMessageRenderer';

/**
 * Events
 */
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = function() {
	return $GLOBALS['container']['avisota.core.options-builder'];
};
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Avisota\Contao\Core\DataContainer\RecipientSource';

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
*/


/**
 * Page types
 */
// TODO to be removed
// $GLOBALS['TL_PTY']['avisota'] = 'PageAvisotaMailing';


/**
 * Form fields
 */
$GLOBALS['BE_FFL']['upload']                 = 'UploadField';
$GLOBALS['BE_FFL']['columnAssignmentWizard'] = 'ColumnAssignmentWizard';


/**
 * Widgets
 */
// TODO to be removed
// $GLOBALS['BE_FFL']['eventchooser'] = 'WidgetEventchooser';
// $GLOBALS['BE_FFL']['newschooser']  = 'WidgetNewschooser';


/**
 * Recipient sources
 */
$GLOBALS['AVISOTA_RECIPIENT_SOURCE']['csv_file'] = 'Avisota\Contao\Core\RecipientSource\CSVFileFactory';
$GLOBALS['AVISOTA_RECIPIENT_SOURCE']['dummy']    = 'Avisota\Contao\Core\RecipientSource\DummyFactory';

/**
 * Queues
 */
$GLOBALS['AVISOTA_QUEUE']['simpleDatabase'] = 'Avisota\Contao\Core\Queue\SimpleDatabaseQueueFactory';

/**
 * Transport modules
 */
$GLOBALS['AVISOTA_TRANSPORT']['swift']   = 'Avisota\Contao\Core\Transport\SwiftTransportFactory';
$GLOBALS['AVISOTA_TRANSPORT']['service'] = 'Avisota\Contao\Core\Transport\ServiceTransportFactory';


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

$GLOBALS['TL_HOOKS']['initializeDependencyContainer']['avisota-core-services'] = array(
	'Avisota\Contao\Core\ServiceFactory',
	'init'
);
$GLOBALS['TL_HOOKS']['nestedMenuPreContent']['avisota-core-nested-menu']  = array(
	'Avisota\Contao\Core\Backend\NestedMenu',
	'hookNestedMenuPreContent'
);
$GLOBALS['TL_HOOKS']['nestedMenuPostContent']['avisota-core-nested-menu'] = array(
	'Avisota\Contao\Core\Backend\NestedMenu',
	'hookNestedMenuPostContent'
);
$GLOBALS['TL_HOOKS']['getUserNavigation']['avisota-core-custom-menu']     = array(
	'Avisota\Contao\Core\Backend\CustomMenu',
	'hookGetUserNavigation'
);

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
	$GLOBALS['TL_JAVASCRIPT'][] = 'assets/avisota/core/css/orm_avisota_recipient.js.php';
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
