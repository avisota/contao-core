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
 * Include dynamic generated informations
 */
if (file_exists(__DIR__ . '/dynamics.php')) {
	$GLOBALS['AVISOTA_DYNAMICS'] = include(__DIR__ . '/dynamics.php');
}


/**
 * Entities
 */
$GLOBALS['DOCTRINE_ENTITY_NAMESPACE_ALIAS']['Avisota\Contao']        = 'Avisota\Contao\Entity';
$GLOBALS['DOCTRINE_ENTITY_NAMESPACE_MAP']['orm_avisota']             = 'Avisota\Contao\Entity';
$GLOBALS['DOCTRINE_ENTITY_CLASS']['Avisota\Contao\Entity\Recipient'] = 'Avisota\Contao\Entity\AbstractRecipient';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_mailing_list';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_mailing';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_mailing_category';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_mailing_content';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_mailing_theme';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_queue';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_recipient';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_recipient_blacklist';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_recipient_source';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_recipient_subscription';
$GLOBALS['DOCTRINE_ENTITIES'][]                                      = 'orm_avisota_transport';


/**
 * Update check
 */
/*
$avisotaUpdateRequired = AvisotaUpdate::getInstance()
	->hasUpdates();
*/


/**
 * Request starttime
 */
if (!isset($_SERVER['REQUEST_TIME'])) {
	$_SERVER['REQUEST_TIME'] = time();
}


/**
 * Settings
 */
$GLOBALS['TL_CONFIG']['avisota_max_send_time']      = ini_get('max_execution_time') > 0 ? floor(
	0.85 * ini_get('max_execution_time')
) : 120;
$GLOBALS['TL_CONFIG']['avisota_max_send_count']     = 100;
$GLOBALS['TL_CONFIG']['avisota_max_send_timeout']   = 1;
$GLOBALS['TL_CONFIG']['avisota_notification_time']  = 3;
$GLOBALS['TL_CONFIG']['avisota_notification_count'] = 3;
$GLOBALS['TL_CONFIG']['avisota_cleanup_time']       = 14;


/**
 * Salutation
 */
if (!isset($GLOBALS['TL_CONFIG']['avisota_salutations'])) {
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array(
		'salutation' => 'Sehr geehrter Herr',
		'title'      => true,
		'firstname'  => true,
		'lastname'   => true
	);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array(
		'salutation' => 'Sehr geehrte Frau',
		'title'      => true,
		'firstname'  => true,
		'lastname'   => true
	);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array(
		'salutation' => 'Sehr geehrte/-r Herr/Frau',
		'title'      => true,
		'firstname'  => true,
		'lastname'   => true
	);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array(
		'salutation' => 'Sehr geehrter Herr',
		'title'      => false,
		'firstname'  => true,
		'lastname'   => true
	);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array(
		'salutation' => 'Sehr geehrte Frau',
		'title'      => false,
		'firstname'  => true,
		'lastname'   => true
	);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array(
		'salutation' => 'Sehr geehrte/-r Herr/Frau',
		'title'      => false,
		'firstname'  => true,
		'lastname'   => true
	);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array(
		'salutation' => 'Sehr geehrter',
		'title'      => false,
		'firstname'  => true,
		'lastname'   => true
	);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array(
		'salutation' => 'Sehr geehrte',
		'title'      => false,
		'firstname'  => true,
		'lastname'   => true
	);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array(
		'salutation' => 'Sehr geehrte/-r',
		'title'      => false,
		'firstname'  => true,
		'lastname'   => true
	);
	$GLOBALS['TL_CONFIG']['avisota_salutations'][] = array(
		'salutation' => 'Hallo',
		'title'      => false,
		'firstname'  => true,
		'lastname'   => false
	);
}
else if (is_string($GLOBALS['TL_CONFIG']['avisota_salutations'])) {
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
 * Build custom back end modules
 */
$customModules = array();
/*
$backendUser          = BackendUser::getInstance();
$database      = Database::getInstance();
if ($database->fieldExists('showInMenu', 'orm_avisota_mailing_category')) {
	$category = $database->query(
		'SELECT * FROM orm_avisota_mailing_category WHERE showInMenu=\'1\' ORDER BY title'
	);
	while ($category->next()) {
		$customModules['avisota_newsletter_' . $category->id]          = array(
			'href'       => 'table=orm_avisota_mailing&amp;id=' . $category->id,
			'tables'     => array(
				'orm_avisota_mailing_category',
				'orm_avisota_mailing',
				'orm_avisota_mailing_content',
				'orm_avisota_mailing_create_from_draft'
			),
			'send'       => array('Avisota', 'send'),
			'icon'       => $category->menuIcon ? $category->menuIcon
				: 'system/modules/avisota/html/newsletter.png',
			'stylesheet' => 'system/modules/avisota/assets/css/stylesheet.css'
		);
		$GLOBALS['TL_LANG']['MOD']['avisota_newsletter_' . $category->id] = array($category->title, '');
	}
}
*/

/**
 * Back end modules
 */
$i                 = array_search('design', array_keys($GLOBALS['BE_MOD']));
$GLOBALS['BE_MOD'] = array_merge(
	array_slice($GLOBALS['BE_MOD'], 0, $i),
	array
	(
	'avisota' => array_merge
	(
		array(
			 'avisota_outbox' => array
			 (
				 'callback'   => 'AvisotaBackendOutbox',
				 'icon'       => 'system/modules/avisota/html/outbox.png',
				 'stylesheet' => 'system/modules/avisota/assets/css/stylesheet.css'
			 )
		),
		$customModules,
		array(
			 'avisota_newsletter' => array
			 (
				 'tables'     => array(
					 'orm_avisota_mailing_category',
					 'orm_avisota_mailing',
					 'orm_avisota_mailing_content',
					 'orm_avisota_mailing_create_from_draft'
				 ),
				 'send'       => array('Avisota', 'send'),
				 'icon'       => 'system/modules/avisota/html/newsletter.png',
				 'stylesheet' => 'system/modules/avisota/assets/css/stylesheet.css'
			 ),
			 'avisota_recipients' => array
			 (
				 'tables'     => array(
					 'orm_avisota_recipient',
					 'orm_avisota_recipient_migrate',
					 'orm_avisota_recipient_import',
					 'orm_avisota_recipient_export',
					 'orm_avisota_recipient_remove',
					 'orm_avisota_recipient_notify'
				 ),
				 'icon'       => 'system/modules/avisota/html/recipients.png',
				 'stylesheet' => 'system/modules/avisota/assets/css/stylesheet.css',
				 'javascript' => 'system/modules/avisota/assets/css/backend.js'
			 )
		)
	)
	),
	array_slice($GLOBALS['BE_MOD'], $i)
);

$GLOBALS['BE_MOD']['system'] = array_merge(
	$GLOBALS['BE_MOD']['system'],
	array(
		 'avisota_config'           => array
		 (
			 'icon'          => 'system/modules/avisota/assets/images/avisota_config.png',
			 'stylesheet'    => 'system/modules/avisota/assets/css/stylesheet.css',
			 'nested-config' => array(
				 'headline' => false
			 )
		 ),
		 'avisota_settings'         => array
		 (
			 'nested'     => 'avisota_config',
			 'tables'     => array('tl_avisota_settings'),
			 'icon'       => 'system/modules/avisota/assets/images/settings.png',
			 'stylesheet' => 'system/modules/avisota/assets/css/stylesheet.css'
		 ),
		 'avisota_mailing_list'     => array
		 (
			 'nested'     => 'avisota_config:recipient',
			 'tables'     => array('orm_avisota_mailing_list'),
			 'icon'       => 'system/modules/avisota/assets/images/mailing_list.png',
			 'stylesheet' => 'system/modules/avisota/assets/css/stylesheet.css'
		 ),
		 'avisota_recipient_source' => array
		 (
			 'nested'     => 'avisota_config:recipient',
			 'tables'     => array('orm_avisota_recipient_source'),
			 'icon'       => 'system/modules/avisota/assets/images/recipient_source.png',
			 'stylesheet' => 'system/modules/avisota/assets/css/stylesheet.css'
		 ),
		 'avisota_theme'            => array
		 (
			 'nested'     => 'avisota_config:newsletter',
			 'tables'     => array('orm_avisota_mailing_theme'),
			 'icon'       => 'system/modules/avisota/assets/images/theme.png',
			 'stylesheet' => 'system/modules/avisota/assets/css/stylesheet.css'
		 ),
		 'avisota_queue'            => array
		 (
			 'nested'     => 'avisota_config:transport',
			 'tables'     => array('orm_avisota_queue'),
			 'icon'       => 'system/modules/avisota/assets/images/queue.png',
			 'stylesheet' => 'system/modules/avisota/assets/css/stylesheet.css'
		 ),
		 'avisota_transport'        => array
		 (
			 'nested'     => 'avisota_config:transport',
			 'tables'     => array('orm_avisota_transport'),
			 'icon'       => 'system/modules/avisota/assets/images/transport.png',
			 'stylesheet' => 'system/modules/avisota/assets/css/stylesheet.css'
		 ),
	)
);

// TODO gray out outbox if nothink in there!

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['avisota']['avisota_subscribe']    = 'Avisota\Contao\Module\Subscribe';
$GLOBALS['FE_MOD']['avisota']['avisota_unsubscribe']  = 'Avisota\Contao\Module\Unsubscribe';
$GLOBALS['FE_MOD']['avisota']['avisota_subscription'] = 'Avisota\Contao\Module\Subscription';
$GLOBALS['FE_MOD']['avisota']['avisota_list']         = 'Avisota\Contao\Module\List';
$GLOBALS['FE_MOD']['avisota']['avisota_reader']       = 'Avisota\Contao\Module\Reader';


/**
 * Newsletter elements
 */
$GLOBALS['TL_NLE'] = array_merge_recursive(
	array
	(
	'texts'    => array
	(
		'headline' => 'NewsletterHeadline',
		'text'     => 'NewsletterText',
		'list'     => 'NewsletterList',
		'table'    => 'NewsletterTable'
	),
	'links'    => array
	(
		'hyperlink' => 'NewsletterHyperlink'
	),
	'images'   => array
	(
		'image'   => 'NewsletterImage',
		'gallery' => 'NewsletterGallery'
	),
	'includes' => array
	(
		'news'    => 'NewsletterNews',
		'events'  => 'NewsletterEvent',
		'article' => 'NewsletterArticleTeaser'
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
$GLOBALS['orm_avisota_RECIPIENT_SOURCE']['integrated']                 = 'Avisota\Contao\RecipientSource\IntegratedRecipients';
$GLOBALS['orm_avisota_RECIPIENT_SOURCE']['integrated_by_mailing_list'] = 'Avisota\Contao\RecipientSource\IntegratedRecipientsByMailingList';
// $GLOBALS['orm_avisota_RECIPIENT_SOURCE']['member']     = 'Avisota\Contao\RecipientSource\MemberGroup';
$GLOBALS['orm_avisota_RECIPIENT_SOURCE']['csv_file'] = 'Avisota\RecipientSource\CSVFile';


/**
 * Queues
 */
$GLOBALS['orm_avisota_QUEUE']['simpleDatabase'] = 'Avisota\Queue\SimpleDatabaseQueue';

/**
 * Transport modules
 */
$GLOBALS['orm_avisota_TRANSPORT']['swift']   = 'Avisota\Transport\Swift';
$GLOBALS['orm_avisota_TRANSPORT']['service'] = 'Avisota\Transport\Service';


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
//$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('orm_avisota_mailing_content', 'myLoadDataContainer');
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
